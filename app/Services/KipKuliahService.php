<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class KipKuliahService
{
    protected TracerVokasiService $tracerService;
    protected BanPtService $banPtService;

    public function __construct(TracerVokasiService $tracerService, BanPtService $banPtService)
    {
        $this->tracerService = $tracerService;
        $this->banPtService = $banPtService;
    }

    /**
     * Cari perguruan tinggi unik berdasarkan keyword.
     * Menggunakan basis data lengkap lokal (4.837 kampus) dari Tracer Vokasi/Kemdikbud.
     */
    public function cariPerguruanTinggi(string $keyword): array
    {
        return $this->tracerService->cariPerguruanTinggi($keyword);
    }

    /**
     * Ambil program studi berdasarkan ID / Nama Perguruan Tinggi.
     * Mengutamakan data resmi BAN-PT SAPTO 2.0 (lengkap dengan Akreditasi resmi).
     * Jika tidak ada / offline, otomatis fallback ke Tracer Vokasi.
     */
    public function getProdiByPT(string $ptIdOrName): array
    {
        $prodis = $this->banPtService->getProdiByPT($ptIdOrName);

        if (!empty($prodis)) {
            return $prodis;
        }

        return $this->tracerService->getProdiByPT($ptIdOrName);
    }

    /**
     * Ambil rincian data kampus (profil, akreditasi institusi, SK, alamat) beserta daftar prodi D1-D4/S1.
     */
    public function getDetailAndProdi(string $ptIdOrName): array
    {
        $data = $this->banPtService->getDetailAndProdi($ptIdOrName);

        // Jika daftar prodi di BAN-PT kosong, coba lengkapi dari Tracer Vokasi
        if (empty($data['prodi'])) {
            $data['prodi'] = $this->tracerService->getProdiByPT($ptIdOrName);
        }

        return $data;
    }

    /**
     * Clear cache BAN-PT & Tracer Vokasi.
     */
    public function clearCache(): void
    {
        $this->banPtService->clearCache();
        $this->tracerService->clearCache();
        Cache::forget('kip_kuliah_session_auth');
    }
}
