<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SPK Kost</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --secondary: #64748b; --success: #22c55e; --danger: #ef4444; --bg-light: #f3f4f6; --dark: #1e1b4b; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #1e293b; display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar { width: 260px; background-color: var(--dark); color: white; position: fixed; height: 100vh; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header { padding: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-links { padding: 20px 0; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; padding: 12px 25px; color: #c7d2fe; text-decoration: none; font-weight: 500; cursor: pointer; transition: 0.3s; }
        .nav-item:hover, .nav-item.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid var(--success); }
        .nav-icon { margin-right: 12px; font-size: 1.2rem; }
        
        /* CONTENT */
        .main-content { margin-left: 260px; padding: 30px; width: 100%; }
        
        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 4px solid var(--primary); }
        .stat-value { font-size: 2rem; font-weight: 700; color: var(--dark); margin: 5px 0; }
        .stat-label { color: var(--secondary); font-size: 0.9rem; }

        /* TAB SECTIONS */
        .tab-section { display: none; animation: fadeIn 0.4s; }
        .tab-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* COMPONENTS & TABLE */
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 25px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        td { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; font-size: 0.9rem; }
        
        /* ITEM STYLES (NEW) */
        .img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; background-color: #f3f4f6; }
        .spec-item { display: flex; align-items: center; gap: 5px; font-size: 0.8rem; color: #6b7280; margin-bottom: 3px; }
        .owner-info { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #4b5563; }
        .owner-avatar { width: 24px; height: 24px; background: #e0e7ff; color: var(--primary); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 10px; font-weight: bold; }

        /* BADGES & BUTTONS */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .bg-pending { background: #fef3c7; color: #92400e; } 
        .bg-approved { background: #dcfce7; color: #166534; } 
        .bg-rejected { background: #fee2e2; color: #991b1b; }
        
        .btn-action { padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; color: white; margin-right: 5px; font-size: 0.8rem; font-weight: 600; display: inline-block;}
        .btn-approve { background: var(--success); } 
        .btn-approve:hover { background: #15803d; }
        .btn-reject { background: var(--danger); }
        .btn-reject:hover { background: #b91c1c; }
        
        .btn-logout { width: 100%; padding: 12px; background: var(--danger); border: none; color: white; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .report-box { background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 100%); padding: 25px; border-radius: 12px; border-left: 5px solid var(--primary); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>🛡️ AdminPanel</h2>
        </div>
        <nav class="nav-links">
            <div class="nav-item active" onclick="switchTab('verifikasi', this)">
                <span class="nav-icon">📋</span> Verifikasi Kos
            </div>

            <a href="{{ route('kriteria.index') }}" class="nav-item">
                <span class="nav-icon">⚖️</span> Kelola Kriteria
            </a>

            <div class="nav-item" onclick="switchTab('analisis', this)">
                <span class="nav-icon">📊</span> Analisis SPK
            </div>

            <div class="nav-item" onclick="switchTab('laporan', this)">
                <span class="nav-icon">📑</span> Laporan
            </div>
        </nav>
        <div class="logout-box">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn-logout">Keluar Sistem</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 1.8rem; font-weight: 700;">Dashboard Administrator</h1>
            <p style="color: #64748b;">Selamat datang, {{ Auth::user()->username ?? 'Admin' }}.</p>
        </div>

        @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pengajuan</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-card" style="border-bottom-color: #f59e0b;">
                <div class="stat-label">Menunggu Verifikasi</div>
                <div class="stat-value" style="color: #d97706;">{{ $stats['pending'] }}</div>
            </div>
            <div class="stat-card" style="border-bottom-color: #22c55e;">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value" style="color: #166534;">{{ $stats['approved'] }}</div>
            </div>
        </div>

        <section id="verifikasi" class="tab-section active">
            <div class="card">
                <h3 style="margin-bottom: 15px;">Daftar Pengajuan Kosan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th style="min-width: 200px;">Info Utama</th>
                            <th>Spesifikasi</th>
                            <th>Pemilik</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allKosts as $kos)
                        <tr>
                            <td>
                                @if($kos->image)
                                    <a href="{{ asset('storage/' . $kos->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $kos->image) }}" class="img-thumb" onerror="this.src='https://placehold.co/60?text=No+Img'">
                                    </a>
                                @else
                                    <div class="img-thumb" style="display:flex;align-items:center;justify-content:center;font-size:10px;">No Img</div>
                                @endif
                            </td>

                            <td>
                                <div style="font-weight: 700; font-size: 1rem; color: #111827;">{{ $kos->name }}</div>
                                <div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 5px;">{{ Str::limit($kos->address, 35) }}</div>
                                <div style="font-weight: 600; color: #2563eb;">Rp {{ number_format($kos->price_per_month) }}</div>
                            </td>

                            <td>
                                <div class="spec-item">🛏️ {{ $kos->total_rooms ?? '-' }} Kamar</div>
                                <div class="spec-item">📍 {{ $kos->evaluations->where('criteria.code', 'C2')->first()->value ?? '-' }} (Jarak)</div>
                            </td>

                            <td>
                                <div class="owner-info">
                                    <div class="owner-avatar">👤</div>
                                    <div>
                                        <div style="font-weight:600;">{{ $kos->owner->full_name ?? '-' }}</div>
                                        <div style="font-size:0.75rem;">{{ $kos->owner->phone_number ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td><span class="badge bg-{{ $kos->status }}">{{ ucfirst($kos->status) }}</span></td>

                            <td>
                                @if($kos->status == 'pending')
                                    <form action="{{ route('admin.verifikasi', $kos->kost_id) }}" method="POST" style="display:inline-block;">
                                        @csrf <input type="hidden" name="status" value="approved">
                                        <button class="btn-action btn-approve" title="Terima">✓ Terima</button>
                                    </form>
                                    <form action="{{ route('admin.verifikasi', $kos->kost_id) }}" method="POST" style="display:inline-block; margin-top: 5px;">
                                        @csrf <input type="hidden" name="status" value="rejected">
                                        <button class="btn-action btn-reject" title="Tolak" onclick="return confirm('Yakin tolak?')">✗ Tolak</button>
                                    </form>
                                @else
                                    <span style="color:#94a3b8; font-size:0.8rem; font-style:italic;">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 20px; color: #94a3b8;">Belum ada data kosan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="analisis" class="tab-section">
            
            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Hasil Perhitungan SAW</h2>
                <p style="color: var(--secondary);">Detail perhitungan sistem pendukung keputusan.</p>
            </div>

            @if(empty($ranks))
                <div class="card">
                    <p style="color:#94a3b8; padding:20px; text-align:center; background: #f8fafc; border-radius: 8px;">
                        ⚠️ Belum ada data kosan dengan status <strong>Approved</strong> untuk dihitung.
                    </p>
                </div>
            @else

                <div class="card" style="margin-bottom: 30px;">
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; margin-bottom: 15px;">
                        <h3 style="font-size: 1.1rem; font-weight: 600; color: #4b5563;">1. Matriks Nilai Alternatif (Data Mentah)</h3>
                        <small style="color: #6b7280;">Nilai asli dari setiap kosan berdasarkan kriteria.</small>
                    </div>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kosan</th>
                                    @foreach($criterias as $c)
                                        <th style="text-align: center;">{{ $c->criteria_name }} ({{ $c->code }})</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranks as $rank)
                                <tr>
                                    <td><strong>{{ $rank['kost']->name }}</strong></td>
                                    @foreach($criterias as $c)
                                        <td style="text-align: center;">
                                            {{ $rank['details'][$c->criteria_id]['val'] ?? 0 }}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 30px;">
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; margin-bottom: 15px;">
                        <h3 style="font-size: 1.1rem; font-weight: 600; color: #4b5563;">2. Hasil Normalisasi (R)</h3>
                        <small style="color: #6b7280;">Nilai setelah disetarakan ke skala 0-1 (Benefit/Cost).</small>
                    </div>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kosan</th>
                                    @foreach($criterias as $c)
                                        <th style="text-align: center;">{{ $c->code }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranks as $rank)
                                <tr>
                                    <td><strong>{{ $rank['kost']->name }}</strong></td>
                                    @foreach($criterias as $c)
                                        <td style="text-align: center;">
                                            {{ number_format($rank['details'][$c->criteria_id]['norm'] ?? 0, 3) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; margin-bottom: 15px;">
                        <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--success);">3. Hasil Akhir & Ranking (V)</h3>
                        <small style="color: #6b7280;">Penjumlahan dari (Nilai Normalisasi × Bobot).</small>
                    </div>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 80px; text-align: center;">Ranking</th>
                                    <th>Nama Kosan</th>
                                    <th style="text-align: center;">Nilai Preferensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranks as $index => $rank)
                                <tr style="{{ $index == 0 ? 'background-color: #f0fdf4; border-left: 4px solid var(--success);' : '' }}">
                                    <td style="text-align: center; font-weight: bold; font-size: 1.1rem;">
                                        #{{ $index + 1 }}
                                    </td>
                                    <td>
                                        <strong>{{ $rank['kost']->name }}</strong>
                                        @if($index == 0) <span class="badge bg-approved">Terbaik 🏆</span> @endif
                                    </td>
                                    <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: {{ $index == 0 ? 'var(--success)' : '#4b5563' }}">
                                        {{ number_format($rank['score'], 4) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif
        </section>

        <section id="laporan" class="tab-section">
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h3>Laporan Rekomendasi</h3>
                    <button class="btn-action btn-approve" onclick="window.print()" style="font-size: 14px;">🖨️ Cetak PDF</button>
                </div>
                
                @if($topKostAnalysis)
                    <div class="report-box">
                        <h3 style="color: #3730a3; margin-bottom: 10px;">🏆 Rekomendasi Utama</h3>
                        <p style="line-height: 1.6; color: #3730a3;">{!! $topKostAnalysis !!}</p>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <h4>Top 3 Alternatif:</h4>
                        <ul>
                        @foreach(array_slice($ranks, 0, 3) as $r)
                            <li style="margin: 5px 0;">{{ $r['kost']->name }} (Skor: {{ number_format($r['score'], 3) }})</li>
                        @endforeach
                        </ul>
                    </div>
                @else
                    <p style="color:#64748b;">Belum ada data analisis yang cukup untuk membuat laporan.</p>
                @endif
            </div>
        </section>

    </main>

    <script>
        function switchTab(tabId, element) {
            // Sembunyikan semua tab content
            document.querySelectorAll('.tab-section').forEach(sec => sec.classList.remove('active'));
            // Tampilkan tab yang dipilih
            document.getElementById(tabId).classList.add('active');
            
            // Hapus class active dari semua nav-item div (bukan link a)
            document.querySelectorAll('div.nav-item').forEach(item => item.classList.remove('active'));
            // Tambahkan class active ke tombol yang diklik
            element.classList.add('active');
        }
    </script>
</body>
</html>