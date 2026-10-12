# Architectural Patterns & Best Practices

Reference patterns for writing resilient, maintainable, and high-performance applications.

---

## 1. Action / Service Pattern for Business Logic

Keep controllers thin and focused on HTTP orchestration. Encapsulate business logic into dedicated Action or Service classes:

```php
namespace App\Actions\Attendance;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordAttendanceAction
{
    public function execute(string $qrCode, string $ipAddress): Attendance
    {
        return DB::transaction(function () use ($qrCode, $ipAddress) {
            $employee = Employee::where('qr_code', $qrCode)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if (! $employee) {
                throw ValidationException::withMessages([
                    'qr_code' => ['Kartu atau QR Code tidak terdaftar atau nonaktif.'],
                ]);
            }

            // Record attendance atomically
            return Attendance::create([
                'employee_id' => $employee->id,
                'scanned_at'  => now(),
                'ip_address'  => $ipAddress,
            ]);
        });
    }
}
```

---

## 2. Safe Database Transactions & Concurrency

When updating related tables, always enforce transactional consistency:

```php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

try {
    DB::transaction(function () {
        // Step 1: Update primary entity
        // Step 2: Log audit or sync related data
    }, attempts: 3); // Automatically retry deadlocks up to 3 times
} catch (\Throwable $e) {
    Log::error('Transaction failed during operation', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
    throw $e;
}
```

---

## 3. High-Volume Batch Processing (Memory-Safe)

Never load thousands of records into memory with `all()` or `get()`. Use chunking or cursors:

```php
// Memory-safe iteration over thousands of records
Employee::with(['category', 'position'])
    ->where('status', 'active')
    ->chunkById(200, function ($employees) {
        foreach ($employees as $employee) {
            // Process individual record
        }
    });
```

---

## 4. Resilient External I/O with Retries and Backoff

Handle network or file system flakiness with exponential backoff:

```php
use Illuminate\Support\Facades\Http;

$response = Http::timeout(5)
    ->retry(3, 100, function ($exception, $request) {
        return $exception instanceof \Illuminate\Http\Client\ConnectionException;
    })
    ->get('https://api.example.com/sync');
```
