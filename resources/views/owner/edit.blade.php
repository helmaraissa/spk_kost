<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kos - Owner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- COPY STYLE DARI DASHBOARD OWNER --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #1f2937; display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; background-color: #ffffff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
        .sidebar-header h2 { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .sidebar-menu { padding: 20px 10px; flex-grow: 1; }
        .menu-item { display: block; padding: 12px 15px; color: #4b5563; text-decoration: none; border-radius: 6px; margin-bottom: 5px; font-weight: 500; transition: all 0.2s; }
        .menu-item:hover, .menu-item.active { background-color: #eff6ff; color: #2563eb; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #e5e7eb; }
        .btn-logout { background-color: #ef4444; color: white; width: 100%; padding: 10px; border: none; border-radius: 6px; cursor: pointer; }

        /* CONTENT */
        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        
        /* FORM CARD */
        .card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 30px; border: 1px solid #e5e7eb; max-width: 800px; margin: 0 auto; }
        .card-header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f3f4f6; }
        .card-title { font-size: 1.25rem; font-weight: 700; color: #111827; }

        /* FORM ELEMENTS */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.95rem; transition: border 0.2s; }
        .form-control:focus { outline: none; border-color: #2563eb; ring: 2px solid rgba(37,99,235,0.2); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        
        .section-title { margin-top: 30px; margin-bottom: 15px; font-size: 1.1rem; font-weight: 700; color: #2563eb; border-left: 4px solid #2563eb; padding-left: 10px; }
        .helper-text { font-size: 0.8rem; color: #6b7280; margin-top: 5px; }

        /* BUTTONS */
        .btn-group { display: flex; gap: 10px; margin-top: 30px; }
        .btn { padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; border: none; font-size: 0.95rem; }
        .btn-primary { background-color: #2563eb; color: white; flex: 1; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-secondary { background-color: #f3f4f6; color: #4b5563; text-decoration: none; display: flex; align-items: center; justify-content: center; }
        .btn-secondary:hover { background-color: #e5e7eb; }

        /* Current Image */
        .current-img { width: 100px; height: 100px; object-fit: cover; border-radius: 6px; margin-top: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>🏠 OwnerPanel</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('owner.dashboard') }}" class="menu-item active">
                📊 <span class="menu-text">Kembali ke Dashboard</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">✏️ Edit Data Kosan</h1>
            </div>

            @if ($errors->any())
                <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <ul style="margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kos.update', $kost->kost_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="section-title">Informasi Utama</div>

                <div class="form-group">
                    <label class="form-label">Nama Kos</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $kost->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" required>{{ old('address', $kost->address) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" class="form-control" required>{{ old('description', $kost->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Detail Fasilitas (Teks)</label>
                    <textarea name="facility_details" class="form-control" placeholder="Contoh: Wifi, AC, Kamar Mandi Dalam...">{{ old('facility_details', $kost->facility_details) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Kosan</label>
                    @if($kost->image)
                        <img src="{{ asset('storage/' . $kost->image) }}" class="current-img">
                        <div class="helper-text">Biarkan kosong jika tidak ingin mengganti foto.</div>
                    @endif
                    <input type="file" name="image" class="form-control" style="margin-top: 5px;">
                </div>

                <div class="section-title">Data Penilaian (SPK)</div>

                <div class="form-group">
                    <label class="form-label">Harga Per Bulan (Rp)</label>
                    <input type="number" name="price_per_month" class="form-control" value="{{ old('price_per_month', $kost->price_per_month) }}" required>
                    <div class="helper-text">*Nilai Kriteria Harga akan dihitung otomatis dari sini.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jarak ke Kampus (Meter)</label>
                    <input type="number" name="distance" class="form-control" placeholder="Masukkan jarak dalam meter" required>
                    <div class="helper-text">*Wajib diisi ulang agar skor Jarak terupdate akurat.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Kamar</label>
                    <input type="number" name="total_rooms" class="form-control" value="{{ old('total_rooms', $kost->total_rooms) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nilai Fasilitas (Skor)</label>
                    <select name="facility_score" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @if($c3 && $c3->subCriterias)
                            @foreach($c3->subCriterias as $sub)
                                <option value="{{ $sub->value }}" {{ $old_facility == $sub->value ? 'selected' : '' }}>
                                    {{ $sub->name }} (Poin: {{ $sub->value }})
                                </option>
                            @endforeach
                        @else
                            <option value="1">Data Sub Kriteria Tidak Ditemukan</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nilai Luas Kamar (Skor)</label>
                    <select name="area_score" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @if($c4 && $c4->subCriterias)
                            @foreach($c4->subCriterias as $sub)
                                <option value="{{ $sub->value }}" {{ $old_area == $sub->value ? 'selected' : '' }}>
                                    {{ $sub->name }} (Poin: {{ $sub->value }})
                                </option>
                            @endforeach
                        @else
                            <option value="1">Data Sub Kriteria Tidak Ditemukan</option>
                        @endif
                    </select>
                </div>

                <div class="btn-group">
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </main>

</body>
</html>