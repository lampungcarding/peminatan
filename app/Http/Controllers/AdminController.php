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
     * Scoping query User (siswa) jika user yang login adalah Guru BK.
     */
    protected function applySiswaScope($query)
    {
        $user = auth()->user();
        if ($user && $user->isGuruBk()) {
            $kelasBinaan = $user->kelas_binaan_array;
            $query->whereIn('kelas', $kelasBinaan);
        }
        return $query;
    }

    /**
     * Scoping query relasi ke User (CareerResult / PilihanSetelahLulus) jika login sebagai Guru BK.
     */
    protected function applyUserRelationScope($query)
    {
        $user = auth()->user();
        if ($user && $user->isGuruBk()) {
            $kelasBinaan = $user->kelas_binaan_array;
            $query->whereHas('user', function ($q) use ($kelasBinaan) {
                $q->whereIn('kelas', $kelasBinaan);
            });
        }
        return $query;
    }

    /**
     * Dapatkan daftar kelas yang relevan (semua kelas untuk Admin, atau kelas binaan untuk Guru BK).
     */
    protected function getKelasListForUser()
    {
        $user = auth()->user();
        $query = User::where('role', 'siswa')->whereNotNull('kelas');

        if ($user && $user->isGuruBk()) {
            $query->whereIn('kelas', $user->kelas_binaan_array);
        }

        return $query->distinct()->pluck('kelas')->sort()->values();
    }

    /**
     * Dashboard Admin / Guru BK — Statistik komprehensif.
     */
    public function dashboard(Request $request)
    {
        // 1. Stat cards
        $siswaQuery = $this->applySiswaScope(User::where('role', 'siswa'));
        $totalSiswa = (clone $siswaQuery)->count();

        $totalTesSelesai = $this->applyUserRelationScope(CareerResult::query())->count();
        $totalBelumTes = max(0, $totalSiswa - $totalTesSelesai);

        $totalSubmitRencana = $this->applyUserRelationScope(PilihanSetelahLulus::query())->count();
        $totalBelumRencana = max(0, $totalSiswa - $totalSubmitRencana);

        // Siswa yang datanya lengkap
        $totalDataLengkap = (clone $siswaQuery)
            ->has('careerResult')
            ->has('pilihanSetelahLulus')
            ->count();

        // Rincian Rencana
        $totalKuliah = $this->applyUserRelationScope(PilihanSetelahLulus::where('rencana', 'kuliah'))->count();
        $totalBekerja = $this->applyUserRelationScope(PilihanSetelahLulus::where('rencana', 'bekerja'))->count();
        $totalWirausaha = $this->applyUserRelationScope(PilihanSetelahLulus::where('rencana', 'berwirausaha'))->count();

        $pctKuliah = $totalSiswa > 0 ? round(($totalKuliah / $totalSiswa) * 100) : 0;
        $pctBekerja = $totalSiswa > 0 ? round(($totalBekerja / $totalSiswa) * 100) : 0;
        $pctWirausaha = $totalSiswa > 0 ? round(($totalWirausaha / $totalSiswa) * 100) : 0;
        $pctBelumRencana = $totalSiswa > 0 ? round(($totalBelumRencana / $totalSiswa) * 100) : 0;

        // 2. Statistik Distribusi RIASEC Dominan
        $riasecDistribution = [
            'Realistic' => $this->applyUserRelationScope(CareerResult::where('dominant_type', 'Realistic'))->count(),
            'Investigative' => $this->applyUserRelationScope(CareerResult::where('dominant_type', 'Investigative'))->count(),
            'Artistic' => $this->applyUserRelationScope(CareerResult::where('dominant_type', 'Artistic'))->count(),
            'Social' => $this->applyUserRelationScope(CareerResult::where('dominant_type', 'Social'))->count(),
            'Enterprising' => $this->applyUserRelationScope(CareerResult::where('dominant_type', 'Enterprising'))->count(),
            'Conventional' => $this->applyUserRelationScope(CareerResult::where('dominant_type', 'Conventional'))->count(),
        ];

        // 3. Tren 6 bulan terakhir
        $months = [];
        $chartKuliah = [];
        $chartBekerja = [];
        $chartWirausaha = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            $months[] = $monthDate->translatedFormat('M Y');

            $chartKuliah[] = $this->applyUserRelationScope(
                PilihanSetelahLulus::where('rencana', 'kuliah')->where('submitted_at', 'like', $monthKey . '%')
            )->count();

            $chartBekerja[] = $this->applyUserRelationScope(
                PilihanSetelahLulus::where('rencana', 'bekerja')->where('submitted_at', 'like', $monthKey . '%')
            )->count();

            $chartWirausaha[] = $this->applyUserRelationScope(
                PilihanSetelahLulus::where('rencana', 'berwirausaha')->where('submitted_at', 'like', $monthKey . '%')
            )->count();
        }

        // 4. Data Rencana Terbaru untuk Tabel Dashboard
        $pilihanTerbaru = $this->applyUserRelationScope(
            PilihanSetelahLulus::with(['user.careerResult'])->orderBy('submitted_at', 'desc')
        )->limit(8)->get();

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
        $query = $this->applySiswaScope(User::where('role', 'siswa')->with(['pilihanSetelahLulus', 'careerResult']));

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

        $kelasList = $this->getKelasListForUser();

        $siswaBase = $this->applySiswaScope(User::where('role', 'siswa'));
        $totalSiswa = (clone $siswaBase)->count();
        $totalTes = $this->applyUserRelationScope(CareerResult::query())->count();
        $totalRencana = $this->applyUserRelationScope(PilihanSetelahLulus::query())->count();

        return view('admin.siswa', compact(
            'siswaList',
            'kelasList',
            'totalSiswa',
            'totalTes',
            'totalRencana'
        ));
    }

    /**
     * Simpan Siswa Baru (CRUD Siswa).
     */
    public function siswaStore(Request $request)
    {
        $allowedKelas = $this->getKelasListForUser();

        $rules = [
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:users,nisn',
            'kelas' => 'required|string|max:50',
            'nipd' => 'nullable|string|max:50',
            'jk' => 'nullable|in:L,P',
            'nik' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'dusun' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten_kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_hp' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
        ];

        if (auth()->user()->isGuruBk()) {
            $rules['kelas'] .= '|in:' . implode(',', $allowedKelas->toArray());
        }

        $request->validate($rules, [
            'nisn.unique' => 'NISN sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'kelas.in' => 'Kelas yang dipilih tidak berada dalam lingkup binaan Anda.',
        ]);

        $email = $request->filled('email')
            ? $request->email
            : $request->nisn . '@sekolah.id';

        $password = $request->filled('password')
            ? Hash::make($request->password)
            : Hash::make($request->nisn ?: 'password');

        User::create([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'email' => $email,
            'password' => $password,
            'role' => 'siswa',
            'kelas' => $request->kelas,
            'nipd' => $request->nipd,
            'jk' => $request->jk,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'dusun' => $request->dusun,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten_kota' => $request->kabupaten_kota ?: 'Kota Bandar Lampung',
            'kode_pos' => $request->kode_pos,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->route('admin.siswa')->with('success', "Siswa '{$request->name}' ({$request->kelas}) berhasil ditambahkan.");
    }

    /**
     * Update Data Siswa (CRUD Siswa).
     */
    public function siswaUpdate(Request $request, $id)
    {
        $siswa = $this->applySiswaScope(User::where('role', 'siswa'))->findOrFail($id);
        $allowedKelas = $this->getKelasListForUser();

        $rules = [
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:users,nisn,' . $id,
            'kelas' => 'required|string|max:50',
            'nipd' => 'nullable|string|max:50',
            'jk' => 'nullable|in:L,P',
            'nik' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'dusun' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten_kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_hp' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ];

        if (auth()->user()->isGuruBk()) {
            $rules['kelas'] .= '|in:' . implode(',', $allowedKelas->toArray());
        }

        $request->validate($rules, [
            'nisn.unique' => 'NISN sudah terdaftar pada siswa lain.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'kelas.in' => 'Kelas yang dipilih tidak berada dalam lingkup binaan Anda.',
        ]);

        $data = [
            'name' => $request->name,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'nipd' => $request->nipd,
            'jk' => $request->jk,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'dusun' => $request->dusun,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten_kota' => $request->kabupaten_kota ?: 'Kota Bandar Lampung',
            'kode_pos' => $request->kode_pos,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
        ];

        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);

        return redirect()->back()->with('success', "Data siswa '{$siswa->name}' berhasil diperbarui.");
    }

    /**
     * Hapus Siswa (CRUD Siswa).
     */
    public function siswaDestroy($id)
    {
        $siswa = $this->applySiswaScope(User::where('role', 'siswa'))->findOrFail($id);
        $nama = $siswa->name;

        // Hapus relasi data tes dan pilihan yang terkait
        $siswa->careerAnswers()->delete();
        $siswa->careerResult()->delete();
        $siswa->pilihanSetelahLulus()->delete();
        $siswa->delete();

        return redirect()->back()->with('success', "Data siswa '{$nama}' berhasil dihapus dari sistem.");
    }

    /**
     * Halaman Hasil Tes Minat Karier (RIASEC).
     */
    public function hasilTes(Request $request)
    {
        $query = $this->applyUserRelationScope(CareerResult::with(['user.pilihanSetelahLulus']));

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

        $kelasList = $this->getKelasListForUser();

        $dominantTypes = ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'];

        return view('admin.hasil_tes', compact('hasilList', 'kelasList', 'dominantTypes'));
    }

    /**
     * Halaman Rencana Siswa Setelah Lulus (Kuliah / Bekerja / Wirausaha).
     */
    public function rencanaSiswa(Request $request)
    {
        $query = $this->applyUserRelationScope(PilihanSetelahLulus::with(['user.careerResult']));

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
     * Halaman Pengelolaan Akun Guru BK & Mapping Kelas Binaan.
     */
    public function guruBkIndex(Request $request)
    {
        $guruBkList = User::where('role', 'guru_bk')->orderBy('name')->get();

        $availableClasses = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->distinct()
            ->pluck('kelas')
            ->sort()
            ->values();

        $classCounts = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->groupBy('kelas')
            ->selectRaw('kelas, count(*) as count')
            ->pluck('count', 'kelas');

        $totalSiswaSekolah = User::where('role', 'siswa')->count();

        return view('admin.guru_bk', compact('guruBkList', 'availableClasses', 'classCounts', 'totalSiswaSekolah'));
    }

    /**
     * Simpan Akun Guru BK Baru.
     */
    public function guruBkStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'binaan_kelas' => 'nullable|array',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru_bk',
            'binaan_kelas' => $request->binaan_kelas ?? [],
        ]);

        return redirect()->route('admin.guru-bk')->with('success', 'Akun Guru BK berhasil dibuat.');
    }

    /**
     * Update Akun Guru BK & Class Mapping.
     */
    public function guruBkUpdate(Request $request, $id)
    {
        $user = User::where('role', 'guru_bk')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'binaan_kelas' => 'nullable|array',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'binaan_kelas' => $request->binaan_kelas ?? [],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.guru-bk')->with('success', 'Data Guru BK berhasil diperbarui.');
    }

    /**
     * Hapus Akun Guru BK.
     */
    public function guruBkDestroy($id)
    {
        $user = User::where('role', 'guru_bk')->findOrFail($id);
        $nama = $user->name;
        $user->delete();

        return redirect()->route('admin.guru-bk')->with('success', "Akun Guru BK '{$nama}' berhasil dihapus.");
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
     * AJAX pencarian daftar prodi berdasarkan perguruan tinggi untuk panel admin.
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
     * Halaman Laporan & Ekspor Data Lengkap Siswa.
     */
    public function laporan(Request $request)
    {
        $kelasList = $this->getKelasListForUser();

        $siswaBase = $this->applySiswaScope(User::where('role', 'siswa'));
        $totalSiswa = (clone $siswaBase)->count();
        $totalTes = $this->applyUserRelationScope(CareerResult::query())->count();
        $totalRencana = $this->applyUserRelationScope(PilihanSetelahLulus::query())->count();
        $totalLengkap = (clone $siswaBase)->has('careerResult')->has('pilihanSetelahLulus')->count();

        return view('admin.laporan', compact('kelasList', 'totalSiswa', 'totalTes', 'totalRencana', 'totalLengkap'));
    }

    /**
     * Halaman Cetak Form Rekapitulasi Sekolah (Format Blanko / Form Fisik Sekolah Sesuai Blanko).
     */
    public function cetakLaporan(Request $request)
    {
        $kelasList = $this->getKelasListForUser();
        $selectedKelas = $request->get('kelas', $kelasList->first() ?? 'Semua');
        $tahunLulus = $request->get('tahun_lulus', 'TAHUN 2025');
        $mode = $request->get('mode', 'isi'); // 'isi' atau 'kosong'

        $query = $this->applySiswaScope(User::where('role', 'siswa')->with('pilihanSetelahLulus'));

        if ($selectedKelas && $selectedKelas !== 'Semua') {
            $query->where('kelas', $selectedKelas);
        }

        $students = $query->orderBy('name')->get();

        $countL = $students->where('jk', 'L')->count();
        $countP = $students->where('jk', 'P')->count();
        $totalCount = $students->count();

        $currentUser = auth()->user();
        $namaGuruBk = $currentUser->isGuruBk() ? $currentUser->name : 'Guru Bimbingan Konseling';

        return view('admin.cetak_laporan', compact(
            'kelasList',
            'selectedKelas',
            'tahunLulus',
            'mode',
            'students',
            'countL',
            'countP',
            'totalCount',
            'namaGuruBk'
        ));
    }

    /**
     * Export Komprehensif Seluruh Data Siswa (Excel/CSV UTF-8).
     */
    public function exportLaporan(Request $request)
    {
        $query = $this->applySiswaScope(User::where('role', 'siswa')->with(['pilihanSetelahLulus', 'careerResult']));

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

        $filename = 'laporan_lengkap_siswa_kelas_12_' . date('Ymd_His') . '.csv';

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
                'NIPD',
                'L/P',
                'NISN',
                'NIK',
                'Kelas',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Alamat',
                'RT',
                'RW',
                'Dusun',
                'Kelurahan',
                'Kecamatan',
                'Kabupaten/Kota',
                'Kode Pos',
                'HP/WA',
                'Minat: Bekerja',
                'Minat: Melanjutkan',
                'Minat: Wirausaha',
                'Keterangan Minat Pilihan',
                'Perguruan Tinggi',
                'Program Studi',
                'Jenjang',
                'Akreditasi',
                'Bidang Pekerjaan',
                'Keterangan Pekerjaan',
                'Bidang Usaha',
                'Keterangan Usaha',
                'Status Tes RIASEC',
                'Holland Code',
                'Tipe Dominan',
                'Status Rencana',
                'Status Kelengkapan',
                'Waktu Submit Rencana',
            ]);

            foreach ($students as $index => $s) {
                $cr = $s->careerResult;
                $p = $s->pilihanSetelahLulus;

                $statusTes = $cr ? 'Sudah Tes' : 'Belum Tes';
                $statusRencana = $p ? 'Sudah Memilih' : 'Belum Memilih';
                $statusLengkap = ($cr && $p) ? 'Lengkap' : 'Belum Lengkap';

                // Kolom checklist minat
                $minatBekerja = ($p && $p->rencana === 'bekerja') ? 'V' : '-';
                $minatMelanjutkan = ($p && $p->rencana === 'kuliah') ? 'V' : '-';
                $minatWirausaha = ($p && $p->rencana === 'berwirausaha') ? 'V' : '-';

                // Keterangan minat ringkas
                $ketMinat = '-';
                if ($p) {
                    if ($p->rencana === 'kuliah') {
                        $ketMinat = ($p->nama_perguruan_tinggi ?: '') . ($p->nama_program_studi ? ' - ' . $p->nama_program_studi : '');
                    } elseif ($p->rencana === 'bekerja') {
                        $ketMinat = ($p->bidang_pekerjaan ?: '') . ($p->keterangan_pekerjaan ? ' (' . $p->keterangan_pekerjaan . ')' : '');
                    } elseif ($p->rencana === 'berwirausaha') {
                        $ketMinat = ($p->bidang_usaha ?: '') . ($p->keterangan_usaha ? ' (' . $p->keterangan_usaha . ')' : '');
                    }
                }

                $row = [
                    $index + 1,
                    $s->name,
                    $s->nipd ?? '-',
                    $s->jk ?? '-',
                    $s->nisn ?? '-',
                    $s->nik ?? '-',
                    $s->kelas ?? '-',
                    $s->tempat_lahir ?? '-',
                    $s->tanggal_lahir ?? '-',
                    $s->alamat ?? '-',
                    $s->rt ?? '-',
                    $s->rw ?? '-',
                    $s->dusun ?? '-',
                    $s->kelurahan ?? '-',
                    $s->kecamatan ?? '-',
                    $s->kabupaten_kota ?? 'Kota Bandar Lampung',
                    $s->kode_pos ?? '-',
                    $s->no_hp ?? '-',
                    $minatBekerja,
                    $minatMelanjutkan,
                    $minatWirausaha,
                    $ketMinat,
                    $p->nama_perguruan_tinggi ?? '-',
                    $p->nama_program_studi ?? '-',
                    $p->jenjang ?? '-',
                    $p->akreditasi ?? '-',
                    $p->bidang_pekerjaan ?? '-',
                    $p->keterangan_pekerjaan ?? '-',
                    $p->bidang_usaha ?? '-',
                    $p->keterangan_usaha ?? '-',
                    $statusTes,
                    $cr->holland_code ?? '-',
                    $cr->dominant_type ?? '-',
                    $statusRencana,
                    $statusLengkap,
                    $p && $p->submitted_at ? Carbon::parse($p->submitted_at)->format('d-m-Y H:i') : '-',
                ];

                fputcsv($file, array_map([$this, 'sanitizeCsvValue'], $row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Sanitasi nilai sel untuk mitigasi CSV Formula Injection (OWASP A03).
     * Mencegah karakter formula (=, +, -, @, tab, cr) dieksekusi oleh Microsoft Excel/Calc.
     */
    protected function sanitizeCsvValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        $str = (string) $value;
        $firstChar = substr($str, 0, 1);

        if (in_array($firstChar, ['=', '+', '-', '@', "\t", "\r"])) {
            return "'" . $str;
        }

        return $str;
    }

    /**
     * Reset pilihan rencana siswa (dengan validasi hak akses scope Guru BK).
     */
    public function resetPilihan($id)
    {
        $pilihan = $this->applyUserRelationScope(PilihanSetelahLulus::query())->findOrFail($id);
        $namaSiswa = $pilihan->user->name ?? 'Siswa';
        $pilihan->delete();

        return redirect()->back()->with('success', "Pilihan rencana {$namaSiswa} berhasil direset.");
    }

    /**
     * Reset hasil tes RIASEC siswa (dengan validasi hak akses scope Guru BK).
     */
    public function resetTes($userId)
    {
        $user = $this->applySiswaScope(User::where('role', 'siswa'))->findOrFail($userId);
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
