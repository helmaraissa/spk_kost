<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    use HasFactory;

    protected $table = 'kosts';
    protected $primaryKey = 'kost_id';

    protected $fillable = [
        'owner_id', 'name', 'address', 'price_per_month', 'status', 'description', 'image'
    ];

    // Relasi ke Pemilik (User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relasi ke Nilai Evaluasi (Untuk SPK)
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'kost_id');
    }
}