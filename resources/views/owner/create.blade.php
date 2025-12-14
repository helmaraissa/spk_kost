<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kos Baru - Owner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- STYLE SAMA SEPERTI EDIT --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #1f2937; display: flex; min-height: 100vh; }
        
        .sidebar { width: 250px; background-color: #ffffff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
        .sidebar-header h2 { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .sidebar-menu { padding: 20px 10px; flex-grow: 1; }
        .menu-item { display: block; padding: 12px 15px; color: #4b5563; text-decoration: none; border-radius: 6px; margin-bottom: 5px; font-weight: 500; transition: all 0.2s; }
        .menu-item:hover, .menu-item.active { background-color: #eff6ff; color: #2563eb; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #e5e7eb; }
        .btn-logout { background-color: #ef4444; color: white; width: 100%; padding: 10px; border: none; border-radius: 6px; cursor: pointer; }

        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        
        .card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 30px; border: 1px solid #e5e7eb; max-width: 800px; margin: 0 auto; }
        .card-header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f3f4f6; }
        .card-title { font-size: 1.25rem; font-weight: 700; color: #111827; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.95rem; transition: border 0.2s; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .helper-text { font-size: 0.8rem; color: #6b7280; margin-top: 5px; }

        .btn-group { display: flex; gap: 10px; margin-top: 30px; }
        .btn { padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; border: none; font-size: 0.95rem; }
        .btn-primary { background-color: #2563eb; color: white; flex: 1; }
        .btn-secondary { background-color: #f3f4f6; color: #4b5563; text-decoration: none; display: flex; align-items: center; justify-content: center; }
        
        .section-divider { margin-top: 40px; margin-bottom: 20px; border-top: 2px dashed #e5e7eb; padding-top: 20px; font-size: 1.1rem; color: #2563eb; font-weight: 700; }
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
                <h1 class="card-title">➕ Tambah Kos Baru</h1>
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

            <form action="{{ route('kos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Kos</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Kost Melati Indah" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Harga Per Bulan (Rp)</label>
                    <input type="number" name="price_per_month" class="form-control" placeholder="Contoh: 800000" required value="{{ old('price_per_month') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" placeholder="Alamat lengkap kosan..." required>{{ old('address') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" class="form-control" placeholder="Deskripsi tentang lingkungan, keamanan, dll." required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Detail Fasilitas (Teks)</label>
                    <textarea name="facility_details" class="form-control" placeholder="Sebutkan fasilitas: Wifi, Kasur, Lemari, dll.">{{ old('facility_details') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Kamar Tersedia</label>
                    <input type="number" name="total_rooms" class="form-control" placeholder="Contoh: 10" required value="{{ old('total_rooms') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Utama</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <h4 class="section-divider">📊 Data Penilaian (Sistem SAW)</h4>
                <p style="font-size: 0.9em; color: #6b7280; margin-bottom: 20px;">Data di bawah ini akan digunakan sistem untuk menghitung peringkat rekomendasi.</p>

                <div class="form-group">
                    <label class="form-label">📏 Jarak ke Kampus (Meter)</label>
                    <input type="number" name="distance" class="form-control" placeholder="Contoh: 1200" required value="{{ old('distance') }}">
                    <div class="helper-text">Masukkan angka saja dalam satuan meter.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">⭐ Kualitas Fasilitas (Pilih Paling Sesuai)</label>
                    <select name="facility_score" class="form-control" required>
                        <option value="">-- Pilih Kriteria Fasilitas --</option>
                        
                        {{-- PERBAIKAN: Gunakan $c3->subCriterias (CamelCase) agar aman --}}
                        @if($c3 && $c3->subCriterias)
                            @foreach($c3->subCriterias as $sub)
                                <option value="{{ $sub->value }}" {{ old('facility_score') == $sub->value ? 'selected' : '' }}>
                                    {{ $sub->name }} (Poin: {{ $sub->value }})
                                </option>
                            @endforeach
                        @else
                            <option value="1">Data Sub Kriteria Tidak Ditemukan</option>
                        @endif

                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">📐 Luas Kamar (Pilih Paling Sesuai)</label>
                    <select name="area_score" class="form-control" required>
                        <option value="">-- Pilih Luas Kamar --</option>
                        
                        {{-- PERBAIKAN: Gunakan $c4->subCriterias (CamelCase) agar aman --}}
                        @if($c4 && $c4->subCriterias)
                            @foreach($c4->subCriterias as $sub)
                                <option value="{{ $sub->value }}" {{ old('area_score') == $sub->value ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">Simpan & Ajukan</button>
                </div>

            </form>
        </div>
    </main>

</body>
</html>