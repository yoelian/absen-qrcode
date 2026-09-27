<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTemplateDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_downloads_the_employee_template_excel(): void
    {
        $user = User::factory()->create();
        Category::create([
            'name' => 'Siswa',
            'code' => 'SIS',
        ]);

        $response = $this->actingAs($user)->get(route('employees.template'));

        $response->assertOk();

        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertNotNull($contentDisposition);
        $this->assertStringContainsString('Template_Import_Siswa_Staff.xlsx', $contentDisposition);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
