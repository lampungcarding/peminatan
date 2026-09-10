<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CareerQuestion;
use App\Models\User;
use App\Services\RiasecService;

echo "=== TESTING NEW RIASEC QUESTION DISTRIBUTION & AVERAGE SCORE ALGORITHM ===\n\n";

// 1. Verify Question Distribution in Database
$counts = [
    'R' => CareerQuestion::where('section', 'riasec')->where('type_riasec', 'R')->count(),
    'I' => CareerQuestion::where('section', 'riasec')->where('type_riasec', 'I')->count(),
    'A' => CareerQuestion::where('section', 'riasec')->where('type_riasec', 'A')->count(),
    'S' => CareerQuestion::where('section', 'riasec')->where('type_riasec', 'S')->count(),
    'E' => CareerQuestion::where('section', 'riasec')->where('type_riasec', 'E')->count(),
    'C' => CareerQuestion::where('section', 'riasec')->where('type_riasec', 'C')->count(),
];

echo "Database Question Distribution:\n";
foreach ($counts as $dim => $c) {
    echo "- Dimension {$dim}: {$c} questions\n";
}
echo "Total RIASEC Questions: " . array_sum($counts) . "\n\n";

// Expected counts: R: 10, I: 5, A: 8, S: 8, E: 8, C: 9 (Total: 48)

// 2. Test Scoring Logic with Simulation User
$user = User::firstOrCreate(
    ['email' => 'test_riasec_sim@example.com'],
    [
        'name' => 'Fatjeri Test',
        'password' => bcrypt('password'),
        'role' => 'siswa',
        'kelas' => 'XII AKL 1',
        'nisn' => '9988776655',
    ]
);

$riasecQuestions = CareerQuestion::where('section', 'riasec')->get();
$anchorQuestions = CareerQuestion::where('section', 'career_anchor')->get();

$answers = [];
foreach ($riasecQuestions as $q) {
    // All I questions: 5 (Sangat Suka)
    // All R questions: 4 (Suka)
    // All other questions: 3 (Cukup)
    if ($q->type_riasec === 'I') {
        $answers[$q->id] = 5;
    } elseif ($q->type_riasec === 'R') {
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

echo "Simulation Results:\n";
echo "- Raw Sum R: {$result->realistic_score} (10 questions -> Avg: " . ($result->realistic_score / 10) . ")\n";
echo "- Raw Sum I: {$result->investigative_score} (5 questions -> Avg: " . ($result->investigative_score / 5) . ")\n";
echo "- Raw Sum A: {$result->artistic_score} (8 questions -> Avg: " . ($result->artistic_score / 8) . ")\n";
echo "- Raw Sum S: {$result->social_score} (8 questions -> Avg: " . ($result->social_score / 8) . ")\n";
echo "- Raw Sum E: {$result->enterprising_score} (8 questions -> Avg: " . ($result->enterprising_score / 8) . ")\n";
echo "- Raw Sum C: {$result->conventional_score} (9 questions -> Avg: " . ($result->conventional_score / 9) . ")\n\n";

echo "Calculated Dominant Type: {$result->dominant_type}\n";
echo "Calculated Holland Code: {$result->holland_code}\n";
echo "Recommended Execution Path: {$result->recommended_execution_path}\n\n";

if ($result->getDominantCodeAttribute() === 'I') {
    echo "✅ TEST PASSED: Investigative (I) correctly won Primary position based on Average Score (5.0 vs 4.0) despite having lower raw total sum (25 vs 40)!\n";
} else {
    echo "❌ TEST FAILED: Dominant code is {$result->getDominantCodeAttribute()}\n";
}

