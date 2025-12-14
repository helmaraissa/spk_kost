<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Kosan Cibogo (SAW)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        
        /* Navbar Sederhana */
        .navbar { background-color: #ffffff; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 1.5em; font-weight: bold; color: #2c3e50; text-decoration: none; }
        .auth-links a { text-decoration: none; color: #555; margin-left: 15px; font-weight: 500; }
        .btn-login { background-color: #3498db; color: white !important; padding: 8px 15px; border-radius: 5px; }

        /* Container Utama */
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        
        /* Header Section */
        .hero { text-align: center; margin-bottom: 40px; }
        .hero h2 { color: #2c3e50; margin-bottom: 10px; }
        .hero p { color: #7f8c8d; }

        /* Card Kosan */
        .card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center; border-left: 5px solid #3498db; transition: transform 0.2s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
        
        /* Rank Badge (Juara 1, 2, dst) */
        .rank-badge { background: #34495e; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 1.2em; margin-right: 20px; flex-shrink: 0; }
        .rank-1 { background: #f1c40f; transform: scale(1.1); box-shadow: 0 0 10px rgba(241, 196, 15, 0.5); } /* Emas untuk Juara 1 */
        .rank-2 { background: #bdc3c7; } /* Perak */
        .rank-3 { background: #d35400; } /* Perunggu */

        /* Info Kosan */
        .info { flex-grow: 1; }
        .info h3 { margin: 0 0 5px; color: #2c3e50; }
        .price { color: #27ae60; font-weight: bold; font-size: 1.1em; }
        .address { color: #7f8c8d; font-size: 0.9em; margin-top: 5px; }
        .owner { font-size: 0.8em; color: #95a5a6; margin-top: 5px; }

        /* Nilai SPK */
        .score-box { text-align: center; margin-left: 20px; min-width: 80px; }
        .score-val { font-size: 1.5em; font-weight: bold; color: #3498db; display: block; }
        .score-label { font-size: 0.7em; color: #95a5a6; text-transform: uppercase; letter-spacing: 1px; }

        /* State Kosong */
        .empty-state { text-align: center; padding: 50px; color: #95a5a6; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="brand">🏠 SPK Kosan Cibogo</a>
        <div class="auth-links">
            @if (Route::has('login'))
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-login">Dashboard Admin</a>
                    @else
                        <a href="{{ route('owner.dashboard') }}" class="btn-login">Dashboard Pemilik</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-login">Login Pemilik/Admin</a>
                @endauth
            @endif
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <h2>Rekomendasi Kosan Terbaik</h2>
            <p>Diurutkan berdasarkan metode <i>Simple Additive Weighting</i> (SAW)</p>
            <p><small>Kriteria: Harga (Cost), Jarak (Cost), Fasilitas (Benefit), Luas (Benefit)</small></p>
        </div>

        @if(empty($ranks))
            <div class="empty-state">
                <h3>Belum ada data kosan yang direkomendasikan.</h3>
                <p>Silakan login sebagai Pemilik untuk mendaftarkan kos, dan Admin untuk memverifikasinya.</p>
            </div>
        @else
            @foreach($ranks as $index => $rank)
                <div class="card">
                    <!-- Penomoran Ranking (1, 2, 3...) -->
                    <div class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">
                        #{{ $index + 1 }}
                    </div>

                    <div class="info">
                        <h3>{{ $rank['kost']->name }}</h3>
                        <div class="price">Rp {{ number_format($rank['kost']->price_per_month, 0, ',', '.') }} / bulan</div>
                        <div class="address">📍 {{ $rank['kost']->address }}</div>
                        <div class="owner">👤 Pemilik: {{ $rank['kost']->owner->full_name ?? 'Tidak diketahui' }}</div>
                    </div>

                    <div class="score-box">
                        <span class="score-val">{{ number_format($rank['score'], 4) }}</span>
                        <span class="score-label">Nilai SPK</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</body>
</html>