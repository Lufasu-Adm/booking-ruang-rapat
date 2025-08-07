<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DivisionSeeder
 * @package Database\Seeders
 *
 * Seeder untuk mengisi tabel 'divisions' dengan data dummy.
 * Data diambil dari array mentah dan dimasukkan ke tabel.
 */
class DivisionSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $raw = [
            'Divisi Rekayasa Umum',
            'Divisi Kapal Niaga',
            'Supply Chain',
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

        foreach ($raw as $name) {
            // Hilangkan awalan "Divisi " jika ada
            $clean = preg_replace('/^Divisi\s+/i', '', $name);

            DB::table('divisions')->insertOrIgnore([
                'name'       => $clean,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}