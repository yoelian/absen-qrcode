<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Position;
use Illuminate\Support\Str;

class DummyEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::with('positions')->get();

        if ($categories->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 100; $i++) {
            $category = $categories->random();
            $position = $category->positions->random();

            Employee::create([
                'code' => 'DUMMY' . str_pad($i, 4, '0', STR_PAD_LEFT) . Str::random(2),
                'name' => 'Karyawan/Siswa Dummy ' . $i,
                'category_id' => $category->id,
                'position_id' => $position->id,
                'is_active' => true,
            ]);
        }
    }
}
