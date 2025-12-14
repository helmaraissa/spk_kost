<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Criteria;
use App\Models\SubCriteria;

class SubCriteriaSeeder extends Seeder
{
    public function run()
    {
        // 1. CARI ID KRITERIA (Berdasarkan Nama)
        $kriteriaHarga = Criteria::where('criteria_name', 'LIKE', '%Harga%')->first();
        $kriteriaJarak = Criteria::where('criteria_name', 'LIKE', '%Jarak%')->first();
        $kriteriaFasilitas = Criteria::where('criteria_name', 'LIKE', '%Fasilitas%')->first();
        $kriteriaLuas = Criteria::where('criteria_name', 'LIKE', '%Luas%')->first();

        // 2. RESET DATA LAMA (Agar tidak duplikat)
        if ($kriteriaHarga) SubCriteria::where('criteria_id', $kriteriaHarga->criteria_id)->delete();
        if ($kriteriaJarak) SubCriteria::where('criteria_id', $kriteriaJarak->criteria_id)->delete();
        if ($kriteriaFasilitas) SubCriteria::where('criteria_id', $kriteriaFasilitas->criteria_id)->delete();
        if ($kriteriaLuas) SubCriteria::where('criteria_id', $kriteriaLuas->criteria_id)->delete();

        // 3. ISI DATA BARU (SESUAI STANDAR EXCEL SAW)

        // --- C1: HARGA (Cost) ---
        // Murah = Nilai Tinggi (5), Mahal = Nilai Rendah (1)
        if ($kriteriaHarga) {
            $dataHarga = [
                ['name' => '< Rp 500.000 (Sangat Murah)', 'value' => 5],
                ['name' => 'Rp 500.000 - Rp 800.000 (Murah)', 'value' => 4],
                ['name' => 'Rp 800.000 - Rp 1.200.000 (Sedang)', 'value' => 3],
                ['name' => 'Rp 1.200.000 - Rp 1.500.000 (Mahal)', 'value' => 2],
                ['name' => '> Rp 1.500.000 (Sangat Mahal)', 'value' => 1],
            ];
            foreach ($dataHarga as $d) {
                SubCriteria::create(['criteria_id' => $kriteriaHarga->criteria_id, 'name' => $d['name'], 'value' => $d['value']]);
            }
        }

        // --- C2: JARAK (Cost) ---
        // Dekat = Nilai Tinggi (5), Jauh = Nilai Rendah (1)
        if ($kriteriaJarak) {
            $dataJarak = [
                ['name' => '< 500 Meter (Sangat Dekat)', 'value' => 5],
                ['name' => '500 - 1000 Meter (Dekat)', 'value' => 4],
                ['name' => '1000 - 2000 Meter (Sedang)', 'value' => 3],
                ['name' => '2000 - 3000 Meter (Jauh)', 'value' => 2],
                ['name' => '> 3000 Meter (Sangat Jauh)', 'value' => 1],
            ];
            foreach ($dataJarak as $d) {
                SubCriteria::create(['criteria_id' => $kriteriaJarak->criteria_id, 'name' => $d['name'], 'value' => $d['value']]);
            }
        }

        // --- C3: FASILITAS (Benefit) ---
        if ($kriteriaFasilitas) {
            $dataFasilitas = [
                ['name' => 'Sangat Lengkap (AC, Wifi, KM Dalam, Parkir Luas)', 'value' => 5],
                ['name' => 'Lengkap (Wifi, KM Dalam, Parkir)', 'value' => 4],
                ['name' => 'Cukup (Kasur, Lemari, KM Luar)', 'value' => 3],
                ['name' => 'Kurang (Kosongan, KM Luar)', 'value' => 2],
                ['name' => 'Sangat Kurang (Tidak Layak)', 'value' => 1],
            ];
            foreach ($dataFasilitas as $d) {
                SubCriteria::create(['criteria_id' => $kriteriaFasilitas->criteria_id, 'name' => $d['name'], 'value' => $d['value']]);
            }
        }

        // --- C4: LUAS KAMAR (Benefit) ---
        // Luas = Nilai Tinggi (5)
        if ($kriteriaLuas) {
            $dataLuas = [
                ['name' => 'Sangat Luas (> 5x5 m²)', 'value' => 5],
                ['name' => 'Luas (4x5 m²)', 'value' => 4],
                ['name' => 'Cukup Luas (4x4 m²)', 'value' => 3],
                ['name' => 'Sedang (3x4 m²)', 'value' => 2],
                ['name' => 'Kecil (3x3 m²)', 'value' => 1],
            ];
            foreach ($dataLuas as $d) {
                SubCriteria::create(['criteria_id' => $kriteriaLuas->criteria_id, 'name' => $d['name'], 'value' => $d['value']]);
            }
        }

        $this->command->info('✅ Data Sub-Kriteria (Harga, Jarak, Fasilitas, Luas) berhasil disesuaikan!');
    }
}