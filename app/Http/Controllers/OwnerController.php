<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Evaluation;
use App\Models\Criteria; // Pastikan Model Criteria di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerController extends Controller
{
    // 1. DASHBOARD
    public function index()
    {
        $owner = Auth::user()->owner;
        if (!$owner) return redirect()->back()->with('error', 'Data Owner tidak ditemukan.');

        $myKosts = Kost::where('owner_id', $owner->owner_id)->orderBy('created_at', 'desc')->get();
        $allApprovedKosts = Kost::with('owner')->where('status', 'approved')->orderBy('created_at', 'desc')->get();

        return view('owner.dashboard', compact('myKosts', 'allApprovedKosts'));
    }

    // 2. CREATE FORM
// Jangan lupa import Model Criteria di bagian atas file controller
    // use App\Models\Criteria; 

    public function create()
    {
        // 1. Ambil Kriteria Fasilitas (C3)
        // Kita cari berdasarkan nama agar aman kalau ID berubah-ubah
        $c3 = Criteria::with('subCriterias')
                      ->where('criteria_name', 'LIKE', '%Fasilitas%')
                      ->first();

        // 2. Ambil Kriteria Luas (C4)
        $c4 = Criteria::with('subCriterias')
                      ->where('criteria_name', 'LIKE', '%Luas%')
                      ->first();

        // 3. Cek Error (Opsional, untuk debugging jika data kosong)
        if(!$c3 || !$c4) {
            return redirect()->back()->with('error', 'Data Kriteria (Fasilitas/Luas) belum disetting Admin. Silakan hubungi Admin.');
        }

        // 4. Kirim ke View
        return view('owner.create', compact('c3', 'c4'));
    }

    // 3. STORE (SIMPAN BARU)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price_per_month' => 'required|numeric',
            'address' => 'required',
            'total_rooms' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('kos-images', 'public') : null;

        $kost = Kost::create([
            'owner_id' => Auth::user()->owner->owner_id,
            'name' => $request->name,
            'price_per_month' => $request->price_per_month,
            'address' => $request->address,
            'description' => $request->description,
            'facility_details' => $request->facility_details,
            'total_rooms' => $request->total_rooms,
            'distance' => $request->distance,
            'image' => $imagePath,
            'status' => 'pending'
        ]);

        // --- SIMPAN SPK (DINAMIS CARI ID) ---
        $this->saveDynamicEvaluation($kost->kost_id, 'Harga', $this->calculateHargaScore($request->price_per_month));
        $this->saveDynamicEvaluation($kost->kost_id, 'Jarak', $this->calculateJarakScore($request->distance));
        $this->saveDynamicEvaluation($kost->kost_id, 'Fasilitas', $request->facility_score ?? 3);
        $this->saveDynamicEvaluation($kost->kost_id, 'Luas', $request->area_score ?? 3);

        return redirect()->route('owner.dashboard')->with('success', 'Kosan berhasil diajukan!');
    }

    // 4. EDIT FORM
    public function edit($id)
    {
        $kost = Kost::with('evaluations.criteria')->findOrFail($id);

        if ($kost->owner_id != Auth::user()->owner->owner_id) {
            abort(403, 'Akses ditolak.');
        }

        // 1. Ambil Data Kriteria & Sub-Kriteria untuk Dropdown
        $c3 = Criteria::with('subCriterias')->where('criteria_name', 'LIKE', '%Fasilitas%')->first();
        $c4 = Criteria::with('subCriterias')->where('criteria_name', 'LIKE', '%Luas%')->first();

        // 2. Cari Nilai Lama (Selected Value)
        // Kita cari aman pakai keyword nama, bukan ID
        $evalFasilitas = $kost->evaluations->first(function($val) {
            return stripos($val->criteria->criteria_name, 'Fasilitas') !== false;
        });
        
        $evalLuas = $kost->evaluations->first(function($val) {
            return stripos($val->criteria->criteria_name, 'Luas') !== false;
        });

        $old_facility = $evalFasilitas ? $evalFasilitas->value : 1;
        $old_area = $evalLuas ? $evalLuas->value : 1;

        // 3. Kirim Semua Data ke View
        return view('owner.edit', compact('kost', 'old_facility', 'old_area', 'c3', 'c4'));
    }

    // 5. UPDATE
    public function update(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);

        if ($kost->owner_id != Auth::user()->owner->owner_id) abort(403);

        $dataToUpdate = [
            'name' => $request->name,
            'price_per_month' => $request->price_per_month,
            'address' => $request->address,
            'description' => $request->description,
            'facility_details' => $request->facility_details,
            'total_rooms' => $request->total_rooms,
            'distance' => $request->distance,
        ];

        if ($request->hasFile('image')) {
            if ($kost->image) Storage::disk('public')->delete($kost->image);
            $dataToUpdate['image'] = $request->file('image')->store('kos-images', 'public');
        }

        $kost->update($dataToUpdate);

        // --- UPDATE SPK (DINAMIS CARI ID) ---
        
        // 1. Harga (Hitung Ulang)
        $this->saveDynamicEvaluation($id, 'Harga', $this->calculateHargaScore($request->price_per_month));

        // 2. Jarak (Hitung Ulang)
        $this->saveDynamicEvaluation($id, 'Jarak', $this->calculateJarakScore($request->distance));

        // 3. Fasilitas
        if($request->has('facility_score')) {
            $this->saveDynamicEvaluation($id, 'Fasilitas', $request->facility_score);
        }

        // 4. Luas
        if($request->has('area_score')) {
            $this->saveDynamicEvaluation($id, 'Luas', $request->area_score);
        }

        return redirect()->route('owner.dashboard')->with('success', 'Data kosan berhasil diperbarui.');
    }

    // 6. DELETE
    public function destroy($id)
    {
        $kost = Kost::findOrFail($id);
        if ($kost->owner_id != Auth::user()->owner->owner_id) abort(403);
        if ($kost->image) Storage::disk('public')->delete($kost->image);
        $kost->delete();
        return redirect()->route('owner.dashboard')->with('success', 'Kosan dihapus.');
    }

    // --- HELPER FUNCTIONS ---

    // Fungsi Baru: Mencari ID Kriteria berdasarkan Nama, baru simpan
    private function saveDynamicEvaluation($kostId, $criteriaNameKeyword, $value) {
        // Cari kriteria yang namanya mengandung keyword (misal "Harga")
        $criteria = Criteria::where('criteria_name', 'LIKE', "%{$criteriaNameKeyword}%")->first();

        // Jika kriteria ditemukan di database, baru simpan nilainya
        if ($criteria) {
            Evaluation::updateOrCreate(
                ['kost_id' => $kostId, 'criteria_id' => $criteria->criteria_id],
                ['value' => $value]
            );
        }
    }

    // --- UPDATE FUNGSI INI DI BAGIAN BAWAH OWNER CONTROLLER ---
    private function calculateHargaScore($price) {
        if(!$price) return 1;
        // Logic sesuai Seeder Baru
        if($price < 500000) return 5;          // Sangat Murah
        if($price <= 800000) return 4;         // Murah
        if($price <= 1200000) return 3;        // Sedang
        if($price <= 1500000) return 2;        // Mahal
        return 1;                              // Sangat Mahal (> 1.5jt)
    }

    private function calculateJarakScore($dist) {
        if(!$dist) return 1;
        // Logic sesuai Seeder Baru
        if($dist < 500) return 5;              // Sangat Dekat
        if($dist <= 1000) return 4;            // Dekat
        if($dist <= 2000) return 3;            // Sedang
        if($dist <= 3000) return 2;            // Jauh
        return 1;                              // Sangat Jauh (> 3km)
    }
}