<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KipKuliahService
{
    protected string $baseUrl = 'https://kip-kuliah.kemdiktisaintek.go.id';

    /**
     * Dapatkan CSRF token dan cookies dari halaman KIP Kuliah.
     * Di-cache selama 1 jam agar request berikutnya instan.
     */
    protected function getSessionAndToken(): array
    {
        $cached = Cache::get('kip_kuliah_session_auth');
        if (!empty($cached['token'])) {
            return $cached;
        }

        try {
            $jar = new CookieJar();
            $client = new Client([
                'cookies' => $jar,
                'verify' => false,
                'connect_timeout' => 5,
                'timeout' => 8,
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ],
            ]);

            $response = $client->get($this->baseUrl . '/');
            $html = (string) $response->getBody();

            $token = null;
            if (preg_match('/_token:\s*["\']([^"\']+)["\']/', $html, $matches)) {
                $token = $matches[1];
            } elseif (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $html, $matches)) {
                $token = $matches[1];
            }

            if ($token) {
                $data = [
                    'token' => $token,
                    'jar' => $jar->toArray(),
                ];
                Cache::put('kip_kuliah_session_auth', $data, 3600);
                return $data;
            }

            return [];
        } catch (\Exception $e) {
            Log::warning('Gagal mengambil token KIP Kuliah: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Kirim query DataTables ke endpoint /prodijson.
     */
    public function queryProdijson(string $keyword, int $limit = 60): array
    {
        $cacheKey = 'kip_prodijson_query_' . md5(strtolower(trim($keyword)) . '_' . $limit);

        return Cache::remember($cacheKey, 1800, function () use ($keyword, $limit) {
            $session = $this->getSessionAndToken();
            if (empty($session['token'])) {
                // Hapus cache token dan coba refresh sekali lagi
                Cache::forget('kip_kuliah_session_auth');
                $session = $this->getSessionAndToken();
            }

            if (empty($session['token'])) {
                return $this->fallbackSearch($keyword);
            }

            $jar = !empty($session['jar']) && is_array($session['jar'])
                ? new CookieJar(false, $session['jar'])
                : new CookieJar();

            $client = new Client([
                'cookies' => $jar,
                'verify' => false,
                'connect_timeout' => 5,
                'timeout' => 10,
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'application/json, text/javascript, */*; q=0.01',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Referer' => $this->baseUrl . '/',
                ],
            ]);

            $columns = [
                ['data' => 'no', 'name' => '', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'nama_pt', 'name' => '', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'nama_prodi', 'name' => '', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'jenjang', 'name' => '', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'akreditasi_prodi', 'name' => '', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'options', 'name' => '', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ];

            $params = [
                '_token' => $session['token'],
                'draw' => '1',
                'columns' => $columns,
                'order' => [
                    ['column' => '1', 'dir' => 'asc']
                ],
                'start' => '0',
                'length' => (string) $limit,
                'search' => [
                    'value' => $keyword,
                    'regex' => 'false'
                ]
            ];

            try {
                $response = $client->post($this->baseUrl . '/prodijson', [
                    'form_params' => $params,
                ]);

                if ($response->getStatusCode() === 200) {
                    $json = json_decode((string) $response->getBody(), true);
                    return array_values($json['data'] ?? []);
                }

                return $this->fallbackSearch($keyword);
            } catch (\Exception $e) {
                Log::warning("Error query prodijson untuk '{$keyword}': " . $e->getMessage());
                // Jika error 419 (token expired), bersihkan cache token
                if (str_contains($e->getMessage(), '419')) {
                    Cache::forget('kip_kuliah_session_auth');
                }
                return $this->fallbackSearch($keyword);
            }
        });
    }

    /**
     * Cari perguruan tinggi unik berdasarkan keyword.
     */
    public function cariPerguruanTinggi(string $keyword): array
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 2) {
            return [];
        }

        // Resolusi singkatan umum kampus (UNILA, ITB, UGM, dll.)
        $expandedKeyword = $this->resolveAcronym($keyword);

        $rows = $this->queryProdijson($expandedKeyword, 120);

        $perguruanTinggi = [];
        $seen = [];

        foreach ($rows as $item) {
            $namaPt = trim($item['nama_pt'] ?? ($item['namapt'] ?? ''));
            if (empty($namaPt)) continue;

            if (!isset($seen[$namaPt])) {
                $seen[$namaPt] = true;
                $perguruanTinggi[] = [
                    'id' => $namaPt,
                    'nama' => $namaPt,
                ];
            }
        }

        // Jika keyword adalah singkatan dan belum ada hasil, coba fallback search
        if (empty($perguruanTinggi)) {
            $fallback = $this->fallbackSearch($keyword);
            foreach ($fallback as $item) {
                $namaPt = $item['nama_pt'] ?? '';
                if ($namaPt && !isset($seen[$namaPt])) {
                    $seen[$namaPt] = true;
                    $perguruanTinggi[] = [
                        'id' => $namaPt,
                        'nama' => $namaPt,
                    ];
                }
            }
        }

        usort($perguruanTinggi, fn($a, $b) => strcmp($a['nama'], $b['nama']));
        return array_values(array_slice($perguruanTinggi, 0, 30));
    }

    /**
     * Ambil program studi berdasarkan nama perguruan tinggi.
     */
    public function getProdiByPT(string $ptIdOrName): array
    {
        $ptName = trim($ptIdOrName);
        if (empty($ptName)) {
            return [];
        }

        // Query prodijson langsung dengan nama kampus untuk mendapatkan daftar lengkap prodi
        $rows = $this->queryProdijson($ptName, 200);

        $prodi = [];
        $seen = [];

        foreach ($rows as $item) {
            $rowPt = trim($item['nama_pt'] ?? ($item['namapt'] ?? ''));
            
            // Cocokkan nama PT (case-insensitive)
            if (strcasecmp($rowPt, $ptName) !== 0 && stripos($rowPt, $ptName) === false && stripos($ptName, $rowPt) === false) {
                continue;
            }

            $namaProdi = trim($item['nama_prodi'] ?? ($item['namaprodi'] ?? ''));
            $jenjang = trim($item['jenjang'] ?? '-');
            $akreditasi = trim($item['akreditasi_prodi'] ?? ($item['akreditasi'] ?? '-'));

            if (empty($namaProdi)) continue;

            $key = strtolower($namaProdi . '_' . $jenjang);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $prodi[] = [
                    'id' => md5($namaProdi . $jenjang),
                    'nama' => $namaProdi,
                    'jenjang' => $jenjang,
                    'akreditasi' => $akreditasi,
                    'nama_pt' => $rowPt ?: $ptName,
                ];
            }
        }

        // Fallback jika API KIP Kuliah sedang tidak mengembalikan prodi kampus tsb
        if (empty($prodi)) {
            $fallback = $this->fallbackProdi($ptName);
            $prodi = $fallback;
        }

        usort($prodi, fn($a, $b) => strcmp($a['nama'], $b['nama']));
        return array_values($prodi);
    }

    /**
     * Resolusi singkatan umum perguruan tinggi di Indonesia.
     */
    protected function resolveAcronym(string $kw): string
    {
        $map = [
            'unila' => 'Universitas Lampung',
            'itb' => 'Institut Teknologi Bandung',
            'ugm' => 'Universitas Gadjah Mada',
            'ui' => 'Universitas Indonesia',
            'undip' => 'Universitas Diponegoro',
            'its' => 'Institut Teknologi Sepuluh Nopember',
            'unair' => 'Universitas Airlangga',
            'ub' => 'Universitas Brawijaya',
            'unpad' => 'Universitas Padjadjaran',
            'uns' => 'Universitas Sebelas Maret',
            'upi' => 'Universitas Pendidikan Indonesia',
            'polinela' => 'Politeknik Negeri Lampung',
            'polban' => 'Politeknik Negeri Bandung',
            'itera' => 'Institut Teknologi Sumatera',
            'ubl' => 'Universitas Bandar Lampung',
        ];

        $lower = strtolower(trim($kw));
        return $map[$lower] ?? $kw;
    }

    /**
     * Fallback database jika server KIP Kuliah offline / down.
     */
    protected function fallbackSearch(string $keyword): array
    {
        $kw = strtolower(trim($keyword));
        $all = [
            // Politeknik Negeri Lampung (Lengkap 31 Prodi Resmi)
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Agribisnis Pangan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Agribisnis Peternakan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Akuntansi Bisnis Digital', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Akuntansi Perpajakan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Budidaya Perikanan', 'jenjang' => 'D3', 'akreditasi_prodi' => 'B'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Gizi Klinis', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Terakreditasi Pertama'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Hortikultura', 'jenjang' => 'D3', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Mekanisasi Pertanian', 'jenjang' => 'D3', 'akreditasi_prodi' => 'B'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Pengelolaan Agribisnis', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Pengelolaan Perhotelan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Pengembangan Produk Agroindustri', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Pengolahan Patiseri', 'jenjang' => 'D2', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Perikanan Tangkap', 'jenjang' => 'D3', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Produksi Media', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Terakreditasi Sementara'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Produksi dan Manajemen Industri Perkebunan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'B'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Produksi Ternak', 'jenjang' => 'D3', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Produksi Tanaman Pangan', 'jenjang' => 'D3', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Rekayasa Instrumentasi dan Otomasi', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Rekayasa Keamanan Siber', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Benih', 'jenjang' => 'D3', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Produksi Tanaman Perkebunan', 'jenjang' => 'D3', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Produksi Ternak', 'jenjang' => 'D4', 'akreditasi_prodi' => 'B'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Rekayasa Konstruksi Jalan dan Jembatan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Rekayasa Kimia Industri', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Rekayasa Perangkat Lunak', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Rekayasa Otomotif', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Pangan', 'jenjang' => 'D3', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Manajemen Informatika', 'jenjang' => 'D3', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknik Komputer', 'jenjang' => 'D3', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Teknologi Pembenihan Ikan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama_prodi' => 'Pengelolaan Sumberdaya Perairan', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Baik'],

            // Universitas Lampung
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Ilmu Komputer', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Teknik Elektro', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Teknik Mesin', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Teknik Sipil', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Pendidikan Dokter', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Farmasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Manajemen', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Akuntansi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Ilmu Hukum', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Ilmu Komunikasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama_prodi' => 'Hubungan Internasional', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],

            // Institut Teknologi Sumatera
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Sains Data', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Teknik Elektro', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Teknik Biomedis', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Teknik Sipil', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Teknik Geomatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Arsitektur', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama_prodi' => 'Perencanaan Wilayah dan Kota', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Baik Sekali'],

            // Universitas Bandar Lampung
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama_prodi' => 'Informatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama_prodi' => 'Teknik Sipil', 'jenjang' => 'S1', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama_prodi' => 'Manajemen', 'jenjang' => 'S1', 'akreditasi_prodi' => 'A'],
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama_prodi' => 'Ilmu Hukum', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],

            // Universitas Nasional Terkemuka
            ['nama_pt' => 'Institut Teknologi Bandung', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Institut Teknologi Bandung', 'nama_prodi' => 'Sistem dan Teknologi Informasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Gadjah Mada', 'nama_prodi' => 'Ilmu Komputer', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Gadjah Mada', 'nama_prodi' => 'Teknologi Informasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Indonesia', 'nama_prodi' => 'Ilmu Komputer', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Indonesia', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Politeknik Negeri Bandung', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'D4', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Diponegoro', 'nama_prodi' => 'Informatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
            ['nama_pt' => 'Universitas Brawijaya', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi_prodi' => 'Unggul'],
        ];

        return array_values(array_filter($all, function ($item) use ($kw) {
            return str_contains(strtolower($item['nama_pt']), $kw) || str_contains(strtolower($item['nama_prodi']), $kw);
        }));
    }

    /**
     * Fallback prodi jika server KIP Kuliah offline.
     */
    protected function fallbackProdi(string $namaPt): array
    {
        $all = $this->fallbackSearch($namaPt);
        $result = [];

        foreach ($all as $item) {
            $result[] = [
                'id' => md5($item['nama_prodi'] . $item['jenjang']),
                'nama' => $item['nama_prodi'],
                'jenjang' => $item['jenjang'],
                'akreditasi' => $item['akreditasi_prodi'],
                'nama_pt' => $item['nama_pt'],
            ];
        }

        return array_values($result);
    }

    /**
     * Clear cache KIP Kuliah.
     */
    public function clearCache(): void
    {
        Cache::forget('kip_kuliah_session_auth');
    }
}
