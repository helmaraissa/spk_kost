<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criterias';
    protected $primaryKey = 'criteria_id'; // Sesuai DB

    protected $fillable = [
        'code',
        'criteria_name',
        'attribute',
        'weight'
    ];

    // --- INI BAGIAN YANG HILANG/KURANG ---
    // Relasi ke Sub Kriteria (Pilihan Dropdown: Sangat Baik, Kurang, dll)
    public function subCriterias()
    {
        // Pastikan model SubCriteria sudah ada juga
        return $this->hasMany(SubCriteria::class, 'criteria_id', 'criteria_id');
    }

    // Relasi ke Evaluasi
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'criteria_id', 'criteria_id');
    }
}