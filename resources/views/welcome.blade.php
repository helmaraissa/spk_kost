<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Kosan Cibogo (SAW)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>

    <nav class="absolute top-0 w-full z-20 px-6 py-4 flex justify-between items-center text-white">
        <a href="/" class="text-2xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-house-chimney text-blue-400"></i> SPK Cibogo
        </a>
        <div class="hidden md:flex gap-6 text-sm font-medium">
            @if (Route::has('login'))
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-full transition shadow-lg">Dashboard Admin</a>
                    @else
                        <a href="{{ route('owner.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-full transition shadow-lg">Dashboard Pemilik</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-white text-gray-900 hover:bg-gray-100 px-5 py-2 rounded-full transition shadow-lg">Masuk / Daftar</a>
                @endauth
            @endif
        </div>
    </nav>

    <div class="hero-bg h-[500px] flex flex-col justify-center items-center text-white text-center px-4 relative">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-md">Temukan Kosan Terbaik</h1>
        <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl">Rekomendasi otomatis berdasarkan Harga, Jarak, dan Fasilitas menggunakan metode SAW.</p>
        
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-4xl absolute -bottom-16 text-left border border-gray-100">
            <form action="/" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-1">Maksimal Harga</label>
                    <select name="filter_harga" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-gray-700">
                        <option value="">Semua Harga</option>
                        <option value="500000" {{ request('filter_harga') == '500000' ? 'selected' : '' }}>< Rp 500rb</option>
                        <option value="1000000" {{ request('filter_harga') == '1000000' ? 'selected' : '' }}>< Rp 1 Juta</option>
                        <option value="1500000" {{ request('filter_harga') == '1500000' ? 'selected' : '' }}>< Rp 1.5 Juta</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-1">Maksimal Jarak</label>
                    <select name="filter_jarak" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-gray-700">
                        <option value="">Semua Jarak</option>
                        <option value="500" {{ request('filter_jarak') == '500' ? 'selected' : '' }}>Dekat (< 500m)</option>
                        <option value="1000" {{ request('filter_jarak') == '1000' ? 'selected' : '' }}>Sedang (< 1km)</option>
                        <option value="2000" {{ request('filter_jarak') == '2000' ? 'selected' : '' }}>Jauh (> 1km)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-1">Fasilitas Wajib</label>
                    <select name="filter_fasilitas" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-gray-700">
                        <option value="">Bebas</option>
                        <option value="wifi" {{ request('filter_fasilitas') == 'wifi' ? 'selected' : '' }}>WiFi</option>
                        <option value="ac" {{ request('filter_fasilitas') == 'ac' ? 'selected' : '' }}>AC</option>
                        <option value="km_dalam" {{ request('filter_fasilitas') == 'km_dalam' ? 'selected' : '' }}>Kamar Mandi Dalam</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition duration-300">
                        <i class="fa-solid fa-filter mr-2"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 pt-24 pb-12">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hasil Rekomendasi</h2>
                <p class="text-gray-500 text-sm">Diurutkan dari nilai SPK tertinggi ke terendah.</p>
            </div>
            <div class="hidden md:block bg-white px-4 py-2 rounded-lg shadow-sm text-sm text-gray-600">
                <span class="font-bold text-blue-600">Nilai 1.0</span> = Sangat Layak
            </div>
        </div>

        @if(empty($ranks))
            <div class="text-center py-20 bg-white rounded-xl shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" class="w-32 mx-auto opacity-50 mb-4" alt="Empty">
                <h3 class="text-xl font-bold text-gray-600">Belum ada data kosan.</h3>
                <p class="text-gray-400">Cobalah ubah filter atau login untuk menambahkan data.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($ranks as $index => $rank)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 flex flex-col h-full relative group">
                        
                        <div class="absolute top-4 left-4 z-10">
                            @if($index == 0)
                                <div class="bg-yellow-400 text-white w-10 h-10 flex items-center justify-center rounded-full shadow-lg font-bold text-lg ring-4 ring-white">1</div>
                            @elseif($index == 1)
                                <div class="bg-gray-400 text-white w-10 h-10 flex items-center justify-center rounded-full shadow-lg font-bold text-lg ring-4 ring-white">2</div>
                            @elseif($index == 2)
                                <div class="bg-orange-500 text-white w-10 h-10 flex items-center justify-center rounded-full shadow-lg font-bold text-lg ring-4 ring-white">3</div>
                            @else
                                <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full shadow-md font-bold text-sm">#{{ $index + 1 }}</div>
                            @endif
                        </div>

                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $rank['kost']->gambar_url }}" 
                                 alt="{{ $rank['kost']->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            
                            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-4">
                                <span class="text-white text-xs font-semibold bg-blue-600 px-2 py-1 rounded">Putra/Putri</span>
                            </div>
                        </div>

                        <div class="p-5 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-800 line-clamp-1 hover:text-blue-600 cursor-pointer">{{ $rank['kost']->name }}</h3>
                                <div class="text-right">
                                    <span class="block text-xs text-gray-400">Nilai SPK</span>
                                    <span class="text-lg font-bold text-blue-600">{{ number_format($rank['score'], 3) }}</span>
                                </div>
                            </div>

                            <p class="text-gray-500 text-sm mb-4 flex items-center">
                                <i class="fa-solid fa-location-dot text-red-400 mr-2"></i> {{ $rank['kost']->address }}
                            </p>

                            <div class="flex flex-wrap gap-2 mb-4 text-xs text-gray-600">
                                <span class="bg-gray-100 px-2 py-1 rounded-md"><i class="fa-solid fa-ruler-combined"></i> {{ $rank['kost']->room_size ?? '-' }} m²</span>
                                <span class="bg-gray-100 px-2 py-1 rounded-md"><i class="fa-solid fa-map-pin"></i> {{ $rank['kost']->distance ?? '0' }} m</span>
                            </div>

                            <div class="mt-auto border-t border-gray-100 pt-4 flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-400">Harga per bulan</p>
                                    <p class="text-green-600 font-bold text-lg">Rp {{ number_format($rank['kost']->price_per_month, 0, ',', '.') }}</p>
                                </div>
                                <button class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12 py-8 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} SPK Kosan Cibogo. Dibuat dengan Laravel & SAW.</p>
    </footer>

</body>
</html>