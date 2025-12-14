<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemilik Kos - SPK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --bg-color: #f3f4f6; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .register-card { background: white; width: 100%; max-width: 500px; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .title { font-size: 1.5rem; font-weight: 700; color: #1f2937; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 0.9rem; color: #6b7280; text-align: center; margin-bottom: 30px; }
        
        .form-group { margin-bottom: 15px; }
        .label { display: block; margin-bottom: 5px; font-size: 0.9rem; font-weight: 500; color: #374151; }
        .input-control { width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; outline: none; }
        .input-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
                .btn-register { width: 100%; padding: 12px; background-color: var(--primary); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .btn-register:hover { background-color: #4338ca; }
        
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .footer-link { margin-top: 20px; text-align: center; font-size: 0.85rem; color: #6b7280; }
        .footer-link a { color: var(--primary); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

    <div class="register-card">
        <h1 class="title">Daftar Akun</h1>
        <p class="subtitle">Bergabung sebagai Pemilik Kos.</p>

        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label class="label">Username</label>
                <input type="text" name="username" class="input-control" required value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <label class="label">Password</label>
                <input type="password" name="password" class="input-control" required>
            </div>

            <div class="form-group">
                <label class="label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="input-control" required>
            </div>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

            <div class="form-group">
                <label class="label">Nama Lengkap</label>
                <input type="text" name="full_name" class="input-control" required value="{{ old('full_name') }}">
            </div>

            <div class="form-group">
                <label class="label">Nomor HP / WhatsApp</label>
                <input type="number" name="phone_number" class="input-control" required value="{{ old('phone_number') }}">
            </div>

            <div class="form-group">
                <label class="label">Alamat Domisili</label>
                <input type="text" name="address" class="input-control" required value="{{ old('address') }}">
            </div>

            <button type="submit" class="btn-register">Daftar Sekarang</button>
        </form>

        <div class="footer-link">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Login disini</a></p>
        </div>
    </div>

</body>
</html>