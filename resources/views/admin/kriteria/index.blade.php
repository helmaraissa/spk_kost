<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kriteria - Admin SPK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- COPY STYLE LENGKAP DARI DASHBOARD --- */
        :root { --primary: #4f46e5; --secondary: #64748b; --success: #22c55e; --danger: #ef4444; --bg-light: #f3f4f6; --dark: #1e1b4b; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #1e293b; display: flex; min-height: 100vh; }
        
        /* SIDEBAR (Sama Persis) */
        .sidebar { width: 260px; background-color: var(--dark); color: white; position: fixed; height: 100vh; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header { padding: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-links { padding: 20px 0; flex-grow: 1; }
        /* Style Link Navigasi */
        .nav-link { display: flex; align-items: center; padding: 12px 25px; color: #c7d2fe; text-decoration: none; font-weight: 500; cursor: pointer; transition: 0.3s; width: 100%; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid var(--success); }
        .nav-icon { margin-right: 12px; font-size: 1.2rem; }
        
        .logout-box { padding: 20px; }
        .btn-logout { width: 100%; padding: 12px; background: var(--danger); border: none; color: white; border-radius: 6px; cursor: pointer; font-weight: bold; }

        /* CONTENT */
        .main-content { margin-left: 260px; padding: 30px; width: 100%; }
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 25px; }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #f8fafc; color: #64748b; font-size: 0.85rem; text-transform: uppercase; }
        
        /* BUTTONS */
        .btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; color: white; font-weight: 600; }
        .btn-add { background: var(--primary); }
        .btn-add:disabled { background: #cbd5e1; cursor: not-allowed; }
        .btn-edit { background: #f59e0b; margin-right: 5px; }
        .btn-delete { background: var(--danger); }
        
        /* ALERTS */
        .alert-error { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; }

        /* MODAL */
        .modal { display: none; position: fixed; z-index: 200; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); }
        .modal-content { background: white; margin: 5% auto; padding: 25px; width: 450px; border-radius: 12px; animation: slideDown 0.3s; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .close-btn { float: right; font-size: 1.5rem; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>🛡️ AdminPanel</h2>
        </div>
        <nav class="nav-links">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <span class="nav-icon">📋</span> Verifikasi Kos
            </a>

            <a href="{{ route('kriteria.index') }}" class="nav-link active">
                <span class="nav-icon">⚖️</span> Kelola Kriteria
            </a>

            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <span class="nav-icon">📊</span> Analisis SPK
            </a>

            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <span class="nav-icon">📑</span> Laporan
            </a>
        </nav>
        <div class="logout-box">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn-logout">Keluar Sistem</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700;">Kelola Kriteria (SAW)</h1>
                <p style="color: var(--secondary);">Total Bobot Saat Ini: 
                    <strong style="color: {{ $totalWeight == 1 ? 'green' : ($totalWeight > 1 ? 'red' : 'orange') }}">
                        {{ $totalWeight * 100 }}%
                    </strong>
                </p>
            </div>
            
            @if($totalWeight >= 1)
                <button class="btn btn-add" disabled title="Total bobot sudah 100%">🚫 Kuota Penuh</button>
            @else
                <button class="btn btn-add" onclick="openModal('addModal')">+ Tambah Kriteria</button>
            @endif
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                ⚠️ <strong>Terjadi Kesalahan:</strong>
                <ul style="margin-left: 20px; margin-top: 5px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Atribut</th>
                        <th>Bobot</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $c)
                    <tr>
                        <td><strong>{{ $c->code }}</strong></td>
                        <td>{{ $c->criteria_name }}</td>
                        <td>
                            @if($c->attribute == 'cost')
                                <span style="color:red; font-weight:bold;">Cost</span> (Biaya)
                            @else
                                <span style="color:green; font-weight:bold;">Benefit</span> (Untung)
                            @endif
                        </td>
                        <td>{{ $c->weight }} ({{ $c->weight * 100 }}%)</td>
                        <td>
                            <button class="btn btn-edit" 
                                onclick="openEditModal('{{ $c->criteria_id }}', '{{ $c->criteria_name }}', '{{ $c->weight }}', '{{ $c->attribute }}')">
                                ✏️
                            </button>
                            
                            <form action="{{ route('kriteria.destroy', $c->criteria_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus kriteria ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3 style="margin-bottom: 20px;">Tambah Kriteria Baru</h3>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Kriteria</label>
                    <input type="text" name="criteria_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Atribut</label>
                    <select name="attribute" class="form-control">
                        <option value="benefit">Benefit (Untung)</option>
                        <option value="cost">Cost (Biaya)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bobot (Sisa Kuota: {{ 1 - $totalWeight }})</label>
                    <input type="number" step="0.01" max="{{ 1 - $totalWeight }}" name="weight" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-add" style="width:100%">Simpan</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3 style="margin-bottom: 20px;">Edit Kriteria</h3>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama Kriteria</label>
                    <input type="text" name="criteria_name" id="editName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Atribut</label>
                    <select name="attribute" id="editAttribute" class="form-control">
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bobot</label>
                    <input type="number" step="0.01" name="weight" id="editWeight" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-add" style="width:100%">Update Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        function openEditModal(id, name, weight, attr) {
            document.getElementById('editForm').action = '/admin/kriteria/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editWeight').value = weight;
            document.getElementById('editAttribute').value = attr;
            openModal('editModal');
        }
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>