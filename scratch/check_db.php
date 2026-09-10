<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$qCount = App\Models\CareerQuestion::count();
$rCount = App\Models\CareerRecommendation::count();
echo "Career Questions in DB: $qCount\n";
echo "Career Recommendations in DB: $rCount\n";

foreach (['R', 'I', 'A', 'S', 'E', 'C'] as $dim) {
    $c = App\Models\CareerQuestion::where('type_riasec', $dim)->count();
    echo "  Dimensi $dim: $c soal\n";
}
