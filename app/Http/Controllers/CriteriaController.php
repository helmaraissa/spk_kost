<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::orderBy('code', 'asc')->get();
        
        // Hitung total bobot saat ini
        $totalWeight = $criterias->sum('weight');
        
        // Kirim data ke view baru
        return view('admin.kriteria.index', compact('criterias', 'totalWeight'));
    }

    public function store(Request $request)
    {
        // 1. Cek Total Bobot Sekarang
        $currentTotal = Criteria::sum('weight');
        $newWeight = $request->weight;

        // 2. Validasi Batas Bobot
        if (($currentTotal + $newWeight) > 1) {
            return back()->withErrors(['weight' => 'Gagal! Total bobot akan melebihi 100% (1.0). Sisa kuota bobot: ' . (1 - $currentTotal)]);
        }

        // --- LOGIKA BARU (SMART CODE GENERATOR) ---
        // Mencari angka terkecil yang belum dipakai
        // Misal ada C2, C3. Maka loop akan cek:
        // C1 ada? Tidak -> Pakai C1.
        
        $number = 1;
        while (Criteria::where('code', 'C' . $number)->exists()) {
            $number++;
        }
        
        $code = 'C' . $number; 
        // ------------------------------------------

        Criteria::create([
            'code' => $code, // Pakai kode hasil pencarian di atas
            'criteria_name' => $request->criteria_name,
            'weight' => $request->weight,
            'attribute' => $request->attribute,
        ]);

        return back()->with('success', 'Kriteria berhasil ditambahkan sebagai ' . $code);
    }

    public function update(Request $request, $id)
    {
        $criteria = Criteria::findOrFail($id);

        // 1. Cek Total Bobot (Kecuali punya dia sendiri yang sedang diedit)
        $otherTotal = Criteria::where('criteria_id', '!=', $id)->sum('weight');
        $newTotal = $otherTotal + $request->weight;

        // 2. Validasi Batas 1
        if ($newTotal > 1) {
             return back()->withErrors(['weight' => 'Gagal update! Total bobot melebihi 100%. Maksimal yang bisa diinput: ' . (1 - $otherTotal)]);
        }

        $criteria->update([
            'criteria_name' => $request->criteria_name,
            'weight' => $request->weight,
            'attribute' => $request->attribute,
        ]);

        return back()->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();

        return back()->with('success', 'Kriteria berhasil dihapus.');
    }
}