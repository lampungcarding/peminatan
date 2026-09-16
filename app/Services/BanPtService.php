<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BanPtService
{
    protected string $baseUrl = 'https://sapto2.banpt.or.id';

    /**
     * Dapatkan CSRF token & cookies dari halaman utama BAN-PT SAPTO 2.0.
     * Di-cache selama 50 menit.
     */
    protected function getCsrfSession(): array
    {
        return Cache::remember('banpt_csrf_session', 3000, function () {
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

                $res = $client->get($this->baseUrl . '/');
                $html = (string) $res->getBody();

                if (preg_match('/name="_csrf_sapto"\s+value="([^"]+)"/', $html, $m)) {
                    return [
                        'token' => $m[1],
                        'jar' => $jar->toArray(),
                    ];
                }

                return [];
            } catch (\Exception $e) {
                Log::warning('BAN-PT: Gagal mengambil CSRF session: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Ambil data lengkap profil perguruan tinggi dan program studi beserta akreditasi dari BAN-PT.
     * Hasil di-cache selama 7 hari agar instan (< 2 ms).
     */
    public function getDetailAndProdi(string $ptNamaOrId): array
    {
        $keyword = trim($ptNamaOrId);
        if (empty($keyword)) {
            return ['campus' => null, 'prodi' => []];
        }

        $cacheKey = 'banpt_detail_prodi_' . md5(strtolower($keyword));

        return Cache::remember($cacheKey, 86400 * 7, function () use ($keyword) {
            $session = $this->getCsrfSession();
            if (empty($session['token'])) {
                Cache::forget('banpt_csrf_session');
                $session = $this->getCsrfSession();
            }

            if (empty($session['token'])) {
                return ['campus' => null, 'prodi' => []];
            }

            $jar = !empty($session['jar']) && is_array($session['jar'])
                ? new CookieJar(false, $session['jar'])
                : new CookieJar();

            $client = new Client([
                'cookies' => $jar,
                'verify' => false,
                'timeout' => 18,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ],
            ]);

            // 1. Query data_pt di BAN-PT untuk mendapatkan kode unik PT
            try {
                $resDataPt = $client->post($this->baseUrl . '/beranda/data_pt', [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Referer' => $this->baseUrl . '/',
                    ],
                    'form_params' => [
                        'q' => $keyword,
                        'key' => $session['token'],
                    ],
                ]);

                $json = json_decode((string) $resDataPt->getBody(), true);
                $ptList = $json['pt'] ?? [];

                if (empty($ptList)) {
                    return ['campus' => null, 'prodi' => []];
                }

                // Cari kecocokan kampus terbaik (prioritaskan exact match nama)
                $targetPt = null;
                foreach ($ptList as $pt) {
                    if (strcasecmp(trim($pt['nama']), $keyword) === 0) {
                        $targetPt = $pt;
                        break;
                    }
                }

                if (!$targetPt) {
                    foreach ($ptList as $pt) {
                        if (stripos($pt['nama'], $keyword) !== false || stripos($keyword, $pt['nama']) !== false) {
                            $targetPt = $pt;
                            break;
                        }
                    }
                }

                if (!$targetPt) {
                    $targetPt = $ptList[0];
                }

                $banptKode = $targetPt['kode'];

                // 2. Ambil detail_pt berdasarkan kode unik BAN-PT
                $resDetail = $client->get($this->baseUrl . "/beranda/detail_pt/{$banptKode}");
                $html = (string) $resDetail->getBody();

                // 3. Ekstraksi Metadata Profil Perguruan Tinggi
                $campusMeta = [
                    'nama' => $targetPt['nama'],
                    'kode_pt' => '',
                    'akreditasi_pt' => '-',
                    'no_sk' => '-',
                    'tgl_sk' => '-',
                    'alamat' => '-',
                    'kota' => '-',
                    'telepon' => '-',
                ];

                if (preg_match('/Kode PT:\s*<span>([^<]+)<\/span>/i', $html, $mKode)) {
                    $campusMeta['kode_pt'] = trim($mKode[1]);
                }
                if (preg_match('/btn-info[^>]*text-uppercase[^>]*>([^<]+)<\/div>/i', $html, $mAkredPt)) {
                    $campusMeta['akreditasi_pt'] = trim($mAkredPt[1]);
                }
                if (preg_match('/Nomor SK Akreditasi<\/label>[\s\S]*?<p[^>]*>:\s*([^<]+)<\/p>/i', $html, $mNoSk)) {
                    $campusMeta['no_sk'] = trim($mNoSk[1]);
                }
                if (preg_match('/Tanggal SK Akreditasi<\/label>[\s\S]*?<p[^>]*>:\s*([^<]+)<\/p>/i', $html, $mTglSk)) {
                    $campusMeta['tgl_sk'] = trim($mTglSk[1]);
                }
                if (preg_match('/Alamat<\/label>[\s\S]*?<p[^>]*>:\s*([^<]+)<\/p>/i', $html, $mAlamat)) {
                    $campusMeta['alamat'] = trim($mAlamat[1]);
                }
                if (preg_match('/Kota\/Kabupaten<\/label>[\s\S]*?<p[^>]*>:\s*([^<]+)<\/p>/i', $html, $mKota)) {
                    $campusMeta['kota'] = trim($mKota[1]);
                }
                if (preg_match('/Telepon<\/label>[\s\S]*?<p[^>]*>:\s*([^<]+)<\/p>/i', $html, $mTelp)) {
                    $campusMeta['telepon'] = trim($mTelp[1]);
                }

                // 4. Ekstraksi Daftar Program Studi & Filter Jenjang D1-D4 & S1
                preg_match('/<table[^>]*id="tbl-prodi-pt"[^>]*>([\s\S]*?)<\/table>/i', $html, $mTable);
                preg_match_all('/<tr[^>]*>([\s\S]*?)<\/tr>/i', $mTable[1] ?? '', $rows);

                $allowedJenjang = ['D1', 'D2', 'D3', 'D4', 'S1'];
                $prodis = [];
                $seen = [];

                foreach ($rows[0] as $r) {
                    if (preg_match('/<h6><a[^>]*>(.*?)<\/a><\/h6>/i', $r, $mName) &&
                        preg_match('/<div class="btn[^>]*>([^<]+)<\/div>/i', $r, $mAkred)) {

                        $full = trim($mName[1]);
                        $akred = trim($mAkred[1]);

                        $detectedJenjang = null;
                        $prodiNama = $full;

                        if (preg_match('/^(S1|D4|D3|D2|D1)\s+(.*)$/i', $full, $mJ)) {
                            $detectedJenjang = strtoupper($mJ[1]);
                            $prodiNama = trim($mJ[2]);
                        } elseif (preg_match('/^(Sarjana|Diploma\s+Tiga|Diploma\s+Empat)\s+(.*)$/i', $full, $mJ)) {
                            $jText = strtolower($mJ[1]);
                            $detectedJenjang = str_contains($jText, 'sarjana') ? 'S1' : (str_contains($jText, 'empat') ? 'D4' : 'D3');
                            $prodiNama = trim($mJ[2]);
                        }

                        // Filter hanya jenjang D1, D2, D3, D4, dan S1 (mengecualikan S2/S3/Spesialis)
                        if ($detectedJenjang && in_array($detectedJenjang, $allowedJenjang)) {
                            $key = strtolower($prodiNama . '_' . $detectedJenjang);
                            if (!isset($seen[$key])) {
                                $seen[$key] = true;
                                $prodis[] = [
                                    'id' => md5($prodiNama . $detectedJenjang),
                                    'nama' => $prodiNama,
                                    'jenjang' => $detectedJenjang,
                                    'akreditasi' => $akred,
                                    'nama_pt' => $targetPt['nama'],
                                ];
                            }
                        }
                    }
                }

                usort($prodis, fn($a, $b) => strcmp($a['nama'], $b['nama']));

                return [
                    'campus' => $campusMeta,
                    'prodi' => $prodis,
                ];
            } catch (\Exception $e) {
                Log::warning("BAN-PT: Gagal memuat data untuk '{$keyword}': " . $e->getMessage());
                Cache::forget('banpt_csrf_session');
                return ['campus' => null, 'prodi' => []];
            }
        });
    }

    /**
     * Ambil daftar program studi khusus D3/D4/S1 beserta akreditasi dari BAN-PT.
     */
    public function getProdiByPT(string $ptNamaOrId): array
    {
        $res = $this->getDetailAndProdi($ptNamaOrId);
        return $res['prodi'] ?? [];
    }

    /**
     * Ambil metadata profil kampus (akreditasi institusi, SK, alamat, kota, telepon) dari BAN-PT.
     */
    public function getCampusDetail(string $ptNamaOrId): ?array
    {
        $res = $this->getDetailAndProdi($ptNamaOrId);
        return $res['campus'] ?? null;
    }

    /**
     * Bersihkan cache BAN-PT.
     */
    public function clearCache(): void
    {
        Cache::forget('banpt_csrf_session');
    }
}
