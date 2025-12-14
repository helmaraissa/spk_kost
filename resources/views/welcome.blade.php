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
        /* Animasi Modal */
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: opacity 0.3s, transform 0.3s; }
        .modal-exit { opacity: 1; transform: scale(1); }
        .modal-exit-active { opacity: 0; transform: scale(0.95); transition: opacity 0.2s, transform 0.2s; }
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
                                <button onclick="openModal(this)"
                                        data-name="{{ $rank['kost']->name }}"
                                        data-image="{{ $rank['kost']->gambar_url }}"
                                        data-price="Rp {{ number_format($rank['kost']->price_per_month, 0, ',', '.') }}"
                                        data-address="{{ $rank['kost']->address }}"
                                        data-distance="{{ $rank['kost']->distance ?? '-' }}"
                                        data-size="{{ $rank['kost']->room_size ?? '-' }}"
                                        data-facilities="{{ $rank['kost']->facilities ?? '-' }}"
                                        data-desc="{{ $rank['kost']->description ?? 'Tidak ada deskripsi.' }}"
                                        data-owner="{{ $rank['kost']->owner->name ?? 'Pemilik' }}"
                                        class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition cursor-pointer">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                
                <button onclick="closeModal()" class="absolute top-4 right-4 z-10 bg-black/30 hover:bg-black/50 text-white w-8 h-8 rounded-full flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <div class="h-64 w-full overflow-hidden bg-gray-100">
                    <img id="modalImage" src="" alt="Kosan" class="w-full h-full object-cover">
                </div>

                <div class="px-6 py-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 id="modalName" class="text-2xl font-bold text-gray-900 leading-tight">Nama Kosan</h3>
                            <p id="modalAddress" class="text-gray-500 text-sm mt-1"><i class="fa-solid fa-location-dot text-red-500"></i> Alamat</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Harga per bulan</p>
                            <p id="modalPrice" class="text-2xl font-bold text-green-600">Rp 0</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6 bg-blue-50 p-4 rounded-lg">
                        <div class="text-center">
                            <i class="fa-solid fa-ruler-combined text-blue-500 mb-1 block"></i>
                            <span class="text-xs text-gray-500">Ukuran</span>
                            <p id="modalSize" class="font-semibold text-gray-700">-</p>
                        </div>
                        <div class="text-center">
                            <i class="fa-solid fa-person-walking text-blue-500 mb-1 block"></i>
                            <span class="text-xs text-gray-500">Jarak</span>
                            <p id="modalDistance" class="font-semibold text-gray-700">-</p>
                        </div>
                        <div class="text-center">
                            <i class="fa-solid fa-user-tie text-blue-500 mb-1 block"></i>
                            <span class="text-xs text-gray-500">Pemilik</span>
                            <p id="modalOwner" class="font-semibold text-gray-700">-</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-bold text-gray-800 text-sm mb-2 uppercase tracking-wide">Fasilitas & Detail</h4>
                        <p id="modalFacilities" class="text-sm text-blue-600 font-medium mb-2 bg-blue-50 inline-block px-3 py-1 rounded-full">-</p>
                        <p id="modalDesc" class="text-gray-600 text-sm leading-relaxed whitespace-pre-line"></p>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="https://wa.me/" target="_blank" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg text-center transition shadow-md flex justify-center items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-xl"></i> Hubungi Pemilik
                        </a>
                        <button onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-lg transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('detailModal');

        function openModal(button) {
            // Ambil data dari atribut tombol
            const name = button.getAttribute('data-name');
            const image = button.getAttribute('data-image');
            const price = button.getAttribute('data-price');
            const address = button.getAttribute('data-address');
            const distance = button.getAttribute('data-distance');
            const size = button.getAttribute('data-size');
            const facilities = button.getAttribute('data-facilities');
            const desc = button.getAttribute('data-desc');
            const owner = button.getAttribute('data-owner');

            // Isi data ke dalam elemen Modal
            document.getElementById('modalName').innerText = name;
            document.getElementById('modalImage').src = image;
            document.getElementById('modalPrice').innerText = price;
            document.getElementById('modalAddress').innerHTML = '<i class="fa-solid fa-location-dot text-red-500 mr-1"></i> ' + address;
            document.getElementById('modalDistance').innerText = distance + ' meter';
            document.getElementById('modalSize').innerText = size + ' m²';
            document.getElementById('modalFacilities').innerText = facilities;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('modalOwner').innerText = owner;

            // Tampilkan Modal
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Tutup modal jika tombol Esc ditekan
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>

    <footer class="bg-white border-t border-gray-200 mt-12 py-8 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} SPK Kosan Cibogo. Dibuat dengan Laravel & SAW.</p>
    </footer>

</body>
</html>