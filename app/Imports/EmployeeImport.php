<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Category;
use App\Models\Position;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EmployeeImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private array $categoryCache = [];
    private array $positionCache = [];
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    /**
     * Konversi setiap baris Excel menjadi model Employee.
     */
    public function model(array $row): ?Employee
    {
        // Resolve category_id dari kode kategori (SIS, GUR, dll.)
        $categoryCode = strtoupper(trim($row['kode_kategori'] ?? ''));
        $categoryId   = $this->resolveCategory($categoryCode);

        if (!$categoryId) {
            $this->skippedCount++;
            return null; // Skip jika kategori tidak ditemukan
        }

        // Resolve position_id dari nama jabatan/kelas
        $positionName = trim($row['jabatan_kelas'] ?? '');
        $positionId   = $positionName
            ? $this->resolvePosition($positionName, $categoryId)
            : null;

        $code = strtoupper(trim($row['nomor_induk'] ?? ''));

        // Skip jika kode sudah ada
        if (Employee::where('code', $code)->exists()) {
            $this->skippedCount++;
            return null;
        }

        $isActive = 1;
        $statusRaw = strtolower(trim($row['status'] ?? 'aktif'));
        if (in_array($statusRaw, ['0', 'tidak aktif', 'nonaktif', 'no', 'false'])) {
            $isActive = 0;
        }

        $this->importedCount++;

        return new Employee([
            'code'        => $code,
            'name'        => trim($row['nama_lengkap'] ?? ''),
            'category_id' => $categoryId,
            'position_id' => $positionId,
            'is_active'   => $isActive,
        ]);
    }

    /**
     * Aturan validasi per baris
     */
    public function rules(): array
    {
        return [
            'nomor_induk'  => 'required',
            'nama_lengkap' => 'required|string',
            'kode_kategori' => 'required|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nomor_induk.required'   => 'Kolom Nomor Induk wajib diisi.',
            'nama_lengkap.required'  => 'Kolom Nama Lengkap wajib diisi.',
            'kode_kategori.required' => 'Kolom Kode Kategori wajib diisi.',
        ];
    }

    private function resolveCategory(string $code): ?int
    {
        if (!$code) return null;

        if (!isset($this->categoryCache[$code])) {
            $cat = Category::where('code', $code)->first();
            $this->categoryCache[$code] = $cat?->id;
        }

        return $this->categoryCache[$code];
    }

    private function resolvePosition(string $name, int $categoryId): ?int
    {
        $key = "{$categoryId}_{$name}";

        if (!isset($this->positionCache[$key])) {
            $pos = Position::where('category_id', $categoryId)
                ->where('name', $name)
                ->first();

            // Auto-create jika belum ada
            if (!$pos) {
                $pos = Position::create([
                    'category_id' => $categoryId,
                    'name'        => $name,
                ]);
            }

            $this->positionCache[$key] = $pos?->id;
        }

        return $this->positionCache[$key];
    }
}
