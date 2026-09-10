<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CareerQuestion;
use App\Models\User;
use App\Services\RiasecService;

echo "=== TESTING RIASEC PERCENTAGE CALCULATION & RANKING ===\n\n";

$user = User::firstOrCreate(
    ['email' => 'test_percentage_sim@example.com'],
    [
        'name' => 'Fatjeri Percentage Test',
        'password' => bcrypt('password'),
        'role' => 'siswa',
        'kelas' => 'XII AKL 1',
        'nisn' => '9988776644',
    ]
);

$riasecQuestions = CareerQuestion::where('section', 'riasec')->get();
$anchorQuestions = CareerQuestion::where('section', 'career_anchor')->get();

$answers = [];
foreach ($riasecQuestions as $q) {
    // All I questions: 5 (Sangat Suka) -> Raw = 25/25 (100%)
    // All R questions: 4 (Suka) -> Raw = 40/50 (80%)
    // All A questions: 4 (Suka) -> Raw = 32/40 (80%)
    // All C questions: 3 (Cukup) -> Raw = 27/45 (60%)
    // All S questions: 3 (Cukup) -> Raw = 24/40 (60%)
    // All E questions: 3 (Cukup) -> Raw = 24/40 (60%)
    if ($q->type_riasec === 'I') {
        $answers[$q->id] = 5;
    } elseif (in_array($q->type_riasec, ['R', 'A'])) {
        $answers[$q->id] = 4;
    } else {
        $answers[$q->id] = 3;
    }
}

foreach ($anchorQuestions as $q) {
    $answers[$q->id] = 4;
}

$service = new RiasecService();
$result = $service->processAndSaveAnswers($user, $answers);

$scoresMap = $result->scores_map;

echo "Percentage Calculations:\n";
foreach ($scoresMap as $code => $d) {
    echo "- Dimension {$code} ({$d['name']}): Raw={$d['score']}/{$d['max_score']} -> Percentage={$d['percentage']}%\n";
}

echo "\nDominant Type: {$result->dominant_type}\n";
echo "Holland Code: {$result->holland_code}\n";
echo "Execution Path: {$result->recommended_execution_path}\n\n";

if ($result->getDominantCodeAttribute() === 'I' && $result->holland_code === 'I-R-A') {
    echo "✅ TEST PASSED: Holland Code correctly ranked I (100%) > R (80%) = A (80%) -> 'I-R-A'!\n";
} else {
    echo "❌ TEST FAILED: Got Holland Code {$result->holland_code}\n";
}

