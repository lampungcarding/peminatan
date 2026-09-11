<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TesMinatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Perencanaan Karier & Studi Siswa v2
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Smart Dashboard Redirect
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->isAdmin() || auth()->user()->isGuruBk()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('siswa.dashboard');
})->name('dashboard');

// Siswa Routes (Middleware: auth, role:siswa)
Route::middleware(['auth', 'role:siswa'])->group(function () {
    // Dashboard & Profil
    Route::get('/siswa', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/pilihan-saya', [SiswaController::class, 'pilihanSaya'])->name('siswa.pilihan-saya');
    Route::get('/profil', [SiswaController::class, 'profil'])->name('siswa.profil');

    // Modul Tes Minat Karier (RIASEC)
    Route::get('/tes-minat', [TesMinatController::class, 'index'])->name('tes.index');
    Route::get('/tes-minat/mulai', [TesMinatController::class, 'mulai'])->name('tes.mulai');
    Route::post('/tes-minat/simpan', [TesMinatController::class, 'simpan'])->name('tes.simpan');
    Route::get('/tes-minat/hasil', [TesMinatController::class, 'hasil'])->name('tes.hasil');
    Route::get('/rekomendasi', [TesMinatController::class, 'rekomendasi'])->name('tes.rekomendasi');

    // Modul Rencana Setelah Lulus
    Route::get('/rencana', [SiswaController::class, 'rencana'])->name('siswa.rencana');
    Route::post('/rencana', [SiswaController::class, 'simpanRencana'])->name('siswa.rencana.simpan');
    Route::get('/siswa/rencana', [SiswaController::class, 'rencana']); // fallback alias
    Route::post('/siswa/rencana', [SiswaController::class, 'simpanRencana']); // fallback alias

    // Konfirmasi & Submit Akhir
    Route::get('/konfirmasi', [SiswaController::class, 'konfirmasi'])->name('siswa.konfirmasi');
    Route::get('/siswa/konfirmasi', [SiswaController::class, 'konfirmasi']); // fallback alias
    Route::post('/submit', [SiswaController::class, 'submit'])->name('siswa.submit');
    Route::post('/siswa/submit', [SiswaController::class, 'submit']); // fallback alias
});

// Pencarian Kampus & Prodi via KIP Kuliah API (Dapat diakses Siswa, Guru BK, dan Admin)
Route::middleware(['auth', 'role:siswa,admin,guru_bk'])->group(function () {
    Route::post('/siswa/cari-kampus', [SiswaController::class, 'cariKampus'])->name('siswa.cari-kampus');
    Route::post('/siswa/cari-prodi', [SiswaController::class, 'cariProdi'])->name('siswa.cari-prodi');
});

// Admin & Guru BK Shared Routes (Middleware: auth, role:admin,guru_bk)
Route::middleware(['auth', 'role:admin,guru_bk'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Data Siswa
    Route::get('/siswa', [AdminController::class, 'dataSiswa'])->name('admin.siswa');
    Route::post('/siswa', [AdminController::class, 'siswaStore'])->name('admin.siswa.store');
    Route::put('/siswa/{id}', [AdminController::class, 'siswaUpdate'])->name('admin.siswa.update');
    Route::delete('/siswa/{id}', [AdminController::class, 'siswaDestroy'])->name('admin.siswa.destroy');

    // Hasil Tes Minat RIASEC
    Route::get('/hasil-tes', [AdminController::class, 'hasilTes'])->name('admin.hasil-tes');
    Route::delete('/hasil-tes/{id}/reset', [AdminController::class, 'resetTes'])->name('admin.hasil-tes.reset');

    // Rencana Siswa
    Route::get('/rencana', [AdminController::class, 'rencanaSiswa'])->name('admin.rencana');
    Route::get('/rencana-siswa', [AdminController::class, 'rencanaSiswa'])->name('admin.rencana-siswa'); // alias
    Route::delete('/rencana-siswa/{id}/reset', [AdminController::class, 'resetPilihan'])->name('admin.rencana-siswa.reset');

    // Bank Rekomendasi Karier
    Route::get('/rekomendasi', [AdminController::class, 'rekomendasi'])->name('admin.rekomendasi');

    // Penjelajah Kampus & Prodi
    Route::get('/kampus-prodi', [AdminController::class, 'kampusProdi'])->name('admin.kampus-prodi');

    // Laporan & Export Komprehensif
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/laporan/export', [AdminController::class, 'exportLaporan'])->name('admin.laporan.export');
    Route::get('/rencana-siswa/export', [AdminController::class, 'exportLaporan'])->name('admin.rencana-siswa.export'); // alias

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        // Pengelolaan Akun Guru BK & Class Mapping
        Route::get('/guru-bk', [AdminController::class, 'guruBkIndex'])->name('admin.guru-bk');
        Route::post('/guru-bk', [AdminController::class, 'guruBkStore'])->name('admin.guru-bk.store');
        Route::put('/guru-bk/{id}', [AdminController::class, 'guruBkUpdate'])->name('admin.guru-bk.update');
        Route::delete('/guru-bk/{id}', [AdminController::class, 'guruBkDestroy'])->name('admin.guru-bk.destroy');

        // Pengelolaan Bank Pertanyaan Tes
        Route::get('/pertanyaan-tes', [AdminController::class, 'pertanyaanTes'])->name('admin.pertanyaan-tes');
        Route::post('/pertanyaan-tes', [AdminController::class, 'simpanPertanyaan'])->name('admin.pertanyaan-tes.simpan');
        Route::put('/pertanyaan-tes/{id}', [AdminController::class, 'updatePertanyaan'])->name('admin.pertanyaan-tes.update');

        // Pengaturan Aplikasi
        Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('admin.pengaturan');
        Route::post('/pengaturan/umum', [AdminController::class, 'simpanPengaturanUmum'])->name('admin.pengaturan.umum');
        Route::post('/pengaturan/password', [AdminController::class, 'updatePasswordAdmin'])->name('admin.pengaturan.password');
        Route::post('/pengaturan/clear-cache', [AdminController::class, 'clearCache'])->name('admin.pengaturan.clear-cache');
        Route::post('/pengaturan/sync-kip', [AdminController::class, 'syncKip'])->name('admin.pengaturan.sync-kip');
        Route::post('/pengaturan/reimport-excel', [AdminController::class, 'reimportExcel'])->name('admin.pengaturan.reimport-excel');
    });
});
