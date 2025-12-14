<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// PUBLIC
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cari-kos', [HomeController::class, 'search'])->name('kos.search');

// AUTH (Login/Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
    
    // --- ADMIN GROUP ---
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/verifikasi/{id}', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');

        // CRUD KRITERIA
        Route::get('/kriteria', [CriteriaController::class, 'index'])->name('kriteria.index');
        Route::post('/kriteria', [CriteriaController::class, 'store'])->name('kriteria.store');
        Route::put('/kriteria/{id}', [CriteriaController::class, 'update'])->name('kriteria.update');
        Route::delete('/kriteria/{id}', [CriteriaController::class, 'destroy'])->name('kriteria.destroy');
    });

    // --- OWNER GROUP ---
    Route::prefix('owner')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');
        Route::resource('kos', OwnerController::class); 
    });
});

// =========================================================================
//  ROUTE DARURAT: BYPASS SYMLINK WINDOWS (Cukup Satu Kali Saja)
// =========================================================================
Route::get('storage/kos-images/{filename}', function ($filename) {
    // 1. Cari file fisik di folder storage asli
    $path = storage_path('app/public/kos-images/' . $filename);

    // 2. Cek apakah file ada?
    if (!File::exists($path)) {
        abort(404);
    }

    // 3. Ambil file & tipe kontennya
    $file = File::get($path);
    $type = File::mimeType($path);

    // 4. Kirim ke browser sebagai gambar
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});