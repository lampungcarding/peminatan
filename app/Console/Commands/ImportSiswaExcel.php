<?php

namespace App\Console\Commands;

use App\Models\PilihanSetelahLulus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use ZipArchive;
use SimpleXMLElement;

class ImportSiswaExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:siswa-excel {--with-plans : Also generate realistic plan distribution matching sample mockup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data siswa dari file KELAS 12.xlsx ke tabel users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = base_path('KELAS 12.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("Membuka file Excel: {$filePath}");

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            $this->error("Gagal membuka file Excel sebagai ZipArchive.");
            return 1;
        }

        // 1. Baca sharedStrings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml) {
            $xml = new SimpleXMLElement($sharedStringsXml);
            $xml->registerXPathNamespace('ns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            foreach ($xml->xpath('//ns:si | //si') as $si) {
                $si->registerXPathNamespace('ns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                $tNodes = $si->xpath('.//ns:t | .//t');
                $text = '';
                foreach ($tNodes as $t) {
                    $text .= (string) $t;
                }
                $sharedStrings[] = $text;
            }
        }

        // 2. Baca sheet1
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetXml) {
            $this->error("Sheet1 tidak ditemukan dalam Excel.");
            $zip->close();
            return 1;
        }

        $xmlSheet = new SimpleXMLElement($sheetXml);
        $zip->close();
        $xmlSheet->registerXPathNamespace('ns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        $rows = $xmlSheet->xpath('//ns:row | //row');
        $this->info("Ditemukan " . count($rows) . " baris data (termasuk header).");

        $defaultPassword = Hash::make('password');
        $students = [];

        // Skip header row
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $row->registerXPathNamespace('ns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $cells = [];

            foreach ($row->xpath('.//ns:c | .//c') as $c) {
                $ref = (string) $c['r'];
                $col = preg_replace('/[0-9]/', '', $ref);
                $type = (string) $c['t'];
                $val = (string) ($c->v ?? ($c->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main')->v ?? ''));

                if ($type === 's' && is_numeric($val) && isset($sharedStrings[(int) $val])) {
                    $val = $sharedStrings[(int) $val];
                }

                $cells[$col] = trim($val);
            }

            $nama = $cells['B'] ?? '';
            $nisn = $cells['C'] ?? '';
            $kelas = $cells['D'] ?? '';
            $tempatLahir = $cells['E'] ?? '';
            $tanggalLahir = $cells['F'] ?? '';

            if (!empty($nama) && !empty($nisn)) {
                $students[] = [
                    'nama' => $nama,
                    'nisn' => $nisn,
                    'kelas' => $kelas,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                ];
            }
        }

        $this->info("Memproses " . count($students) . " siswa ke database...");

        $bar = $this->output->createProgressBar(count($students));
        $bar->start();

        $importedCount = 0;
        $createdUsers = [];

        foreach ($students as $data) {
            $user = User::updateOrCreate(
                ['nisn' => $data['nisn']],
                [
                    'name' => $data['nama'],
                    'email' => $data['nisn'] . '@sekolah.id',
                    'password' => $defaultPassword,
                    'role' => 'siswa',
                    'kelas' => $data['kelas'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                ]
            );

            $createdUsers[] = $user;
            $importedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Berhasil mengimpor {$importedCount} siswa.");

        // Pastikan akun Admin tetap ada
        User::updateOrCreate(
            ['email' => 'admin@sekolah.id'],
            [
                'name' => 'admin',
                'password' => $defaultPassword,
                'role' => 'admin',
                'nisn' => null,
            ]
        );
        $this->info("Akun Admin 'admin@sekolah.id' aktif.");

        // Generate realistic plan distribution only if explicitly requested
        if ($this->option('with-plans')) {
            $this->seedRealisticPlans($createdUsers);
        }

        return 0;
    }

    /**
     * Seed sample data plans matching the mockup sample (350 students submitted).
     */
    protected function seedRealisticPlans(array $users)
    {
        $this->info("Membuat 350 rencana siswa sampel sesuai mockup (210 Kuliah, 95 Bekerja, 45 Berwirausaha)...");

        // Clear existing plans to avoid duplicates
        PilihanSetelahLulus::truncate();

        // Target: 210 Kuliah, 95 Bekerja, 45 Berwirausaha = 350 total
        $kampusList = [
            [
                'pt' => 'Universitas Lampung',
                'pt_id' => '1001',
                'prodi' => 'Teknik Informatika',
                'prodi_id' => '55201',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik Sekali',
            ],
            [
                'pt' => 'Institut Teknologi Bandung',
                'pt_id' => '1002',
                'prodi' => 'Informatika',
                'prodi_id' => '55202',
                'jenjang' => 'S1',
                'akreditasi' => 'Unggul',
            ],
            [
                'pt' => 'Universitas Gadjah Mada',
                'pt_id' => '1003',
                'prodi' => 'Manajemen',
                'prodi_id' => '61201',
                'jenjang' => 'S1',
                'akreditasi' => 'A',
            ],
            [
                'pt' => 'Universitas Indonesia',
                'pt_id' => '1004',
                'prodi' => 'Sistem Informasi',
                'prodi_id' => '57201',
                'jenjang' => 'S1',
                'akreditasi' => 'A',
            ],
            [
                'pt' => 'Politeknik Negeri Bandung',
                'pt_id' => '1005',
                'prodi' => 'Teknik Mesin',
                'prodi_id' => '21401',
                'jenjang' => 'D4',
                'akreditasi' => 'Baik Sekali',
            ],
            [
                'pt' => 'Politeknik Negeri Lampung',
                'pt_id' => '1006',
                'prodi' => 'Manajemen Informatika',
                'prodi_id' => '57401',
                'jenjang' => 'D3',
                'akreditasi' => 'Baik Sekali',
            ],
            [
                'pt' => 'Universitas Diponegoro',
                'pt_id' => '1007',
                'prodi' => 'Teknik Sipil',
                'prodi_id' => '22201',
                'jenjang' => 'S1',
                'akreditasi' => 'Unggul',
            ],
            [
                'pt' => 'Institut Teknologi Sepuluh Nopember',
                'pt_id' => '1008',
                'prodi' => 'Teknik Elektro',
                'prodi_id' => '20201',
                'jenjang' => 'S1',
                'akreditasi' => 'Unggul',
            ],
            [
                'pt' => 'Universitas Brawijaya',
                'pt_id' => '1009',
                'prodi' => 'Ilmu Komunikasi',
                'prodi_id' => '70201',
                'jenjang' => 'S1',
                'akreditasi' => 'Unggul',
            ],
            [
                'pt' => 'Universitas Sebelas Maret',
                'pt_id' => '1010',
                'prodi' => 'Akuntansi',
                'prodi_id' => '62201',
                'jenjang' => 'S1',
                'akreditasi' => 'Unggul',
            ],
        ];

        // 6 months timeline: Nov 2024 to Apr 2025
        $months = [
            '2024-11' => ['kuliah' => 60, 'bekerja' => 30, 'berwirausaha' => 10],
            '2024-12' => ['kuliah' => 70, 'bekerja' => 38, 'berwirausaha' => 15],
            '2025-01' => ['kuliah' => 72, 'bekerja' => 40, 'berwirausaha' => 20],
            '2025-02' => ['kuliah' => 78, 'bekerja' => 45, 'berwirausaha' => 25],
            '2025-03' => ['kuliah' => 84, 'bekerja' => 50, 'berwirausaha' => 30],
            '2025-04' => ['kuliah' => 90, 'bekerja' => 55, 'berwirausaha' => 36],
        ];

        // Let's create exactly 350 submissions (210 Kuliah, 95 Bekerja, 45 Berwirausaha)
        $plansToCreate = [];

        for ($i = 0; $i < 210; $i++) {
            $plansToCreate[] = 'kuliah';
        }
        for ($i = 0; $i < 95; $i++) {
            $plansToCreate[] = 'bekerja';
        }
        for ($i = 0; $i < 45; $i++) {
            $plansToCreate[] = 'berwirausaha';
        }

        // Shuffle slightly while keeping first few prominent for the top table rows
        $targetUsers = array_slice($users, 0, 350);

        // Make specific first students match the exact sample table screenshot
        // Row 1: Ahmad Fauzan (or 1st student) -> Kuliah, Universitas Lampung, Teknik Informatika, S1, Baik Sekali, 12 Apr 2025 10:24
        // Row 2: -> Bekerja, 11 Apr 2025 14:32
        // Row 3: -> Berwirausaha, 10 Apr 2025 09:15
        // Row 4: -> Kuliah, ITB, Informatika, S1, Unggul, 9 Apr 2025 16:47
        // Row 5: -> Kuliah, UGM, Manajemen, S1, A, 8 Apr 2025 11:20
        // Row 6: -> Bekerja, 7 Apr 2025 13:05
        // Row 7: -> Kuliah, UI, Sistem Informasi, S1, A, 6 Apr 2025 15:33
        // Row 8: -> Berwirausaha, 5 Apr 2025 10:18
        // Row 9: -> Kuliah, Polban, Teknik Mesin, D4, Baik Sekali, 4 Apr 2025 09:42
        // Row 10: -> Bekerja, 3 Apr 2025 14:27

        $specificTopRows = [
            0 => ['rencana' => 'kuliah', 'pt_idx' => 0, 'days_ago' => 1, 'hour' => 10, 'min' => 24],
            1 => ['rencana' => 'bekerja', 'pt_idx' => null, 'days_ago' => 2, 'hour' => 14, 'min' => 32],
            2 => ['rencana' => 'berwirausaha', 'pt_idx' => null, 'days_ago' => 3, 'hour' => 9, 'min' => 15],
            3 => ['rencana' => 'kuliah', 'pt_idx' => 1, 'days_ago' => 4, 'hour' => 16, 'min' => 47],
            4 => ['rencana' => 'kuliah', 'pt_idx' => 2, 'days_ago' => 5, 'hour' => 11, 'min' => 20],
            5 => ['rencana' => 'bekerja', 'pt_idx' => null, 'days_ago' => 6, 'hour' => 13, 'min' => 5],
            6 => ['rencana' => 'kuliah', 'pt_idx' => 3, 'days_ago' => 7, 'hour' => 15, 'min' => 33],
            7 => ['rencana' => 'berwirausaha', 'pt_idx' => null, 'days_ago' => 8, 'hour' => 10, 'min' => 18],
            8 => ['rencana' => 'kuliah', 'pt_idx' => 4, 'days_ago' => 9, 'hour' => 9, 'min' => 42],
            9 => ['rencana' => 'bekerja', 'pt_idx' => null, 'days_ago' => 10, 'hour' => 14, 'min' => 27],
        ];

        // Track remaining count for 210, 95, 45
        // Specific uses: 5 kuliah, 3 bekerja, 2 berwirausaha
        $remainingKuliah = 210 - 5;
        $remainingBekerja = 95 - 3;
        $remainingWirausaha = 45 - 2;

        $restPlans = [];
        for ($i = 0; $i < $remainingKuliah; $i++) $restPlans[] = 'kuliah';
        for ($i = 0; $i < $remainingBekerja; $i++) $restPlans[] = 'bekerja';
        for ($i = 0; $i < $remainingWirausaha; $i++) $restPlans[] = 'berwirausaha';
        shuffle($restPlans);

        $now = Carbon::now();

        foreach ($targetUsers as $index => $user) {
            if (isset($specificTopRows[$index])) {
                $item = $specificTopRows[$index];
                $rencana = $item['rencana'];
                $submittedAt = $now->copy()->subDays($item['days_ago'])->setHour($item['hour'])->setMinute($item['min']);

                $ptData = null;
                if ($rencana === 'kuliah' && isset($item['pt_idx'])) {
                    $ptData = $kampusList[$item['pt_idx']];
                }
            } else {
                $rencana = array_pop($restPlans) ?? 'kuliah';
                // Distribute submitted dates across the last 180 days (6 months)
                $daysAgo = rand(11, 175);
                $submittedAt = $now->copy()->subDays($daysAgo)->setHour(rand(8, 17))->setMinute(rand(0, 59));

                $ptData = null;
                if ($rencana === 'kuliah') {
                    $ptData = $kampusList[array_rand($kampusList)];
                }
            }

            PilihanSetelahLulus::create([
                'user_id' => $user->id,
                'rencana' => $rencana,
                'perguruan_tinggi_id_external' => $ptData['pt_id'] ?? null,
                'nama_perguruan_tinggi' => $ptData['pt'] ?? null,
                'program_studi_id_external' => $ptData['prodi_id'] ?? null,
                'nama_program_studi' => $ptData['prodi'] ?? null,
                'jenjang' => $ptData['jenjang'] ?? null,
                'akreditasi' => $ptData['akreditasi'] ?? null,
                'submitted_at' => $submittedAt,
            ]);
        }

        $this->info("Rencana sampel berhasil dibuat: " . PilihanSetelahLulus::count() . " siswa telah mengisi.");
    }
}
