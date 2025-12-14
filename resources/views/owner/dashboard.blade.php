<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - Kelola Kos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- RESET & BASIC STYLE --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #1f2937; display: flex; min-height: 100vh; }
        
        /* --- SIDEBAR --- */
        .sidebar { width: 250px; background-color: #ffffff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; left: 0; top: 0; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
        .sidebar-header h2 { font-size: 1.25rem; color: #111827; font-weight: 700; }
        .sidebar-menu { padding: 20px 10px; flex-grow: 1; }
        .menu-item { display: block; padding: 12px 15px; color: #4b5563; text-decoration: none; border-radius: 6px; margin-bottom: 5px; font-weight: 500; transition: all 0.2s; }
        .menu-item:hover, .menu-item.active { background-color: #eff6ff; color: #2563eb; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #e5e7eb; }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: 250px; flex-grow: 1; padding: 30px; width: calc(100% - 250px); }

        /* --- HEADER SECTION --- */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title h1 { font-size: 1.5rem; font-weight: 700; color: #111827; }
        .page-title p { color: #6b7280; font-size: 0.875rem; margin-top: 5px; }

        /* --- BUTTONS --- */
        .btn { display: inline-block; padding: 10px 20px; border-radius: 6px; font-weight: 500; text-decoration: none; cursor: pointer; border: none; font-size: 0.875rem; transition: background 0.2s; }
        .btn-primary { background-color: #2563eb; color: white; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-danger { background-color: #ef4444; color: white; width: 100%; text-align: center; }
        .btn-danger:hover { background-color: #dc2626; }
        .btn-sm { padding: 6px 12px; font-size: 0.75rem; }

        /* --- CARDS & SECTIONS --- */
        .card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px; border: 1px solid #e5e7eb; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f3f4f6; }
        .card-title { font-size: 1.1rem; font-weight: 600; color: #111827; }

        /* --- TABLES --- */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #f9fafb; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 12px 16px; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
        td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; color: #374151; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        
        /* --- BADGES & ICONS --- */
        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-approved { background-color: #d1fae5; color: #065f46; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        
        .spec-item { display: flex; align-items: center; gap: 5px; font-size: 0.8rem; color: #6b7280; margin-bottom: 3px; }
        
        /* --- IMAGE THUMBNAIL (FIXED) --- */
        .img-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; background-color: #f3f4f6; }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar-header h2, .menu-text { display: none; }
            .main-content { margin-left: 70px; width: calc(100% - 70px); }
            .btn-danger { font-size: 0.7rem; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>🏠 OwnerPanel</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('owner.dashboard') }}" class="menu-item active">
                📊 <span class="menu-text">Dashboard</span>
            </a>
            <a href="{{ route('kos.create') }}" class="menu-item">
                ➕ <span class="menu-text">Tambah Kos Baru</span>
            </a>
            <a href="#" class="menu-item">
                👤 <span class="menu-text">Profil Saya</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        
        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="top-bar">
            <div class="page-title">
                <h1>Selamat Datang, Pemilik!</h1>
                <p>Kelola properti kos dan pantau status pendaftaran Anda di sini.</p>
            </div>
            <a href="{{ route('kos.create') }}" class="btn btn-primary">+ Daftarkan Kos Baru</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📂 Status Pengajuan Pendaftaran Saya</h3>
            </div>
            
            <div class="table-container">
                @php
                    // Filter: Tampilkan yang statusnya BUKAN approved (Pending/Rejected)
                    $myPendingKosts = isset($myKosts) ? $myKosts->filter(function($k) { return $k->status !== 'approved'; }) : collect([]);
                @endphp

                @if($myPendingKosts->isEmpty())
                    <p style="text-align: center; color: #6b7280; padding: 20px;">
                        Tidak ada pengajuan yang sedang diproses.
                    </p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama Kos</th>
                                <th>Spesifikasi Dasar</th>
                                <th>Tanggal Ajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myPendingKosts as $kos)
                            <tr>
                                <td>
                                    @if($kos->image)
                                        <a href="{{ asset('storage/' . $kos->image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $kos->image) }}" class="img-thumb" onerror="this.src='https://placehold.co/70?text=No+Img'">
                                        </a>
                                    @else
                                        <div class="img-thumb" style="display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No Img</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $kos->name }}</strong><br>
                                    <small style="color:#2563eb; font-weight:bold;">Rp {{ number_format($kos->price_per_month) }}/bln</small>
                                </td>
                                <td>
                                    <div class="spec-item">🛏️ {{ $kos->total_rooms ?? '-' }} Kamar</div>
                                    <div class="spec-item">📍 {{ $kos->distance ?? '-' }} Meter (Kampus)</div>
                                </td>
                                <td>{{ $kos->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($kos->status == 'pending')
                                        <span class="badge badge-pending">Pending</span>
                                    @else
                                        <span class="badge badge-rejected">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('kos.edit', $kos->kost_id) }}" class="btn btn-primary btn-sm" style="margin-bottom:5px;">Edit</a>
                                    <form action="{{ route('kos.destroy', $kos->kost_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus pengajuan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="width: auto; padding: 6px 10px;">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">✅ Daftar Kosan Terdaftar (Aktif)</h3>
                <small style="color: #6b7280">Menampilkan seluruh kosan aktif. Anda hanya bisa mengedit milik Anda sendiri.</small>
            </div>

            <div class="table-container">
                @if(!isset($allApprovedKosts) || $allApprovedKosts->isEmpty())
                    <p style="text-align: center; color: #6b7280; padding: 20px;">
                        Belum ada kosan yang terdaftar aktif.
                    </p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th style="min-width: 200px;">Info Utama</th>
                                <th style="min-width: 150px;">Spesifikasi</th>
                                <th style="min-width: 200px;">Fasilitas</th>
                                <th>Status Kepemilikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allApprovedKosts as $kos)
                            <tr>
                                <td>
                                    @if($kos->image)
                                        <a href="{{ asset('storage/' . $kos->image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $kos->image) }}" class="img-thumb" onerror="this.src='https://placehold.co/70?text=No+Img'">
                                        </a>
                                    @else
                                        <div class="img-thumb" style="display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No Img</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 700; font-size: 1rem; color: #111827;">{{ $kos->name }}</div>
                                    <div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 5px;">{{ Str::limit($kos->address, 45) }}</div>
                                    <div style="font-weight: 600; color: #2563eb;">Rp {{ number_format($kos->price_per_month) }} <span style="font-size:0.75rem; color:#6b7280; font-weight:400;">/ bulan</span></div>
                                </td>
                                <td>
                                    <div class="spec-item">
                                        <span>🛏️</span> 
                                        <strong>{{ $kos->total_rooms }}</strong> Tersedia
                                    </div>
                                    <div class="spec-item">
                                        <span>📍</span> 
                                        <strong>{{ $kos->distance }} m</strong> ke Kampus
                                    </div>
                                </td>
                                <td>
                                    <p style="font-size: 0.85rem; color: #4b5563; line-height: 1.4;">
                                        {{ Str::limit($kos->facility_details, 50) }}
                                    </p>
                                </td>
                                <td>
                                    @php
                                        // Cek apakah kosan ini milik user yang sedang login
                                        $isMyKost = (Auth::user()->owner && $kos->owner_id == Auth::user()->owner->owner_id);
                                    @endphp

                                    @if($isMyKost)
                                        <span class="badge badge-approved" style="margin-bottom: 5px;">Milik Anda</span>
                                        <br>
                                        <div style="margin-top: 5px; display: flex; gap: 5px;">
                                            <a href="{{ route('kos.edit', $kos->kost_id) }}" class="btn btn-primary btn-sm">✏️ Edit</a>
                                            
                                            <form action="{{ route('kos.destroy', $kos->kost_id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 10px;">🗑️</button>
                                            </form>
                                        </div>
                                    @else
                                        <div style="display:flex; align-items:center; gap:5px;">
                                            <div style="width:24px; height:24px; background:#e5e7eb; border-radius:50%; display:flex; justify-content:center; align-items:center; font-size:10px;">👤</div>
                                            <span style="font-size: 12px; color: #4b5563;">
                                                {{ $kos->owner->full_name ?? 'Owner #' . $kos->owner_id }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </main>

</body>
</html>