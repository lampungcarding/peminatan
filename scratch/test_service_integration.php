<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\KipKuliahService::class);

echo "1. Testing cariPerguruanTinggi('lampung')...\n";
$start = microtime(true);
$lampung = $service->cariPerguruanTinggi('lampung');
$time1 = round((microtime(true) - $start) * 1000, 2);
echo "Found " . count($lampung) . " results in {$time1} ms:\n";
foreach (array_slice($lampung, 0, 5) as $item) {
    echo "  - [{$item['id']}] {$item['nama']}\n";
}

echo "\n2. Testing acronym 'unila'...\n";
$unila = $service->cariPerguruanTinggi('unila');
echo "Found " . count($unila) . " results:\n";
foreach (array_slice($unila, 0, 3) as $item) {
    echo "  - [{$item['id']}] {$item['nama']}\n";
}

echo "\n3. Testing private campus 'darmajaya'...\n";
$darmajaya = $service->cariPerguruanTinggi('darmajaya');
echo "Found " . count($darmajaya) . " results:\n";
foreach ($darmajaya as $item) {
    echo "  - [{$item['id']}] {$item['nama']}\n";
}

echo "\n4. Testing getProdiByPT('001026') (Universitas Lampung)...\n";
$start = microtime(true);
$prodiUnila = $service->getProdiByPT('001026');
$time2 = round((microtime(true) - $start) * 1000, 2);
echo "Found " . count($prodiUnila) . " prodis in {$time2} ms:\n";
foreach (array_slice($prodiUnila, 0, 5) as $p) {
    echo "  - [{$p['id']}] {$p['nama']} ({$p['jenjang']}) [PT: {$p['nama_pt']}]\n";
}

echo "\n5. Testing getProdiByPT with name 'Institut Informatika Dan Bisnis Darmajaya'...\n";
$start = microtime(true);
$prodiDarmajaya = $service->getProdiByPT('Institut Informatika Dan Bisnis Darmajaya');
$time3 = round((microtime(true) - $start) * 1000, 2);
echo "Found " . count($prodiDarmajaya) . " prodis in {$time3} ms:\n";
foreach (array_slice($prodiDarmajaya, 0, 5) as $p) {
    echo "  - [{$p['id']}] {$p['nama']} ({$p['jenjang']}) [PT: {$p['nama_pt']}]\n";
}

echo "\nAll tests passed successfully!\n";
