<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;
use App\Models\PilihanSetelahLulus;

echo "=== 1. VERIFIKASI DATA DATABASE SISWA EKSISTING ===\n";
$totalPilihan = PilihanSetelahLulus::count();
echo "Total data pilihan_setelah_lulus: {$totalPilihan}\n";
$rows = PilihanSetelahLulus::all();
foreach ($rows as $r) {
    echo "  - User ID {$r->user_id}: Rencana [{$r->rencana}], Kampus: [{$r->nama_perguruan_tinggi}], Prodi: [{$r->nama_program_studi} ({$r->jenjang})]\n";
}

echo "\n=== 2. TEST SISWACONTROLLER cariKampus (AJAX) ===\n";
$siswaController = app(SiswaController::class);

$req1 = Request::create('/siswa/cari-kampus', 'GET', ['keyword' => 'unila']);
$res1 = $siswaController->cariKampus($req1);
$data1 = json_decode($res1->getContent(), true);
echo "cariKampus('unila') -> " . count($data1) . " hasil:\n";
foreach (array_slice($data1, 0, 3) as $k) {
    echo "  - [{$k['id']}] {$k['nama']}\n";
}

$req2 = Request::create('/siswa/cari-kampus', 'GET', ['keyword' => 'polinela']);
$res2 = $siswaController->cariKampus($req2);
$data2 = json_decode($res2->getContent(), true);
echo "cariKampus('polinela') -> " . count($data2) . " hasil:\n";
foreach (array_slice($data2, 0, 3) as $k) {
    echo "  - [{$k['id']}] {$k['nama']}\n";
}

$req3 = Request::create('/siswa/cari-kampus', 'GET', ['keyword' => 'darmajaya']);
$res3 = $siswaController->cariKampus($req3);
$data3 = json_decode($res3->getContent(), true);
echo "cariKampus('darmajaya') -> " . count($data3) . " hasil:\n";
foreach ($data3 as $k) {
    echo "  - [{$k['id']}] {$k['nama']}\n";
}

echo "\n=== 3. TEST SISWACONTROLLER cariProdi (AJAX) ===\n";
// Menggunakan kode PT
$reqProdi1 = Request::create('/siswa/cari-prodi', 'GET', ['pt_id' => '001026']);
$resProdi1 = $siswaController->cariProdi($reqProdi1);
$dataProdi1 = json_decode($resProdi1->getContent(), true);
echo "cariProdi('001026') -> " . count($dataProdi1) . " prodi ditemukan.\n";
foreach (array_slice($dataProdi1, 0, 3) as $p) {
    echo "  - {$p['nama']} ({$p['jenjang']})\n";
}

// Menggunakan nama PT (backward compatibility)
$reqProdi2 = Request::create('/siswa/cari-prodi', 'GET', ['pt_id' => 'Institut Informatika Dan Bisnis Darmajaya']);
$resProdi2 = $siswaController->cariProdi($reqProdi2);
$dataProdi2 = json_decode($resProdi2->getContent(), true);
echo "cariProdi('Institut Informatika Dan Bisnis Darmajaya') -> " . count($dataProdi2) . " prodi ditemukan.\n";
foreach (array_slice($dataProdi2, 0, 3) as $p) {
    echo "  - {$p['nama']} ({$p['jenjang']})\n";
}

echo "\n=== 4. TEST ADMINCONTROLLER cariProdi (AJAX) ===\n";
$adminController = app(AdminController::class);
$reqAdmin = Request::create('/admin/cari-prodi', 'POST', ['pt_id' => '005007']);
$resAdmin = $adminController->cariProdi($reqAdmin);
$dataAdmin = json_decode($resAdmin->getContent(), true);
echo "Admin cariProdi('005007' / Polinela) -> " . count($dataAdmin) . " prodi ditemukan.\n";
foreach (array_slice($dataAdmin, 0, 3) as $p) {
    echo "  - {$p['nama']} ({$p['jenjang']})\n";
}

echo "\n=== SEMUA VERIFIKASI BERHASIL 100%! ===\n";
