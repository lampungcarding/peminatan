<?php

namespace App\Http\Controllers;

use App\Models\PilihanSetelahLulus;
use App\Services\KipKuliahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function __construct(
        protected KipKuliahService $kipService
    ) {}

    /**
     * Dashboard siswa — tampilkan status tes minat, rencana, dan kelengkapan.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $pilihan = $user->pilihanSetelahLulus;
        $careerResult = $user->careerResult;

        return view('siswa.dashboard', compact('user', 'pilihan', 'careerResult'));
    }

    /**
     * Form pilih rencana setelah lulus.
     */
    public function rencana()
    {
        $user = Auth::user();

        // Prasyarat: Siswa WAJIB menyelesaikan Tes Minat Karier terlebih dahulu!
        if (!$user->is_tes_selesai) {
            return redirect()->route('tes.index')
                ->with('warning', 'Kamu wajib mengikuti dan menyelesaikan Tes Minat Karier (Sesi 1 & Sesi 2) terlebih dahulu sebelum mengisi Rencana Setelah Lulus.');
        }

        // Jika sudah submit, redirect ke dashboard atau pilihan saya
        if ($user->pilihanSetelahLulus) {
            return redirect()->route('siswa.pilihan-saya')
                ->with('info', 'Kamu sudah mengisi rencana setelah lulus.');
        }

        // Cek status pendataan dari pengaturan
        if (\App\Models\Setting::get('status_pendataan') === 'tutup') {
            return redirect()->route('siswa.dashboard')
                ->with('warning', 'Pendataan rencana setelah lulus saat ini sedang ditutup oleh pihak sekolah.');
        }

        $pengumuman = \App\Models\Setting::get('pesan_pengumuman');
        $careerResult = $user->careerResult;

        return view('siswa.rencana', compact('user', 'pengumuman', 'careerResult'));
    }

    /**
     * Simpan rencana ke session dan redirect ke konfirmasi.
     */
    public function simpanRencana(Request $request)
    {
        $user = Auth::user();

        // Prasyarat: Siswa WAJIB menyelesaikan Tes Minat Karier terlebih dahulu!
        if (!$user->is_tes_selesai) {
            return redirect()->route('tes.index')
                ->with('warning', 'Kamu wajib mengikuti dan menyelesaikan Tes Minat Karier (Sesi 1 & Sesi 2) terlebih dahulu sebelum mengisi Rencana Setelah Lulus.');
        }

        if (\App\Models\Setting::get('status_pendataan') === 'tutup') {
            return redirect()->route('siswa.dashboard')
                ->with('warning', 'Pendataan rencana setelah lulus saat ini sedang ditutup oleh pihak sekolah.');
        }

        $request->validate([
            'rencana' => ['required', 'in:kuliah,bekerja,berwirausaha'],
            // Kuliah
            'perguruan_tinggi_id' => ['required_if:rencana,kuliah'],
            'nama_perguruan_tinggi' => ['required_if:rencana,kuliah'],
            'program_studi_id' => ['required_if:rencana,kuliah'],
            'nama_program_studi' => ['required_if:rencana,kuliah'],
            'jenjang' => ['required_if:rencana,kuliah'],
            'akreditasi' => ['required_if:rencana,kuliah'],
            // Bekerja
            'bidang_pekerjaan' => ['required_if:rencana,bekerja'],
            'keterangan_pekerjaan' => ['nullable', 'string', 'max:500'],
            // Berwirausaha
            'bidang_usaha' => ['required_if:rencana,berwirausaha'],
            'keterangan_usaha' => ['nullable', 'string', 'max:500'],
        ], [
            'rencana.required' => 'Pilih rencana setelah lulus.',
            'perguruan_tinggi_id.required_if' => 'Pilih perguruan tinggi.',
            'nama_perguruan_tinggi.required_if' => 'Pilih perguruan tinggi.',
            'program_studi_id.required_if' => 'Pilih program studi.',
            'nama_program_studi.required_if' => 'Pilih program studi.',
            'jenjang.required_if' => 'Jenjang wajib diisi.',
            'akreditasi.required_if' => 'Akreditasi wajib diisi.',
            'bidang_pekerjaan.required_if' => 'Pilih bidang pekerjaan yang diminati.',
            'bidang_usaha.required_if' => 'Pilih bidang usaha yang diminati.',
        ]);

        $data = [
            'rencana' => $request->rencana,
        ];

        if ($request->rencana === 'kuliah') {
            $data['perguruan_tinggi_id'] = $request->perguruan_tinggi_id;
            $data['nama_perguruan_tinggi'] = $request->nama_perguruan_tinggi;
            $data['program_studi_id'] = $request->program_studi_id;
            $data['nama_program_studi'] = $request->nama_program_studi;
            $data['jenjang'] = $request->jenjang;
            $data['akreditasi'] = $request->akreditasi;
        } elseif ($request->rencana === 'bekerja') {
            $data['bidang_pekerjaan'] = $request->bidang_pekerjaan;
            $data['keterangan_pekerjaan'] = $request->keterangan_pekerjaan;
        } elseif ($request->rencana === 'berwirausaha') {
            $data['bidang_usaha'] = $request->bidang_usaha;
            $data['keterangan_usaha'] = $request->keterangan_usaha;
        }

        // Simpan ke session untuk konfirmasi
        $request->session()->put('rencana_data', $data);

        return redirect()->route('siswa.konfirmasi');
    }

    /**
     * Cari kampus via KIP Kuliah API (AJAX).
     */
    public function cariKampus(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'min:2'],
        ]);

        $results = $this->kipService->cariPerguruanTinggi($request->keyword);

        return response()->json($results);
    }

    /**
     * Cari prodi berdasarkan kampus (AJAX).
     */
    public function cariProdi(Request $request)
    {
        $request->validate([
            'pt_id' => ['required', 'string'],
        ]);

        $results = $this->kipService->getProdiByPT($request->pt_id);

        return response()->json($results);
    }

    /**
     * Halaman konfirmasi sebelum submit.
     */
    public function konfirmasi(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_tes_selesai) {
            return redirect()->route('tes.index')
                ->with('warning', 'Kamu wajib mengikuti dan menyelesaikan Tes Minat Karier (Sesi 1 & Sesi 2) terlebih dahulu sebelum mengisi Rencana Setelah Lulus.');
        }

        $data = $request->session()->get('rencana_data');

        if (!$data) {
            return redirect()->route('siswa.rencana')
                ->with('warning', 'Silakan pilih rencana terlebih dahulu.');
        }

        $user = Auth::user();

        return view('siswa.konfirmasi', compact('user', 'data'));
    }

    /**
     * Submit final — simpan ke database.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_tes_selesai) {
            return redirect()->route('tes.index')
                ->with('warning', 'Kamu wajib mengikuti dan menyelesaikan Tes Minat Karier (Sesi 1 & Sesi 2) terlebih dahulu sebelum mengisi Rencana Setelah Lulus.');
        }

        $data = $request->session()->get('rencana_data');

        if (!$data) {
            return redirect()->route('siswa.rencana')
                ->with('warning', 'Silakan pilih rencana terlebih dahulu.');
        }

        // Cek apakah sudah ada pilihan
        if ($user->pilihanSetelahLulus) {
            return redirect()->route('siswa.pilihan-saya')
                ->with('info', 'Kamu sudah mengisi rencana setelah lulus.');
        }

        // Simpan ke database
        PilihanSetelahLulus::create([
            'user_id' => $user->id,
            'rencana' => $data['rencana'],
            'perguruan_tinggi_id_external' => $data['perguruan_tinggi_id'] ?? null,
            'nama_perguruan_tinggi' => $data['nama_perguruan_tinggi'] ?? null,
            'program_studi_id_external' => $data['program_studi_id'] ?? null,
            'nama_program_studi' => $data['nama_program_studi'] ?? null,
            'jenjang' => $data['jenjang'] ?? null,
            'akreditasi' => $data['akreditasi'] ?? null,
            'bidang_pekerjaan' => $data['bidang_pekerjaan'] ?? null,
            'keterangan_pekerjaan' => $data['keterangan_pekerjaan'] ?? null,
            'bidang_usaha' => $data['bidang_usaha'] ?? null,
            'keterangan_usaha' => $data['keterangan_usaha'] ?? null,
            'submitted_at' => now(),
        ]);

        // Hapus session
        $request->session()->forget('rencana_data');

        return redirect()->route('siswa.pilihan-saya')
            ->with('success', 'Rencana setelah lulus berhasil dikunci dan tersimpan!');
    }

    /**
     * Profil karier & studi siswa (Pilihan Saya).
     */
    public function pilihanSaya()
    {
        $user = Auth::user();
        $pilihan = $user->pilihanSetelahLulus;
        $careerResult = $user->careerResult;

        $riasecService = app(\App\Services\RiasecService::class);
        $recommendations = $careerResult ? $riasecService->getRecommendationsForCode($careerResult->holland_code) : [];

        return view('siswa.pilihan_saya', compact('user', 'pilihan', 'careerResult', 'recommendations'));
    }

    /**
     * Halaman profil data diri siswa.
     */
    public function profil()
    {
        $user = Auth::user();
        $careerResult = $user->careerResult;
        $pilihan = $user->pilihanSetelahLulus;

        return view('siswa.profil', compact('user', 'careerResult', 'pilihan'));
    }
}
