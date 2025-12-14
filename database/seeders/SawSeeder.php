<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SawSeeder extends Seeder
{
    public function run()
    {
        // 1. Kosongkan tabel lama agar tidak duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('evaluations')->truncate();
        DB::table('sub_criterias')->truncate();
        DB::table('criterias')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Insert Kriteria (C1 - C4)
        $c1 = DB::table('criterias')->insertGetId([
            'code' => 'C1', 'criteria_name' => 'Harga', 'attribute' => 'cost', 'weight' => 0.35, 'created_at' => now(), 'updated_at' => now()
        ]);
        $c2 = DB::table('criterias')->insertGetId([
            'code' => 'C2', 'criteria_name' => 'Jarak', 'attribute' => 'cost', 'weight' => 0.25, 'created_at' => now(), 'updated_at' => now()
        ]);
        $c3 = DB::table('criterias')->insertGetId([
            'code' => 'C3', 'criteria_name' => 'Fasilitas', 'attribute' => 'benefit', 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()
        ]);
        $c4 = DB::table('criterias')->insertGetId([
            'code' => 'C4', 'criteria_name' => 'Luas', 'attribute' => 'benefit', 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()
        ]);

        // 3. Insert Sub-Kriteria
        
        // --- C1: Harga (Cost) ---
        $subsC1 = [
            ['name' => '>= 1.000.000', 'value' => 1],
            ['name' => '>= 850.000 dan < 1.000.000', 'value' => 2],
            ['name' => '>= 650.000 dan < 850.000', 'value' => 3],
            ['name' => '>= 500.000 dan < 650.000', 'value' => 4],
            ['name' => '< 500.000 (Sangat Murah)', 'value' => 5],
        ];
        foreach ($subsC1 as $s) {
            DB::table('sub_criterias')->insert(['criteria_id' => $c1, 'name' => $s['name'], 'value' => $s['value']]);
        }

        // --- C2: Jarak (Cost) ---
        $subsC2 = [
            ['name' => '>= 2500 m', 'value' => 1],
            ['name' => '> 2000 m dan <= 2500 m', 'value' => 2],
            ['name' => '> 1500 m dan <= 2000 m', 'value' => 3],
            ['name' => '> 1000 m dan <= 1500 m', 'value' => 4],
            ['name' => '<= 1000 m (Sangat Dekat)', 'value' => 5],
        ];
        foreach ($subsC2 as $s) {
            DB::table('sub_criterias')->insert(['criteria_id' => $c2, 'name' => $s['name'], 'value' => $s['value']]);
        }

        // --- C3: Fasilitas (Benefit) ---
        $subsC3 = [
            ['name' => 'Kamar Mandi Dalam, 1 Kamar', 'value' => 1],
            ['name' => 'Kamar Mandi Dalam, 1 Kamar, Kasur', 'value' => 2],
            ['name' => 'KM Dalam, Kasur, Dapur, Parkir, 1 Kamar', 'value' => 3],
            ['name' => 'Wifi, KM Dalam, Kasur, Dapur, Parkir', 'value' => 4],
            ['name' => 'Lengkap (Wifi, AC, Lemari, KM Dalam, dll)', 'value' => 5],
        ];
        foreach ($subsC3 as $s) {
            DB::table('sub_criterias')->insert(['criteria_id' => $c3, 'name' => $s['name'], 'value' => $s['value']]);
        }

        // --- C4: Luas (Benefit) ---
        $subsC4 = [
            ['name' => '3x3 m²', 'value' => 1],
            ['name' => '3x5 m²', 'value' => 2],
            ['name' => '4x6 m²', 'value' => 3],
            ['name' => '4x7 m²', 'value' => 4],
            ['name' => '5x8 m²', 'value' => 5],
        ];
        foreach ($subsC4 as $s) {
            DB::table('sub_criterias')->insert(['criteria_id' => $c4, 'name' => $s['name'], 'value' => $s['value']]);
        }
    }
}