---
name: qa-guardian
description: >-
  Architect, implement, and maintain automated testing suites using Pest, PHPUnit, and Cypress/Playwright. Enforces regression prevention, test data factories, edge-case coverage, and mutation testing. Use whenever writing tests, investigating regressions, validating critical business logic, or establishing CI test gates.
---

# 🧪 QA Guardian: Automated Testing & Regression Defense

This skill enforces high-standard automated testing protocols, shifting software development from fragile manual checks to bulletproof automated test suites.

---

## 🎯 1. Testing Pyramid & Principles

*   **Fast & Deterministic:** Tests must run locally in seconds. Never rely on external APIs or live network connections; always mock third-party dependencies.
*   **Test Behavior, Not Implementation Details:** Write assertions against observable outcomes (database state, HTTP responses, emitted events) rather than private internal method calls.
*   **Zero Tolerance for Flaky Tests:** If a test fails intermittently due to timing or concurrency, fix the underlying race condition or deterministic clock control (`now()->setTestNow(...)`).

---

## 🧪 2. Test Classification

1.  **Feature / Integration Tests (70% Focus):**
    *   Simulate full HTTP requests through routes, middleware, controllers, and database assertions.
    *   Example: Student scans card ➔ Attendance record created with status 'hadir' ➔ JSON response returned with 200 OK.
2.  **Unit Tests (20% Focus):**
    *   Test isolated domain logic, utility calculation, or formatters without hitting the database.
3.  **End-to-End / Smoke Tests (10% Focus):**
    *   Validate critical user journeys (e.g. login, export report to Excel, run monthly recap).

---

## 📋 3. Laravel / PHP Testing Protocol

```php
use Tests\TestCase;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_employee_can_record_attendance_successfully(): void
    {
        $employee = Employee::factory()->create([
            'qr_code' => 'QR-12345',
            'status'  => 'active',
        ]);

        $response = $this->postJson('/attendance/scan', [
            'qr_code' => 'QR-12345',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
        ]);
    }

    public function test_inactive_employee_cannot_record_attendance(): void
    {
        $employee = Employee::factory()->create([
            'qr_code' => 'QR-INACTIVE',
            'status'  => 'inactive',
        ]);

        $response = $this->postJson('/attendance/scan', [
            'qr_code' => 'QR-INACTIVE',
        ]);

        $response->assertStatus(422);
    }
}
```

---

## 🔍 4. Edge-Case Matrix Checklist

Before approving any feature:
*   [ ] **Null / Empty inputs:** Empty string, null, missing keys.
*   [ ] **Boundary values:** Maximum string lengths, future/past dates, zero amounts.
*   [ ] **Duplicate submissions:** Scanning the same ID card multiple times in under 3 seconds.
*   [ ] **Authorization boundaries:** Guest vs regular user vs super-admin.
*   [ ] **Deletion cascades:** Deleting a student who has attendance logs (soft deletes vs foreign key protection).

---

## 📚 Supporting References
*   [Testing Patterns & Mocking Recipes](./references/testing-patterns.md)
