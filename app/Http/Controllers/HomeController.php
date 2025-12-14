<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman depan (Landing Page)
     * Sekaligus melakukan perhitungan SPK SAW dengan Filter
     */
    public function index(Request $request)
    {
        // 1. Mulai Query Kosan
        $query = Kost::with('evaluations', 'owner')
                     ->where('status', 'approved');

        // --- LOGIKA FILTER (Baru) ---
        
        // Filter Harga (Maksimal)
        if ($request->filled('filter_harga')) {
            $query->where('price_per_month', '<=', $request->filter_harga);
        }

        // Filter Jarak (Maksimal)
        // Asumsi: Kamu punya kolom 'distance' di tabel 'kosts' (dalam meter)
        if ($request->filled('filter_jarak')) {
            $query->where('distance', '<=', $request->filter_jarak);
        }

        // Filter Fasilitas
        // Asumsi: Kamu punya kolom 'facilities' (text/string) yang berisi daftar fasilitas
        if ($request->filled('filter_fasilitas')) {
            $query->where('facilities', 'like', '%' . $request->filter_fasilitas . '%');
        }

        // Eksekusi Query
        $kosts = $query->get();

        // Jika hasil filter kosong, kembalikan view kosong
        if ($kosts->isEmpty()) {
            return view('welcome', ['ranks' => []]);
        }

        // --- MULAI PERHITUNGAN SAW ---

        // 2. Ambil Semua Kriteria
        $criterias = Criteria::all();

        // 3. Cari Nilai Max/Min untuk Normalisasi
        // Penting: Kita hitung Max/Min hanya dari data yang sudah difilter ($kosts)
        // agar perbandingannya relevan dengan hasil pencarian user.
        $normalizationFactors = [];
        
        // Ambil ID kosan yang lolos filter
        $filteredKostIds = $kosts->pluck('kost_id');

        foreach ($criterias as $c) {
            // Ambil nilai evaluasi hanya dari kosan yang lolos filter
            $values = Evaluation::whereIn('kost_id', $filteredKostIds)
                                ->where('criteria_id', $c->criteria_id)
                                ->pluck('value')
                                ->toArray();
            
            if (empty($values)) {
                $normalizationFactors[$c->criteria_id] = 0; 
                continue;
            }

            if ($c->attribute == 'cost') {
                $normalizationFactors[$c->criteria_id] = min($values);
            } else {
                $normalizationFactors[$c->criteria_id] = max($values);
            }
        }

        // 4. Proses Perankingan (SAW)
        $ranks = [];
        foreach ($kosts as $kost) {
            $totalScore = 0;

            foreach ($criterias as $c) {
                // Cari nilai evaluasi kosan ini
                $eval = $kost->evaluations->where('criteria_id', $c->criteria_id)->first();
                $nilaiAwal = $eval ? $eval->value : 0;
                $factor = $normalizationFactors[$c->criteria_id] ?? 0;

                // Rumus Normalisasi
                if ($factor == 0) {
                     $normalisasi = 0;
                } elseif ($c->attribute == 'cost') {
                    // Cost: Min / Nilai
                    $normalisasi = ($nilaiAwal == 0) ? 0 : $factor / $nilaiAwal;
                } else {
                    // Benefit: Nilai / Max
                    $normalisasi = ($factor == 0) ? 0 : $nilaiAwal / $factor;
                }

                // Rumus Akhir: Normalisasi * Bobot
                $totalScore += $normalisasi * $c->weight;
            }

            // Simpan hasil
            $ranks[] = [
                'kost' => $kost,
                'score' => $totalScore
            ];
        }

        // 5. Urutkan dari Score Tertinggi ke Terendah
        usort($ranks, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 6. Tampilkan ke view
        // Kita juga kirim data request agar form filter tetap terisi setelah submit (opsional)
        return view('welcome', compact('ranks'));
    }
}