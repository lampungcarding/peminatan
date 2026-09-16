<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TracerVokasiService
{
    protected string $baseUrl = 'https://tracervokasi.kemendikdasmen.go.id';

    /**
     * Ambil seluruh data perguruan tinggi dari kampus.txt.
     * Di-cache permanen di Laravel Cache agar pencarian instan (< 2ms).
     */
    public function loadAllCampuses(): array
    {
        return Cache::rememberForever('tracer_all_campuses_list', function () {
            $path = base_path('kampus.txt');
            if (!file_exists($path)) {
                Log::warning("File kampus.txt tidak ditemukan di: {$path}");
                return [];
            }

            $content = file_get_contents($path);
            preg_match_all('/<option\s+value=["\']([^"\']+)["\'][^>]*>([^<]+)<\/option>/i', $content, $matches, PREG_SET_ORDER);

            $campuses = [];
            foreach ($matches as $m) {
                $kode = trim($m[1]);
                $nama = trim($m[2]);

                if (empty($kode) || empty($nama) || str_contains($nama, '--- Pilih ---')) {
                    continue;
                }

                $campuses[] = [
                    'kode' => $kode,
                    'nama' => $nama,
                ];
            }

            return $campuses;
        });
    }

    /**
     * Cari perguruan tinggi berdasarkan keyword nama atau singkatan populer.
     * Pencarian dilakukan di memori/cache lokal sehingga instan tanpa membebani server luar.
     */
    public function cariPerguruanTinggi(string $keyword): array
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 2) {
            return [];
        }

        $all = $this->loadAllCampuses();
        $expanded = $this->resolveAcronym($keyword);
        $kwLower = strtolower($expanded);

        $exact = [];
        $startsWith = [];
        $contains = [];

        foreach ($all as $c) {
            $namaLower = strtolower($c['nama']);

            if ($namaLower === $kwLower) {
                $exact[] = $c;
            } elseif (str_starts_with($namaLower, $kwLower)) {
                $startsWith[] = $c;
            } elseif (str_contains($namaLower, $kwLower)) {
                $contains[] = $c;
            }
        }

        $merged = array_merge($exact, $startsWith, $contains);

        // Jika keyword singkatan belum menghasilkan, cari juga kata aslinya
        if (empty($merged) && $expanded !== $keyword) {
            $origLower = strtolower($keyword);
            foreach ($all as $c) {
                if (str_contains(strtolower($c['nama']), $origLower)) {
                    $merged[] = $c;
                }
            }
        }

        // Ambil maksimal 35 hasil teratas
        $results = [];
        $seen = [];
        foreach (array_slice($merged, 0, 35) as $item) {
            if (!isset($seen[$item['kode']])) {
                $seen[$item['kode']] = true;
                $results[] = [
                    'id' => $item['kode'],
                    'nama' => $item['nama'],
                    'kode' => $item['kode'],
                ];
            }
        }

        return $results;
    }

    /**
     * Autentikasi sesi ke Tracer Vokasi menggunakan NISN alumni.
     * Sesi di-cache selama 110 menit (masa aktif server adalah 120 menit).
     */
    protected function getAuthSession(): array
    {
        return Cache::remember('tracer_vokasi_auth_session', 6600, function () {
            try {
                $jar = new CookieJar();
                $client = new Client([
                    'cookies' => $jar,
                    'verify' => false,
                    'timeout' => 15,
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    ],
                ]);

                // 1. Ambil halaman login untuk CSRF Token
                $resLogin = $client->get($this->baseUrl . '/login');
                $loginHtml = (string) $resLogin->getBody();

                $token = null;
                if (preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $loginHtml, $m)) {
                    $token = $m[1];
                }

                if (!$token) {
                    Log::warning('Tracer Vokasi: Gagal mengekstrak CSRF token dari /login');
                    return [];
                }

                // 2. Login alumni menggunakan NISN
                $nisn = env('TRACER_VOKASI_NISN', '3068950080');
                $client->post($this->baseUrl . '/login/alumni', [
                    'headers' => [
                        'Referer' => $this->baseUrl . '/login',
                        'Origin' => $this->baseUrl,
                    ],
                    'form_params' => [
                        '_token' => $token,
                        'username' => $nisn,
                    ],
                ]);

                // 3. Ambil halaman kuesioner showform11 untuk token form aktif
                $resForm = $client->get($this->baseUrl . '/showform11');
                $formHtml = (string) $resForm->getBody();

                $formToken = $token;
                if (preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $formHtml, $mForm)) {
                    $formToken = $mForm[1];
                }

                return [
                    'token' => $formToken,
                    'jar' => $jar->toArray(),
                ];
            } catch (\Exception $e) {
                Log::warning('Tracer Vokasi: Gagal autentikasi sesi: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Ambil program studi berdasarkan Kode PT atau Nama Perguruan Tinggi.
     * Hasil disimpan dalam cache selama 7 hari agar instan pada pemanggilan berikutnya.
     */
    public function getProdiByPT(string $ptIdOrName): array
    {
        $ptIdentifier = trim($ptIdOrName);
        if (empty($ptIdentifier)) {
            return [];
        }

        // Resolusi apakah input adalah kode atau nama
        $kodePt = null;
        $namaPt = null;

        $allCampuses = $this->loadAllCampuses();

        // 1. Coba cari jika $ptIdentifier adalah kode eksak (misal '001026')
        foreach ($allCampuses as $c) {
            if ($c['kode'] === $ptIdentifier) {
                $kodePt = $c['kode'];
                $namaPt = $c['nama'];
                break;
            }
        }

        // 2. Jika bukan kode, cari berdasarkan kecocokan nama
        if (!$kodePt) {
            $lowerId = strtolower($ptIdentifier);
            foreach ($allCampuses as $c) {
                if (strtolower($c['nama']) === $lowerId) {
                    $kodePt = $c['kode'];
                    $namaPt = $c['nama'];
                    break;
                }
            }
        }

        // 3. Jika masih belum ditemukan, coba substring match
        if (!$kodePt) {
            $lowerId = strtolower($ptIdentifier);
            foreach ($allCampuses as $c) {
                if (str_contains(strtolower($c['nama']), $lowerId) || str_contains($lowerId, strtolower($c['nama']))) {
                    $kodePt = $c['kode'];
                    $namaPt = $c['nama'];
                    break;
                }
            }
        }

        if (!$kodePt) {
            return $this->fallbackProdi($ptIdentifier);
        }

        $cacheKey = 'tracer_vokasi_prodi_' . md5($kodePt);

        return Cache::remember($cacheKey, 86400 * 7, function () use ($kodePt, $namaPt, $ptIdentifier) {
            $session = $this->getAuthSession();

            // Refresh session jika kosong
            if (empty($session['token'])) {
                Cache::forget('tracer_vokasi_auth_session');
                $session = $this->getAuthSession();
            }

            if (empty($session['token'])) {
                return $this->fallbackProdi($namaPt ?: $ptIdentifier);
            }

            $jar = !empty($session['jar']) && is_array($session['jar'])
                ? new CookieJar(false, $session['jar'])
                : new CookieJar();

            $client = new Client([
                'cookies' => $jar,
                'verify' => false,
                'timeout' => 12,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => '*/*',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Referer' => $this->baseUrl . '/showform11',
                ],
            ]);

            $jenjangs = ['S1', 'D4', 'D3'];
            $allProdi = [];
            $seen = [];

            foreach ($jenjangs as $jenjang) {
                try {
                    $response = $client->post($this->baseUrl . '/api/getProdi', [
                        'form_params' => [
                            'value' => $kodePt,
                            'jenjang' => $jenjang,
                            '_token' => $session['token'],
                        ],
                    ]);

                    if ($response->getStatusCode() === 200) {
                        $html = (string) $response->getBody();
                        preg_match_all('/<option\s+value=[\'"]([^\'"]*)[\'"][^>]*>([^<]+)<\/option>/i', $html, $matches, PREG_SET_ORDER);

                        foreach ($matches as $m) {
                            $prodiId = trim($m[1]);
                            $rawText = trim($m[2]);

                            // Lewati option kosong atau option 'Lainnya'
                            if (empty($prodiId) || $prodiId === '30065' || str_contains($rawText, '--- Pilih')) {
                                continue;
                            }

                            $prodiName = $rawText;
                            $detectedJenjang = $jenjang;

                            // Jika format teks: "Nama Prodi (Jenjang)"
                            if (preg_match('/^(.*?)\s*\((.*?)\)$/', $rawText, $matchText)) {
                                $prodiName = trim($matchText[1]);
                                $detectedJenjang = trim($matchText[2]);
                            }

                            $key = strtolower($prodiName . '_' . $detectedJenjang);
                            if (!isset($seen[$key])) {
                                $seen[$key] = true;
                                $allProdi[] = [
                                    'id' => $prodiId,
                                    'nama' => $prodiName,
                                    'jenjang' => $detectedJenjang,
                                    'akreditasi' => '-',
                                    'nama_pt' => $namaPt ?: $ptIdentifier,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Tracer Vokasi: Error getProdi untuk kode {$kodePt} jenjang {$jenjang}: " . $e->getMessage());
                    // Jika token invalid/expired, bersihkan cache auth
                    if (str_contains($e->getMessage(), '419') || str_contains($e->getMessage(), '401')) {
                        Cache::forget('tracer_vokasi_auth_session');
                    }
                }
            }

            if (empty($allProdi)) {
                return $this->fallbackProdi($namaPt ?: $ptIdentifier);
            }

            usort($allProdi, fn($a, $b) => strcmp($a['nama'], $b['nama']));
            return $allProdi;
        });
    }

    /**
     * Resolusi singkatan nama perguruan tinggi populer di Indonesia.
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
            'darmajaya' => 'Institut Informatika Dan Bisnis Darmajaya',
            'malahayati' => 'Universitas Malahayati',
            'telkom' => 'Universitas Telkom',
            'binus' => 'Universitas Bina Nusantara',
            'ipb' => 'Institut Pertanian Bogor',
            'uin rfc' => 'Universitas Islam Negeri Raden Intan Lampung',
            'uin lampung' => 'Universitas Islam Negeri Raden Intan Lampung',
        ];

        $lower = strtolower(trim($kw));
        return $map[$lower] ?? $kw;
    }

    /**
     * Fallback prodi jika server Tracer Vokasi offline / tidak merespons.
     */
    protected function fallbackProdi(string $namaPt): array
    {
        $all = [
            ['nama_pt' => 'Universitas Lampung', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi' => 'Baik Sekali'],
            ['nama_pt' => 'Universitas Lampung', 'nama' => 'Ilmu Komputer', 'jenjang' => 'S1', 'akreditasi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama' => 'Pendidikan Dokter', 'jenjang' => 'S1', 'akreditasi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama' => 'Manajemen', 'jenjang' => 'S1', 'akreditasi' => 'Unggul'],
            ['nama_pt' => 'Universitas Lampung', 'nama' => 'Ilmu Hukum', 'jenjang' => 'S1', 'akreditasi' => 'Unggul'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi' => 'Baik Sekali'],
            ['nama_pt' => 'Institut Teknologi Sumatera', 'nama' => 'Teknik Elektro', 'jenjang' => 'S1', 'akreditasi' => 'Baik'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama' => 'Manajemen Informatika', 'jenjang' => 'D3', 'akreditasi' => 'Baik Sekali'],
            ['nama_pt' => 'Politeknik Negeri Lampung', 'nama' => 'Teknologi Rekayasa Perangkat Lunak', 'jenjang' => 'D4', 'akreditasi' => 'Baik'],
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'akreditasi' => 'A'],
            ['nama_pt' => 'Universitas Bandar Lampung', 'nama' => 'Informatika', 'jenjang' => 'S1', 'akreditasi' => 'A'],
            ['nama_pt' => 'Institut Informatika Dan Bisnis Darmajaya', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'akreditasi' => 'Unggul'],
            ['nama_pt' => 'Institut Informatika Dan Bisnis Darmajaya', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'akreditasi' => 'Unggul'],
            ['nama_pt' => 'Institut Informatika Dan Bisnis Darmajaya', 'nama' => 'Bisnis Digital', 'jenjang' => 'S1', 'akreditasi' => 'Baik Sekali'],
        ];

        $kw = strtolower($namaPt);
        $result = [];
        foreach ($all as $item) {
            if (str_contains(strtolower($item['nama_pt']), $kw) || str_contains($kw, strtolower($item['nama_pt']))) {
                $result[] = [
                    'id' => md5($item['nama'] . $item['jenjang']),
                    'nama' => $item['nama'],
                    'jenjang' => $item['jenjang'],
                    'akreditasi' => $item['akreditasi'],
                    'nama_pt' => $item['nama_pt'],
                ];
            }
        }

        return $result;
    }

    /**
     * Hapus semua cache Tracer Vokasi.
     */
    public function clearCache(): void
    {
        Cache::forget('tracer_all_campuses_list');
        Cache::forget('tracer_vokasi_auth_session');
    }
}
