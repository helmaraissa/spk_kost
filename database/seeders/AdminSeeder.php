<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Criteria;
use App\Models\SubCriteria;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'username' => 'admin_spk',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
        
        // 2. Insert Kriteria (C1-C4)
        // C1: Harga (Cost, 0.35)
        $c1 = Criteria::create(['code' => 'C1', 'criteria_name' => 'Harga', 'attribute' => 'cost', 'weight' => 0.35]);
        // C2: Jarak (Cost, 0.25)
        $c2 = Criteria::create(['code' => 'C2', 'criteria_name' => 'Jarak', 'attribute' => 'cost', 'weight' => 0.25]);
        // C3: Fasilitas (Benefit, 0.2)
        $c3 = Criteria::create(['code' => 'C3', 'criteria_name' => 'Fasilitas', 'attribute' => 'benefit', 'weight' => 0.20]);
        // C4: Luas (Benefit, 0.2)
        $c4 = Criteria::create(['code' => 'C4', 'criteria_name' => 'Luas', 'attribute' => 'benefit', 'weight' => 0.20]);

        // 3. Insert Sub-Kriteria (Contoh untuk C1 Harga)
        SubCriteria::create(['criteria_id' => $c1->criteria_id, 'name' => '>= 1.000.000', 'value' => 1]);
        SubCriteria::create(['criteria_id' => $c1->criteria_id, 'name' => '>= 850.000 dan < 1.000.000', 'value' => 2]);
        SubCriteria::create(['criteria_id' => $c1->criteria_id, 'name' => '>= 650.000 dan < 850.000', 'value' => 3]);
        SubCriteria::create(['criteria_id' => $c1->criteria_id, 'name' => '>= 500.000 dan < 650.000', 'value' => 4]);
        SubCriteria::create(['criteria_id' => $c1->criteria_id, 'name' => '< 500.000', 'value' => 5]);
        
        // Silakan tambahkan SubCriteria untuk C2, C3, dan C4 dengan pola yang sama sesuai Excel.
    }
}
