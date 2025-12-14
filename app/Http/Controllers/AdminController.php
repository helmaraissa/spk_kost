<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pastikan import ini ada untuk fitur delete gambar

class AdminController extends Controller
{
    public function index()
    {
        // 1. STATS
        $stats = [
            'total' => Kost::count(),
            'pending' => Kost::where('status', 'pending')->count(),
            'approved' => Kost::where('status', 'approved')->count(),
            'rejected' => Kost::where('status', 'rejected')->count(),
        ];

        // 2. DATA KOSAN
        $allKosts = Kost::with('owner')->orderBy('created_at', 'desc')->get();

        // 3. LOGIKA SPK (SAW)
        $criterias = Criteria::orderBy('code', 'asc')->get(); // Ambil data kriteria
        $approvedKosts = Kost::with('evaluations', 'owner')->where('status', 'approved')->get();
        
        $ranks = [];
        $topKostAnalysis = null; 

        if ($approvedKosts->isNotEmpty() && $criterias->isNotEmpty()) {
            
            // A. Cari Nilai Max/Min untuk Normalisasi
            $normalizationFactors = [];
            foreach ($criterias as $c) {
                $values = Evaluation::whereIn('kost_id', $approvedKosts->pluck('kost_id'))
                                    ->where('criteria_id', $c->criteria_id)
                                    ->pluck('value')->toArray();
                
                if (empty($values)) {
                    $normalizationFactors[$c->criteria_id] = 0;
                    continue;
                }
                $normalizationFactors[$c->criteria_id] = ($c->attribute == 'cost') ? min($values) : max($values);
            }

            // B. Hitung Per Baris (Kosan)
            foreach ($approvedKosts as $kost) {
                $totalScore = 0;
                $details = []; 

                foreach ($criterias as $c) {
                    $eval = $kost->evaluations->where('criteria_id', $c->criteria_id)->first();
                    $val = $eval ? $eval->value : 0;
                    $factor = $normalizationFactors[$c->criteria_id] ?? 0;

                    // Rumus Normalisasi
                    if ($factor == 0) $norm = 0;
                    elseif ($c->attribute == 'cost') $norm = ($val == 0) ? 0 : $factor / $val;
                    else $norm = $val / $factor;

                    $totalScore += ($norm * $c->weight);

                    // Simpan detail (Nilai Asli & Nilai Normalisasi)
                    $details[$c->criteria_id] = [
                        'val' => $val,   // Nilai Asli (Skala 1-5)
                        'norm' => $norm  // Nilai Normalisasi (0-1)
                    ];
                }

                $ranks[] = [
                    'kost' => $kost,
                    'score' => $totalScore,
                    'details' => $details
                ];
            }

            // C. Urutkan Ranking
            usort($ranks, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            // D. Analisis Juara 1
            if (!empty($ranks)) {
                $topKostAnalysis = $this->generateAnalysisText($ranks[0], $criterias);
            }
        }

        return view('admin.dashboard', compact('allKosts', 'ranks', 'topKostAnalysis', 'stats', 'criterias'));
    }

    private function generateAnalysisText($winnerData, $criterias)
    {
        $kost = $winnerData['kost'];
        $score = number_format($winnerData['score'], 3);
        
        $advantages = [];
        // Loop detail berdasarkan ID kriteria
        foreach ($criterias as $c) {
            if (isset($winnerData['details'][$c->criteria_id]) && $winnerData['details'][$c->criteria_id]['norm'] >= 0.8) {
                $advantages[] = $c->criteria_name;
            }
        }
        $advString = implode(", ", $advantages);
        
        return "Kos <strong>{$kost->name}</strong> adalah rekomendasi terbaik (Skor: {$score}) dengan keunggulan di aspek: <strong>{$advString}</strong>.";
    }

    // Fungsi Verifikasi (Approve/Reject)
    public function verifikasi(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);
        $kost->status = $request->status;
        $kost->save();
        return back()->with('success', 'Status kosan diperbarui.');
    }

    // Fungsi Hapus Kosan (Delete)
    public function destroy($id)
    {
        $kost = Kost::findOrFail($id);

        if ($kost->image) {
            Storage::disk('public')->delete($kost->image);
        }

        $kost->delete();

        return back()->with('success', 'Data kosan berhasil dihapus permanen.');
    }
}