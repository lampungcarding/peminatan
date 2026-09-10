<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CareerResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING PREREQUISITE REQUIREMENT (TES MINAT BEFORE RENCANA) ===\n\n";

// 1. Create a fresh test user without CareerResult
$newStudent = User::firstOrCreate(
    ['email' => 'student_notest@example.com'],
    [
        'name' => 'Siswa Belum Tes',
        'password' => bcrypt('password'),
        'role' => 'siswa',
        'kelas' => 'XII PPLG 1',
        'nisn' => '1122334455',
    ]
);

// Remove any existing career result or choice
CareerResult::where('user_id', $newStudent->id)->delete();
$newStudent->pilihanSetelahLulus()->delete();

// Login as new student
Auth::login($newStudent);

$controller = app(\App\Http\Controllers\SiswaController::class);

// Call rencana()
$response = $controller->rencana();

echo "Response Status Code: " . $response->getStatusCode() . "\n";
echo "Redirect Target URL: " . $response->getTargetUrl() . "\n";
echo "Session Warning Alert: " . session('warning') . "\n\n";

if ($response->isRedirect(route('tes.index')) && session()->has('warning')) {
    echo "✅ TEST PASSED: Student without interest test is strictly BLOCKED from accessing plan form and redirected to tes.index with warning alert!\n";
} else {
    echo "❌ TEST FAILED: Redirect was not enforced as expected.\n";
}

