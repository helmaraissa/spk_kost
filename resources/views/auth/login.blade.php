<!DOCTYPE html>
<html>
<head>
    <title>Login SPK Kosan</title>
    <!-- Bisa pakai Bootstrap CDN nanti -->
</head>
<body>
    <h2>Login Sistem</h2>
    
    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <br>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Masuk</button>
    </form>
</body>
</html>