<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Kosan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- VARIABLES & RESET --- */
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* --- LOGIN CARD --- */
        .login-card {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* --- HEADER --- */
        .logo {
            font-size: 3rem;
            margin-bottom: 10px;
            display: inline-block;
        }
        .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 30px;
        }

        /* --- FORM ELEMENTS --- */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #374151;
        }
        .input-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            outline: none;
        }
        .input-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* --- BUTTON --- */
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background-color: var(--primary-hover);
        }

        /* --- ALERT --- */
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-align: left;
            border: 1px solid #fecaca;
        }

        /* --- FOOTER --- */
        .footer-link {
            margin-top: 25px;
            font-size: 0.85rem;
            color: var(--text-gray);
            line-height: 1.6;
        }
        .footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo">🏠</div>
        
        <h1 class="title">Selamat Datang</h1>
        <p class="subtitle">Masuk untuk mengelola sistem SPK Kosan.</p>

        @if ($errors->any())
            <div class="alert-error">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="label" for="username">Username</label>
                <input type="text" id="username" name="username" class="input-control" placeholder="Masukkan username Anda" required autofocus>
            </div>

            <div class="form-group">
                <label class="label" for="password">Password</label>
                <input type="password" id="password" name="password" class="input-control" placeholder="••••••••" required>
            </div>

            <div class="form-group" style="display: flex; align-items: center;">
                <input type="checkbox" name="remember" id="remember" style="margin-right: 8px; cursor: pointer;">
                <label for="remember" style="font-size: 0.85rem; color: #4b5563; cursor: pointer; user-select: none;">Ingat Saya</label>
            </div>

            <button type="submit" class="btn-login">Masuk ke Sistem</button>
        </form>

        <div class="footer-link">
            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar Pemilik Kos</a></p>
            
            <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">
            
            <p>Bukan Admin/Pemilik? <a href="{{ route('home') }}">Kembali ke Beranda</a></p>
        </div>
    </div>

</body>
</html>