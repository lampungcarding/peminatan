<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\CareerQuestion;
use App\Services\RiasecService;

$user = User::where('role', 'siswa')->first();
if (!$user) {
    $user = User::create([
        'name' => 'A. Fatjeri',
        'email' => 'fatjeri@smk.test',
        'password' => bcrypt('password'),
        'kelas' => 'XII AKL 1',
        'role' => 'siswa'
    ]);
} else {
    $user->update(['kelas' => 'XII AKL 1']);
}

$riasecService = app(RiasecService::class);
$questions = CareerQuestion::all();
$answers = [];

foreach ($questions as $q) {
    if ($q->section === 'riasec') {
        $answers[$q->id] = match($q->type_riasec) {
            'I' => 5,
            'R' => 4,
            'A' => 3,
            default => 2
        };
    } else {
        $answers[$q->id] = ($q->type_anchor === 'SE') ? 5 : 2;
    }
}

$res = $riasecService->processAndSaveAnswers($user, $answers);
$recs = $riasecService->getRecommendationsForCode($res->holland_code, $user);

echo "================ SIMULASI TEST FATJERI (AKL - SE ANCHOR) ================\n";
echo "Siswa: " . $user->name . " (" . $user->kelas . ")\n";
echo "Holland Code: " . $res->holland_code . "\n";
echo "Dominant Anchor: " . $res->dominant_anchor . " (" . $res->dominant_anchor_name . ")\n";
echo "Recommended Path: " . $res->recommended_execution_path . "\n";
echo "Recommendation Title: " . $res->anchor_recommendation_title . "\n";
echo "Collaboration Narrative: \n";
$collab = json_decode($res->collaboration_narrative, true);
print_r($collab);

echo "\n--- PROFESI LINIER (KERJA LANGSUNG - AKL) ---\n";
foreach ($recs['profesi_linier'] as $p) {
    echo "- " . $p['name'] . ": " . $p['description'] . "\n";
}
echo "=========================================================================\n";

