<?php

namespace App\Http\Controllers;

use App\Models\CareerQuestion;
use App\Models\CareerRecommendation;
use App\Models\CareerResult;
use App\Models\PilihanSetelahLulus;
use App\Models\User;
use App\Services\KipKuliahService;
use App\Services\RiasecService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(
        protected RiasecService $riasecService,
        protected KipKuliahService $kipService
    ) {}

    /**
     * Dashboard Admin — Statistik komprehensif Sistem Perencanaan Karier & Studi Siswa v2.
     */
    public function dashboard(Request $request)
    {
        // 1. Stat cards v2
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalTesSelesai = CareerResult::count();
        $totalBelumTes = max(0, $totalSiswa - $totalTesSelesai);

        $totalSubmitRencana = PilihanSetelahLulus::count();
        $totalBelumRencana = max(0, $totalSiswa - $totalSubmitRencana);

        // Siswa yang datanya lengkap (sudah tes RIASEC dan sudah submit rencana)
        $totalDataLengkap = User::where('role', 'siswa')
            ->has('careerResult')
            ->has('pilihanSetelahLulus')
            ->count();

        // Rincian Rencana
        $totalKuliah = PilihanSetelahLulus::where('rencana', 'kuliah')->count();
        $totalBekerja = PilihanSetelahLulus::where('rencana', 'bekerja')->count();
        $totalWirausaha = PilihanSetelahLulus::where('rencana', 'berwirausaha')->count();

        $pctKuliah = $totalSiswa > 0 ? round(($totalKuliah / $totalSiswa) * 100) : 0;
        $pctBekerja = $totalSiswa > 0 ? round(($totalBekerja / $totalSiswa) * 100) : 0;
        $pctWirausaha = $totalSiswa > 0 ? round(($totalWirausaha / $totalSiswa) * 100) : 0;
        $pctBelumRencana = $totalSiswa > 0 ? round(($totalBelumRencana / $totalSiswa) * 100) : 0;

        // 2. Statistik Distribusi RIASEC Dominan (6 Dimensi)
        $riasecDistribution = [
            'Realistic' => CareerResult::where('dominant_type', 'Realistic')->count(),
            'Investigative' => CareerResult::where('dominant_type', 'Investigative')->count(),
            'Artistic' => CareerResult::where('dominant_type', 'Artistic')->count(),
            'Social' => CareerResult::where('dominant_type', 'Social')->count(),
            'Enterprising' => CareerResult::where('dominant_type', 'Enterprising')->count(),
            'Conventional' => CareerResult::where('dominant_type', 'Conventional')->count(),
        ];

        // 3. Tren 6 bulan terakhir untuk Bar Chart Rencana
        $months = [];
        $chartKuliah = [];
        $chartBekerja = [];
        $chartWirausaha = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            $months[] = $monthDate->translatedFormat('M Y');

            $chartKuliah[] = PilihanSetelahLulus::where('rencana', 'kuliah')
                ->where('submitted_at', 'like', $monthKey . '%')
                ->count();

            $chartBekerja[] = PilihanSetelahLulus::where('rencana', 'bekerja')
                ->where('submitted_at', 'like', $monthKey . '%')
                ->count();

            $chartWirausaha[] = PilihanSetelahLulus::where('rencana', 'berwirausaha')
                ->where('submitted_at', 'like', $monthKey . '%')
                ->count();
        }

        // 4. Data Rencana Terbaru untuk Tabel Dashboard
        $pilihanTerbaru = PilihanSetelahLulus::with(['user.careerResult'])
            ->orderBy('submitted_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalTesSelesai',
            'totalBelumTes',
            'totalDataLengkap',
            'totalSubmitRencana',
            'totalBelumRencana',
            'totalKuliah',
            'totalBekerja',
            'totalWirausaha',
            'pctKuliah',
            'pctBekerja',
            'pctWirausaha',
            'pctBelumRencana',
            'riasecDistribution',
            'months',
            'chartKuliah',
            'chartBekerja',
            'chartWirausaha',
            'pilihanTerbaru'
        ));
    }

    /**
     * Halaman Data Siswa (Master Siswa Kelas 12 + Status Profil Lengkap).
     */
    public function dataSiswa(Request $request)
    {
        $query = User::where('role', 'siswa')->with(['pilihanSetelahLulus', 'careerResult']);

        // Filter Kelas
        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->where('kelas', $request->kelas);
        }

        // Filter Rencana
        if ($request->filled('rencana') && $request->rencana !== 'Semua') {
            if ($request->rencana === 'belum') {
                $query->doesntHave('pilihanSetelahLulus');
            } else {
                $query->whereHas('pilihanSetelahLulus', function ($q) use ($request) {
                    $q->where('rencana', $request->rencana);
                });
            }
        }

        // Filter Tipe RIASEC Dominan
        if ($request->filled('riasec') && $request->riasec !== 'Semua') {
            $query->whereHas('careerResult', function ($q) use ($request) {
                $q->where('dominant_type', $request->riasec);
            });
        }

        // Filter Status Tes
        if ($request->filled('status_tes') && $request->status_tes !== 'Semua') {
            if ($request->status_tes === 'sudah') {
                $query->has('careerResult');
            } elseif ($request->status_tes === 'belum') {
                $query->doesntHave('careerResult');
            }
        }

        // Filter Status Kelengkapan Data
        if ($request->filled('status_lengkap') && $request->status_lengkap !== 'Semua') {
            if ($request->status_lengkap === 'lengkap') {
                $query->has('careerResult')->has('pilihanSetelahLulus');
            } elseif ($request->status_lengkap === 'belum') {
                $query->where(function ($q) {
                    $q->doesntHave('careerResult')->orDoesntHave('pilihanSetelahLulus');
                });
            }
        }

        // Pencarian Nama / NISN
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        $siswaList = $query->orderBy('kelas')->orderBy('name')->paginate(20)->withQueryString();

        $kelasList = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->distinct()
            ->pluck('kelas')
            ->sort()
            ->values();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalTes = CareerResult::count();
        $totalRencana = PilihanSetelahLulus::count();

        return view('admin.siswa', compact(
            'siswaList',
            'kelasList',
            'totalSiswa',
            'totalTes',
            'totalRencana'
        ));
    }

    /**
     * Halaman Hasil Tes Minat Karier (RIASEC).
     */
    public function hasilTes(Request $request)
    {
        $query = CareerResult::with(['user.pilihanSetelahLulus']);

        // Filter Kelas
        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter Dominan
        if ($request->filled('dominan') && $request->dominan !== 'Semua') {
            $query->where('dominant_type', $request->dominan);
        }

        // Pencarian Nama / NISN / Holland Code
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('holland_code', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('nisn', 'like', "%{$q}%");
                    });
            });
        }

        $hasilList = $query->orderBy('completed_at', 'desc')->paginate(20)->withQueryString();

        $kelasList = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->distinct()
            ->pluck('kelas')
            ->sort()
            ->values();

        $dominantTypes = ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'];

        return view('admin.hasil_tes', compact('hasilList', 'kelasList', 'dominantTypes'));
    }

    /**
     * Halaman Rencana Siswa Setelah Lulus (Kuliah / Bekerja / Wirausaha).
     */
    public function rencanaSiswa(Request $request)
    {
        $query = PilihanSetelahLulus::with(['user.careerResult']);

        if ($request->filled('rencana') && $request->rencana !== 'Semua') {
            $query->where('rencana', $request->rencana);
        }

        if ($request->filled('kampus') && $request->kampus !== 'Semua') {
            $query->where('nama_perguruan_tinggi', $request->kampus);
        }

        if ($request->filled('prodi') && $request->prodi !== 'Semua') {
            $query->where('nama_program_studi', $request->prodi);
        }

        if ($request->filled('jenjang') && $request->jenjang !== 'Semua') {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        $pilihan = $query->orderBy('submitted_at', 'desc')->paginate(15)->withQueryString();

        $kampusList = PilihanSetelahLulus::whereNotNull('nama_perguruan_tinggi')
            ->where('nama_perguruan_tinggi', '!=', '')
            ->distinct()
            ->pluck('nama_perguruan_tinggi')
            ->sort()
            ->values();

        $prodiList = PilihanSetelahLulus::whereNotNull('nama_program_studi')
            ->where('nama_program_studi', '!=', '')
            ->distinct()
            ->pluck('nama_program_studi')
            ->sort()
            ->values();

        $jenjangList = PilihanSetelahLulus::whereNotNull('jenjang')
            ->where('jenjang', '!=', '')
            ->distinct()
            ->pluck('jenjang')
            ->sort()
            ->values();

        return view('admin.rencana', compact('pilihan', 'kampusList', 'prodiList', 'jenjangList'));
    }

    /**
     * Halaman Pengelolaan Bank Pertanyaan Tes (RIASEC & Career Anchors).
     */
    public function pertanyaanTes(Request $request)
    {
        $questions = CareerQuestion::orderBy('order_num')->get();

        return view('admin.pertanyaan_tes', compact('questions'));
    }

    /**
     * Simpan / Tambah Pertanyaan Tes Baru.
     */
    public function simpanPertanyaan(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'section' => 'required|in:riasec,career_anchor',
            'type_riasec' => 'nullable|required_if:section,riasec|in:R,I,A,S,E,C',
            'type_anchor' => 'nullable|required_if:section,career_anchor|in:TF,GM,AU,SE,EC,SV,CH,LS',
            'order_num' => 'nullable|integer',
        ]);

        $maxOrder = CareerQuestion::max('order_num') ?? 0;

        CareerQuestion::create([
            'question' => $request->question,
            'section' => $request->section,
            'type_riasec' => $request->section === 'riasec' ? $request->type_riasec : null,
            'type_anchor' => $request->section === 'career_anchor' ? $request->type_anchor : null,
            'order_num' => $request->order_num ?? ($maxOrder + 1),
            'status' => 'active',
        ]);

        return redirect()->route('admin.pertanyaan-tes')->with('success', 'Pertanyaan baru berhasil ditambahkan.');
    }

    /**
     * Update Pertanyaan Tes.
     */
    public function updatePertanyaan(Request $request, $id)
    {
        $q = CareerQuestion::findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:1000',
            'section' => 'required|in:riasec,career_anchor',
            'type_riasec' => 'nullable|required_if:section,riasec|in:R,I,A,S,E,C',
            'type_anchor' => 'nullable|required_if:section,career_anchor|in:TF,GM,AU,SE,EC,SV,CH,LS',
            'status' => 'required|in:active,inactive',
        ]);

        $q->update([
            'question' => $request->question,
            'section' => $request->section,
            'type_riasec' => $request->section === 'riasec' ? $request->type_riasec : null,
            'type_anchor' => $request->section === 'career_anchor' ? $request->type_anchor : null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pertanyaan-tes')->with('success', "Pertanyaan #{$q->order_num} berhasil diperbarui.");
    }

    /**
     * Halaman Bank Rekomendasi Karier.
     */
    public function rekomendasi(Request $request)
    {
        $query = CareerRecommendation::query();

        if ($request->filled('riasec') && $request->riasec !== 'Semua') {
            $query->where('riasec_code', $request->riasec);
        }

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $recommendations = $query->orderBy('riasec_code')->orderBy('category')->paginate(25)->withQueryString();

        return view('admin.rekomendasi', compact('recommendations'));
    }

    /**
     * Halaman Penjelajah Data Kampus & Prodi (prodi.json / KIP Kuliah API).
     */
    public function kampusProdi(Request $request)
    {
        $keyword = $request->input('q', 'lampung');
        $kampusResults = [];

        if (strlen($keyword) >= 2) {
            $kampusResults = $this->kipService->cariPerguruanTinggi($keyword);
        }

        return view('admin.kampus_prodi', compact('keyword', 'kampusResults'));
    }

    /**
     * Halaman Laporan & Ekspor Data Lengkap Siswa.
     */
    public function laporan(Request $request)
    {
        $kelasList = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->distinct()
            ->pluck('kelas')
            ->sort()
            ->values();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalTes = CareerResult::count();
        $totalRencana = PilihanSetelahLulus::count();
        $totalLengkap = User::where('role', 'siswa')->has('careerResult')->has('pilihanSetelahLulus')->count();

        return view('admin.laporan', compact('kelasList', 'totalSiswa', 'totalTes', 'totalRencana', 'totalLengkap'));
    }

    /**
     * Export Komprehensif Seluruh Data Siswa (Excel/CSV UTF-8).
     */
    public function exportLaporan(Request $request)
    {
        $query = User::where('role', 'siswa')
            ->with(['pilihanSetelahLulus', 'careerResult']);

        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('rencana') && $request->rencana !== 'Semua') {
            if ($request->rencana === 'belum') {
                $query->doesntHave('pilihanSetelahLulus');
            } else {
                $query->whereHas('pilihanSetelahLulus', function ($q) use ($request) {
                    $q->where('rencana', $request->rencana);
                });
            }
        }

        if ($request->filled('riasec') && $request->riasec !== 'Semua') {
            $query->whereHas('careerResult', function ($q) use ($request) {
                $q->where('dominant_type', $request->riasec);
            });
        }

        $students = $query->orderBy('kelas')->orderBy('name')->get();

        $filename = 'laporan_karier_studi_siswa_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            // Header baris CSV
            fputcsv($file, [
                'No',
                'Nama Siswa',
                'NISN',
                'Kelas',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Status Tes',
                'Holland Code',
                'Tipe Dominan',
                'Skor Realistic (R)',
                'Skor Investigative (I)',
                'Skor Artistic (A)',
                'Skor Social (S)',
                'Skor Enterprising (E)',
                'Skor Conventional (C)',
                'Status Rencana',
                'Rencana Pilihan',
                'Perguruan Tinggi',
                'Program Studi',
                'Jenjang',
                'Akreditasi',
                'Bidang Pekerjaan',
                'Keterangan Pekerjaan',
                'Bidang Usaha',
                'Keterangan Usaha',
                'Status Kelengkapan',
                'Waktu Submit Rencana',
            ]);

            foreach ($students as $index => $s) {
                $cr = $s->careerResult;
                $p = $s->pilihanSetelahLulus;

                $statusTes = $cr ? 'Sudah Tes' : 'Belum Tes';
                $statusRencana = $p ? 'Sudah Memilih' : 'Belum Memilih';
                $statusLengkap = ($cr && $p) ? 'Lengkap' : 'Belum Lengkap';

                fputcsv($file, [
                    $index + 1,
                    $s->name,
                    $s->nisn ?? '-',
                    $s->kelas ?? '-',
                    $s->tempat_lahir ?? '-',
                    $s->tanggal_lahir ?? '-',
                    $statusTes,
                    $cr->holland_code ?? '-',
                    $cr->dominant_type ?? '-',
                    $cr->realistic_score ?? 0,
                    $cr->investigative_score ?? 0,
                    $cr->artistic_score ?? 0,
                    $cr->social_score ?? 0,
                    $cr->enterprising_score ?? 0,
                    $cr->conventional_score ?? 0,
                    $statusRencana,
                    $p ? ucfirst($p->rencana) : '-',
                    $p->nama_perguruan_tinggi ?? '-',
                    $p->nama_program_studi ?? '-',
                    $p->jenjang ?? '-',
                    $p->akreditasi ?? '-',
                    $p->bidang_pekerjaan ?? '-',
                    $p->keterangan_pekerjaan ?? '-',
                    $p->bidang_usaha ?? '-',
                    $p->keterangan_usaha ?? '-',
                    $statusLengkap,
                    $p && $p->submitted_at ? Carbon::parse($p->submitted_at)->format('d-m-Y H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Reset pilihan rencana siswa.
     */
    public function resetPilihan($id)
    {
        $pilihan = PilihanSetelahLulus::findOrFail($id);
        $namaSiswa = $pilihan->user->name ?? 'Siswa';
        $pilihan->delete();

        return redirect()->back()->with('success', "Pilihan rencana {$namaSiswa} berhasil direset.");
    }

    /**
     * Reset hasil tes RIASEC siswa (agar siswa dapat tes ulang).
     */
    public function resetTes($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->careerResult) {
            $user->careerResult->delete();
        }
        $user->careerAnswers()->delete();

        return redirect()->back()->with('success', "Hasil tes minat RIASEC {$user->name} berhasil direset.");
    }

    /**
     * Halaman Pengaturan Aplikasi.
     */
    public function pengaturan()
    {
        $settings = \App\Models\Setting::getDefaults();
        $adminUser = auth()->user();

        return view('admin.pengaturan', compact('settings', 'adminUser'));
    }

    /**
     * Simpan Pengaturan Umum.
     */
    public function simpanPengaturanUmum(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'nama_aplikasi' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:50',
            'batas_pengisian' => 'required|date',
            'status_pendataan' => 'required|in:buka,tutup',
            'pesan_pengumuman' => 'nullable|string',
        ]);

        \App\Models\Setting::set('nama_sekolah', $request->nama_sekolah);
        \App\Models\Setting::set('nama_aplikasi', $request->nama_aplikasi);
        \App\Models\Setting::set('tahun_ajaran', $request->tahun_ajaran);
        \App\Models\Setting::set('batas_pengisian', $request->batas_pengisian);
        \App\Models\Setting::set('status_pendataan', $request->status_pendataan);
        \App\Models\Setting::set('pesan_pengumuman', $request->pesan_pengumuman ?? '');

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan umum aplikasi berhasil disimpan.');
    }

    /**
     * Update Akun & Password Admin.
     */
    public function updatePasswordAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin = auth()->user();
        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('admin.pengaturan')->with('success', 'Profil dan akun admin berhasil diperbarui.');
    }

    /**
     * Bersihkan Cache Aplikasi.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect()->route('admin.pengaturan')->with('success', 'Cache aplikasi dan template blade berhasil dibersihkan.');
    }

    /**
     * Sinkronkan Ulang API KIP Kuliah.
     */
    public function syncKip()
    {
        $this->kipService->clearCache();
        $this->kipService->cariPerguruanTinggi('lampung');

        return redirect()->route('admin.pengaturan')->with('success', 'Cache dan sesi koneksi KIP Kuliah berhasil disinkronkan kembali.');
    }

    /**
     * Re-import Siswa dari KELAS 12.xlsx.
     */
    public function reimportExcel()
    {
        Artisan::call('import:siswa-excel');

        return redirect()->route('admin.pengaturan')->with('success', 'Data siswa berhasil diimpor ulang dari file KELAS 12.xlsx.');
    }
}
