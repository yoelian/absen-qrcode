<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Position;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Roles
        $adminRole = Role::findOrCreate('admin', 'web');
        $operatorRole = Role::findOrCreate('operator', 'web');

        // 2. Setup Default Admin & Operator
        $admin = User::firstOrCreate(
            ['email' => 'admin@absenpro.local'],
            [
                'name' => 'Administrator SMAN 1 Bentian Besar',
                'password' => bcrypt('password'),
            ]
        );
        if (! $admin->hasRole($adminRole)) {
            $admin->assignRole($adminRole);
        }

        $operator = User::firstOrCreate(
            ['email' => 'operator@absenpro.local'],
            [
                'name' => 'Operator Absensi',
                'password' => bcrypt('password'),
            ]
        );
        if (! $operator->hasRole($operatorRole)) {
            $operator->assignRole($operatorRole);
        }

        // 3. Setup Default Settings
        Setting::set('school_name', 'SMAN 1 Bentian Besar');
        Setting::set('school_address', 'Jl. Trans Kalimantan, Bentian Besar');
        Setting::set('school_logo', null);

        Setting::set('reguler_in', '07:15');
        Setting::set('reguler_out', '14:00');

        Setting::set('security_morning_in', '07:00');
        Setting::set('security_morning_out', '19:00');
        Setting::set('security_night_in', '19:00');
        Setting::set('security_night_out', '07:00');

        // 4. Setup Default Categories
        $categoriesData = [
            ['name' => 'Siswa',    'code' => 'SIS', 'badge_color' => 'primary'],
            ['name' => 'Guru',     'code' => 'GUR', 'badge_color' => 'success'],
            ['name' => 'TU',       'code' => 'TU',  'badge_color' => 'info'],
            ['name' => 'Security', 'code' => 'SEC', 'badge_color' => 'warning'],
            ['name' => 'CS',       'code' => 'CS',  'badge_color' => 'secondary'],
        ];

        foreach ($categoriesData as $cat) {
            Category::firstOrCreate(['code' => $cat['code']], $cat);
        }

        // 5. Setup Default Positions / Kelas
        $siswa  = Category::where('code', 'SIS')->first();
        $guru   = Category::where('code', 'GUR')->first();
        $tu     = Category::where('code', 'TU')->first();
        $sec    = Category::where('code', 'SEC')->first();
        $cs     = Category::where('code', 'CS')->first();

        // Kelas Siswa
        if ($siswa) {
            $kelasSiswa = [
                'X-IPA-1','X-IPA-2','X-IPS-1','X-IPS-2',
                'XI-IPA-1','XI-IPA-2','XI-IPS-1','XI-IPS-2',
                'XII-IPA-1','XII-IPA-2','XII-IPS-1','XII-IPS-2',
            ];
            foreach ($kelasSiswa as $k) {
                Position::firstOrCreate(['category_id' => $siswa->id, 'name' => $k]);
            }
        }

        // Jabatan Guru
        if ($guru) {
            foreach (['Guru Matematika','Guru Bahasa Indonesia','Guru Bahasa Inggris','Guru Fisika','Guru Kimia','Guru Biologi','Guru Sejarah','Guru Ekonomi','Guru Pendidikan Agama','Wali Kelas','Kepala Sekolah','Wakil Kepala Sekolah'] as $j) {
                Position::firstOrCreate(['category_id' => $guru->id, 'name' => $j]);
            }
        }

        // Jabatan TU
        if ($tu) {
            foreach (['Kepala TU','Staff Administrasi','Bendahara','Staff Kesiswaan','Staff Kurikulum'] as $j) {
                Position::firstOrCreate(['category_id' => $tu->id, 'name' => $j]);
            }
        }

        // Jabatan Security
        if ($sec) {
            foreach (['Security Pagi','Security Malam'] as $j) {
                Position::firstOrCreate(['category_id' => $sec->id, 'name' => $j]);
            }
        }

        // Jabatan CS
        if ($cs) {
            foreach (['Cleaning Service'] as $j) {
                Position::firstOrCreate(['category_id' => $cs->id, 'name' => $j]);
            }
        }
    }
}
