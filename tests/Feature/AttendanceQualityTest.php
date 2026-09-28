<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class AttendanceQualityTest extends TestCase
{
    use RefreshDatabase;

    protected Category $studentCategory;
    protected Category $securityCategory;
    protected Employee $studentEmployee;
    protected Employee $securityEmployee;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Master Categories
        $this->studentCategory = Category::create([
            'name' => 'Siswa',
            'code' => 'SIS',
            'target_in_time' => '07:00',
            'target_out_time' => '15:00',
        ]);

        $this->securityCategory = Category::create([
            'name' => 'Security',
            'code' => 'SEC',
            'target_in_time' => '07:00',
            'target_out_time' => '19:00',
        ]);

        $posSiswa = Position::create(['name' => 'X-IPA-1', 'category_id' => $this->studentCategory->id]);
        $posSecurity = Position::create(['name' => 'Danru', 'category_id' => $this->securityCategory->id]);

        // 2. Setup Employees
        $this->studentEmployee = Employee::create([
            'name' => 'Budi Santoso',
            'code' => 'SIS001',
            'category_id' => $this->studentCategory->id,
            'position_id' => $posSiswa->id,
            'is_active' => true,
        ]);

        $this->securityEmployee = Employee::create([
            'name' => 'Pak Joko',
            'code' => 'SEC001',
            'category_id' => $this->securityCategory->id,
            'position_id' => $posSecurity->id,
            'is_active' => true,
        ]);

        // 3. Setup Admin User
        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // 4. Default Settings
        Setting::set('scan_cooldown_minutes', 1);
        Setting::set('security_morning_in', '07:00');
        Setting::set('security_night_in', '19:00');
    }

    /** @test */
    public function test_scan_regular_check_in_on_time()
    {
        Carbon::setTestNow(Carbon::parse('2026-09-28 06:45:00'));

        $response = $this->postJson(route('attendance.scan'), [
            'code' => 'SIS001'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'check_in',
                'name' => 'Budi Santoso',
                'status' => 'hadir'
            ]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-09-28',
            'status' => 'hadir'
        ]);
    }

    /** @test */
    public function test_scan_regular_check_in_late()
    {
        Carbon::setTestNow(Carbon::parse('2026-09-28 07:15:00'));

        $response = $this->postJson(route('attendance.scan'), [
            'code' => 'SIS001'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'check_in',
                'status' => 'terlambat'
            ]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-09-28',
            'status' => 'terlambat'
        ]);
    }

    /** @test */
    public function test_scan_regular_cooldown_prevents_immediate_checkout()
    {
        Carbon::setTestNow(Carbon::parse('2026-09-28 06:50:00'));
        $this->postJson(route('attendance.scan'), ['code' => 'SIS001']);

        // 30 seconds later (within 1 minute cooldown)
        Carbon::setTestNow(Carbon::parse('2026-09-28 06:50:30'));
        $response = $this->postJson(route('attendance.scan'), ['code' => 'SIS001']);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);

        // 2 minutes later (after cooldown)
        Carbon::setTestNow(Carbon::parse('2026-09-28 06:52:00'));
        $responseAfterCooldown = $this->postJson(route('attendance.scan'), ['code' => 'SIS001']);

        $responseAfterCooldown->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'check_out',
            ]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-09-28',
            'check_out' => '06:52:00'
        ]);
    }

    /** @test */
    public function test_scan_unregistered_or_inactive_code()
    {
        $response = $this->postJson(route('attendance.scan'), [
            'code' => 'INVALID_CODE'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }

    /** @test */
    public function test_security_night_shift_checkin_and_cross_day_checkout()
    {
        // Day 1: Night Shift Check-In at 19:00:00
        Carbon::setTestNow(Carbon::parse('2026-09-28 19:00:00'));
        $inResponse = $this->postJson(route('attendance.scan'), [
            'code' => 'SEC001'
        ]);

        $inResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'check_in',
                'status' => 'hadir'
            ]);

        // Day 2: Morning Check-Out at 07:00:00 (12 hours later)
        Carbon::setTestNow(Carbon::parse('2026-09-29 07:00:00'));
        $outResponse = $this->postJson(route('attendance.scan'), [
            'code' => 'SEC001'
        ]);

        $outResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'check_out'
            ]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->securityEmployee->id,
            'date' => '2026-09-28',
            'check_in' => '19:00:00',
            'check_out' => '07:00:00'
        ]);
    }

    /** @test */
    public function test_manual_status_update_and_close_today()
    {
        $this->actingAs($this->adminUser);

        // 1. Update status to 'izin'
        $response = $this->postJson(route('attendances.updateStatus'), [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-09-28',
            'status' => 'izin'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-09-28',
            'status' => 'izin'
        ]);

        // 2. Close today should mark un-attended active employees as alpha and uncompleted check-outs as TAP
        Carbon::setTestNow(Carbon::parse('2026-09-28 17:00:00'));
        $closeResponse = $this->post(route('attendances.close-today'));
        $closeResponse->assertSessionHas('success');

        // Security employee had no attendance on 2026-09-28, so they should be marked alpha
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->securityEmployee->id,
            'date' => '2026-09-28',
            'status' => 'alpha'
        ]);
    }

    /** @test */
    public function test_leave_submission_creates_calendar_entries_atomically()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post(route('leaves.store'), [
            'employee_id' => $this->studentEmployee->id,
            'type' => 'sakit',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-03',
            'reason' => 'Demam tinggi'
        ]);

        $response->assertRedirect(route('leaves.index'));

        $this->assertDatabaseHas('leaves', [
            'employee_id' => $this->studentEmployee->id,
            'type' => 'sakit'
        ]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-10-01',
            'status' => 'sakit'
        ]);
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-10-02',
            'status' => 'sakit'
        ]);
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->studentEmployee->id,
            'date' => '2026-10-03',
            'status' => 'sakit'
        ]);
    }

    /** @test */
    public function test_log_file_path_traversal_is_prevented()
    {
        $this->actingAs($this->adminUser);

        // Attempt directory traversal
        $response = $this->get(route('settings.logs', ['file' => '../../app/Models/User.php']));
        $response->assertStatus(200);

        $clearResponse = $this->post(route('settings.clearLogs'), ['file' => '../../composer.json']);
        $clearResponse->assertSessionHas('error');
    }

    /** @test */
    public function test_avatar_route_sanitization()
    {
        // Traversal attempt
        $response = $this->get('/avatar/../../.env');
        $response->assertRedirect(asset('images/default-avatar.png'));
    }
}
