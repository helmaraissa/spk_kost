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
     * Sekaligus melakukan perhitungan SPK SAW
     */
    public function index()
    {
        // 1. Ambil Kosan yang statusnya sudah Approved
        // Kita juga load relasi 'evaluations' agar query efisien
        $kosts = Kost::with('evaluations', 'owner')
                     ->where('status', 'approved')
                     ->get();

        // Jika tidak ada data, kirim array kosong
        if ($kosts->isEmpty()) {
            // Perhatikan: view yang dipanggil adalah 'welcome', BUKAN 'home'
            return view('welcome', ['ranks' => []]);
        }

        // 2. Ambil Semua Kriteria
        $criterias = Criteria::all();

        // 3. Cari Nilai Max/Min untuk Normalisasi
        // Min untuk Cost (Harga, Jarak), Max untuk Benefit (Fasilitas, Luas)
        $normalizationFactors = [];
        foreach ($criterias as $c) {
            // Ambil semua nilai evaluasi untuk kriteria ini dari kosan yang approved
            $values = Evaluation::whereIn('kost_id', $kosts->pluck('kost_id'))
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
                // Cari nilai evaluasi kosan ini untuk kriteria ini
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
                    $normalisasi = $nilaiAwal / $factor;
                }

                // Rumus Perankingan: Normalisasi * Bobot
                $totalScore += $normalisasi * $c->weight;
            }

            // Simpan hasil ke array
            $ranks[] = [
                'kost' => $kost,
                'score' => $totalScore
            ];
        }

        // 5. Urutkan dari Score Tertinggi ke Terendah
        usort($ranks, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 6. Tampilkan ke view 'welcome'
        return view('welcome', compact('ranks'));
    }

    // Fitur Search (Opsional)
    public function search(Request $request) 
    {
        return redirect()->route('home');
    }
}