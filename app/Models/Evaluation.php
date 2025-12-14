<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';
    protected $primaryKey = 'evaluation_id';

    protected $fillable = [
        'kost_id',
        'criteria_id',
        'value' // Nilai angka (misal: 1000000, 50, dst)
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
}