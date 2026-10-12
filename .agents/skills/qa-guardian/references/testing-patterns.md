# Testing Patterns & Mocking Recipes

Practical testing recipes for building rock-solid Laravel test suites.

---

## 1. Frozen Time Testing
Test logic dependent on specific hours (e.g. late threshold after 07:15 AM) by freezing system time:

```php
public function test_attendance_marks_late_after_cutoff(): void
{
    // Freeze time at 07:30 AM
    $this->travelTo(now()->setTime(7, 30, 0));

    $response = $this->postJson('/attendance/scan', ['qr_code' => 'QR-123']);

    $response->assertOk()
        ->assertJsonPath('data.status', 'terlambat');
}
```

---

## 2. Mocking File Storage / Downloads
Test Excel / PDF exports without littering disk:

```php
use Illuminate\Support\Facades\Storage;

public function test_monthly_report_excel_download(): void
{
    Storage::fake('local');

    $response = $this->get('/reports/excel?month=10&year=2026');

    $response->assertOk();
    $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
}
```
