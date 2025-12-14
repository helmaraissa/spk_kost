<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <--- WAJIB ADA: Untuk cek file gambar

class Kost extends Model
{
    use HasFactory;

    protected $table = 'kosts';
    protected $primaryKey = 'kost_id';

    // Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'owner_id', 
        'name', 
        'address', 
        'price_per_month', 
        'status', 
        'description', 
        'image',       // Nama kolom gambar di database kamu
        'distance',    // PENTING: Untuk filter jarak
        'facilities',  // PENTING: Untuk filter fasilitas
        'room_size'    // PENTING: Untuk info luas kamar di UI
    ];

    /**
     * Accessor Cerdas untuk URL Gambar
     * Cara panggil di Blade: $kost->gambar_url
     */
    public function getGambarUrlAttribute()
    {
        // 1. Cek apakah kolom 'image' tidak kosong DAN file fisiknya ada di storage public
        if (!empty($this->image) && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // 2. Jika file tidak ketemu (rusak/terhapus), kembalikan gambar default ini
        return 'https://images.unsplash.com/photo-1522771753037-633361652bff?auto=format&fit=crop&w=800&q=80';
    }

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