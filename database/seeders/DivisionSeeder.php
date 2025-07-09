<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            'Divisi Rekayasa Umum',
            'Divisi Kapal Niaga',
            'Divisi Supply Chain',
            'Divisi Perencanaan Strategis Perusahaan',
            'Divisi Akuntansi',
            'Divisi Human Capital Management',
            'Divisi Manajemen Risiko',
            'Divisi Technology & Quality Assurance',
            'Divisi Legal',
            'Divisi Desain',
            'Direktorat Utama',
            'Sekretaris Perusahaan',
            'Satuan Pengawas Intern',
            'Divisi Teknologi Informasi',
            'Divisi Office of The Board',
            'Divisi Perbendaharaan',
            'Direktorat Produksi',
            'Divisi Kapal Perang',
            'Divisi Kapal Selam',
            'Divisi Production Management Office',
            'Divisi Penjualan Rekayasa Umum & Pemeliharaan-Perbaikan',
            'Direktorat Pemasaran',
            'Divisi Pemasaran & Penjualan Kapal',
            'Divisi Manajemen Aset, Infrastruktur, & K3LH',
            'Divisi Pemeliharaan & Perbaikan',
            'Direktorat Keuangan, Manajemen Risiko, & SDM',
        ];

        foreach ($divisions as $name) {
            DB::table('divisions')->insertOrIgnore([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}