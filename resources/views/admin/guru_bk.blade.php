@extends('layouts.admin')

@section('title', 'Pengelolaan Guru BK & Mapping Kelas Binaan')

@section('content')
{{-- PAGE TITLE & ACTIONS --}}
<div class="page-title-row">
    <div>
        <h1>Pengelolaan Guru BK & Class Mapping</h1>
        <p>Kelola akun Bimbingan Konseling (BK) dan atur pembagian kelas binaan siswa secara terisolasi.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahGuruBk" style="background-color: #3157A4; border-color: #3157A4; border-radius: 8px; box-shadow: 0 2px 6px rgba(49, 87, 164, 0.25);">
            <i class="bi bi-person-plus-fill fs-6"></i>
            <span>Tambah Guru BK Baru</span>
        </button>
    </div>
</div>

{{-- ALERT NOTIFIKASI --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center gap-3 p-3 mb-4" role="alert" style="background-color: #F0FDF4; border-left: 4px solid #3F7D58 !important; border-radius: 8px; color: #166534;">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div class="flex-grow-1">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center gap-3 p-3 mb-4" role="alert" style="background-color: #FEF2F2; border-left: 4px solid #DC2626 !important; border-radius: 8px; color: #991B1B;">
        <i class="bi bi-exclamation-octagon-fill fs-5 text-danger"></i>
        <div class="flex-grow-1">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 p-3 mb-4" role="alert" style="background-color: #FEF2F2; border-left: 4px solid #DC2626 !important; border-radius: 8px; color: #991B1B;">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <strong>Terdapat beberapa kesalahan pengisian formulir:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- RINGKASAN STATISTIK (STAT CARDS) --}}
@php
    $allAssignedClasses = [];
    foreach($guruBkList as $g) {
        $allAssignedClasses = array_merge($allAssignedClasses, $g->kelas_binaan_array);
    }
    $uniqueAssigned = array_unique($allAssignedClasses);
    $totalClassesCount = count($availableClasses);
    $mappedClassesCount = count($uniqueAssigned);
    $persenKelas = $totalClassesCount > 0 ? round(($mappedClassesCount / $totalClassesCount) * 100) : 0;

    $totalSiswaCovered = 0;
    foreach($uniqueAssigned as $k) {
        $totalSiswaCovered += ($classCounts[$k] ?? 0);
    }
    $totalSiswaSekolah = $totalSiswaSekolah ?? 0;
    $persenSiswa = $totalSiswaSekolah > 0 ? round(($totalSiswaCovered / $totalSiswaSekolah) * 100) : 0;
@endphp

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background-color: #3157A4;">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Akun Guru BK</div>
                <div class="stat-value">{{ count($guruBkList) }} <span class="fs-6 fw-normal text-muted">Akun</span></div>
                <div class="stat-subtext">Konselor bimbingan konseling aktif</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background-color: #3F7D58;">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Cakupan Kelas Terpetakan</div>
                <div class="stat-value">{{ $mappedClassesCount }} <span class="fs-6 fw-normal text-muted">/ {{ $totalClassesCount }} Kelas</span></div>
                <div class="stat-subtext">{{ $persenKelas }}% kelas telah dibina oleh Guru BK</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background-color: #4338CA;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Siswa Dalam Binaan</div>
                <div class="stat-value">{{ number_format($totalSiswaCovered) }} <span class="fs-6 fw-normal text-muted">Siswa</span></div>
                <div class="stat-subtext">{{ $persenSiswa }}% dari {{ number_format($totalSiswaSekolah) }} siswa kelas 12</div>
            </div>
        </div>
    </div>
</div>

{{-- PANEL DAFTAR GURU BK --}}
<div class="panel-card">
    <div class="panel-header flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
        <div>
            <h2>Daftar Akun Guru BK & Penugasan Kelas</h2>
            <p>Setiap guru BK yang login memiliki dashboard dan akses data yang terisolasi sesuai kelas binaan masing-masing.</p>
        </div>
        <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
            <div class="input-group input-group-sm" style="max-width: 320px;">
                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama guru / email..." style="border-radius: 0 8px 8px 0; font-size: 0.84rem;">
            </div>
        </div>
    </div>

    <div class="table-responsive-custom">
        <table class="admin-table" id="guruBkTable">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="min-width: 250px;">Guru BK (Nama & Akun)</th>
                    <th style="width: 130px;">Hak Akses</th>
                    <th style="min-width: 320px;">Kelas Binaan (Class Mapping)</th>
                    <th class="text-center" style="width: 140px;">Total Siswa</th>
                    <th class="text-end" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guruBkList as $index => $bk)
                    @php
                        $assignedClasses = $bk->kelas_binaan_array;
                        $totalSiswaBinaan = 0;
                        foreach($assignedClasses as $k) {
                            $totalSiswaBinaan += ($classCounts[$k] ?? 0);
                        }

                        // Avatar Initials
                        $words = explode(' ', trim($bk->name));
                        $initials = '';
                        foreach(array_slice($words, 0, 2) as $w) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    @endphp
                    <tr class="bk-row" data-search="{{ strtolower($bk->name . ' ' . $bk->email . ' ' . implode(' ', $assignedClasses)) }}">
                        <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background-color: #EFF4FB; color: #3157A4; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0; border: 1px solid #D0D5DD;">
                                    {{ $initials ?: 'BK' }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.92rem;">{{ $bk->name }}</div>
                                    <div style="font-size: 0.8rem; color: #64748b;">
                                        <i class="bi bi-envelope me-1"></i>{{ $bk->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge d-inline-flex align-items-center gap-1 px-2.5 py-1.5" style="background-color: #EFF4FB; color: #3157A4; border: 1px solid #BFDBFE; font-size: 0.76rem; font-weight: 600; border-radius: 6px;">
                                <i class="bi bi-shield-check"></i> Guru BK
                            </span>
                        </td>
                        <td>
                            @if(count($assignedClasses) > 0)
                                <div class="mb-1 d-flex align-items-center gap-1">
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5" style="font-size: 0.72rem; border-radius: 4px; font-weight: 600;">
                                        {{ count($assignedClasses) }} Kelas Ditugaskan
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap gap-1" style="max-width: 520px;">
                                    @foreach($assignedClasses as $k)
                                        <span class="badge border d-inline-flex align-items-center gap-1" style="background-color: #FFFFFF; color: #1E293B; border-color: #E2E8F0; font-size: 0.76rem; border-radius: 6px; font-weight: 600; padding: 4px 7px;">
                                            <i class="bi bi-mortarboard text-primary"></i>
                                            <span>{{ $k }}</span>
                                            <small class="text-muted fw-normal">({{ $classCounts[$k] ?? 0 }})</small>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1.5 d-inline-flex align-items-center gap-1" style="font-size: 0.76rem; border-radius: 6px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum ada kelas binaan
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="fw-bold fs-6 text-dark">{{ number_format($totalSiswaBinaan) }}</div>
                            <div style="font-size: 0.72rem; color: #64748b;">siswa aktif</div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalEditGuruBk{{ $bk->id }}" style="border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <form action="{{ route('admin.guru-bk.destroy', $bk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Guru BK {{ $bk->name }}? Akses akun ini akan dicabut.');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 d-inline-flex align-items-center" title="Hapus Akun" style="border-radius: 6px; font-size: 0.8rem;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT GURU BK --}}
                    <div class="modal fade" id="modalEditGuruBk{{ $bk->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                                <div class="modal-header border-bottom py-3 px-4" style="background-color: #F8FAFC; border-radius: 12px 12px 0 0;">
                                    <div>
                                        <h5 class="modal-title fw-bold fs-6 text-dark d-flex align-items-center gap-2 mb-0">
                                            <i class="bi bi-pencil-square text-primary"></i> Edit Akun & Penugasan Kelas: {{ $bk->name }}
                                        </h5>
                                        <div class="text-muted small" style="font-size: 0.78rem;">Ubah nama, email, password, atau pemetaan kelas binaan</div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.guru-bk.update', $bk->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        {{-- Profil Akun --}}
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.88rem;">
                                                <i class="bi bi-person-fill text-primary"></i> Informasi Akun Login
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Guru BK <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ $bk->name }}" required style="border-radius: 8px; font-size: 0.88rem;">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold small text-dark mb-1">Email / Login ID <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control" value="{{ $bk->email }}" required style="border-radius: 8px; font-size: 0.88rem;">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold small text-dark mb-1">Ganti Password (Opsional)</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengganti password" style="border-radius: 8px; font-size: 0.88rem;">
                                                    <div class="form-text text-muted" style="font-size: 0.75rem;">Hanya isi jika ingin mereset password akun guru BK ini.</div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Class Mapping --}}
                                        <div>
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 0.88rem;">
                                                        <i class="bi bi-layers-fill text-primary"></i> Pemetaan Kelas Binaan (Class Mapping)
                                                    </h6>
                                                    <div class="text-muted" style="font-size: 0.76rem;">Pilih kelas yang dibina oleh guru BK ini. Guru hanya bisa melihat data dari kelas yang dipilih.</div>
                                                </div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 fw-semibold" style="font-size:0.75rem; border-radius: 6px;" onclick="toggleCheckboxes('modalEditGuruBk{{ $bk->id }}', true)">
                                                        <i class="bi bi-check-all me-1"></i> Pilih Semua
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 fw-semibold" style="font-size:0.75rem; border-radius: 6px;" onclick="toggleCheckboxes('modalEditGuruBk{{ $bk->id }}', false)">
                                                        <i class="bi bi-x-lg me-1"></i> Kosongkan
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mb-2">
                                                <input type="text" class="form-control form-control-sm class-filter-input" placeholder="🔍 Cari nama kelas (cth: TKJ, RPL, AKL)..." onkeyup="filterClassTiles(this, 'modalEditGuruBk{{ $bk->id }}')" style="border-radius: 6px; font-size: 0.82rem;">
                                            </div>

                                            <div class="p-3 bg-light rounded-3 border" style="max-height: 250px; overflow-y: auto;">
                                                <div class="row g-2 class-tile-container">
                                                    @forelse($availableClasses as $kelas)
                                                        @php
                                                            $isAssigned = in_array($kelas, $assignedClasses);
                                                            $jmlSiswa = $classCounts[$kelas] ?? 0;
                                                        @endphp
                                                        <div class="col-6 col-sm-4 col-md-3 class-item" data-class-name="{{ strtolower($kelas) }}">
                                                            <label class="class-checkbox-card p-2 rounded border d-flex align-items-center justify-content-between gap-2 mb-0 {{ $isAssigned ? 'is-selected' : '' }}" for="edit_k_{{ $bk->id }}_{{ Str::slug($kelas) }}" style="cursor: pointer; background: #FFFFFF; font-size: 0.82rem; transition: all 0.15s;">
                                                                <div class="d-flex align-items-center gap-2 text-truncate">
                                                                    <input class="form-check-input mt-0 class-checkbox" type="checkbox" name="binaan_kelas[]" value="{{ $kelas }}" id="edit_k_{{ $bk->id }}_{{ Str::slug($kelas) }}" {{ $isAssigned ? 'checked' : '' }} onchange="updateClassTile(this)">
                                                                    <span class="fw-semibold text-dark text-truncate">{{ $kelas }}</span>
                                                                </div>
                                                                <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 0.7rem;">{{ $jmlSiswa }}</span>
                                                            </label>
                                                        </div>
                                                    @empty
                                                        <div class="col-12 text-center text-muted py-4">Belum ada data kelas siswa di sistem.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top bg-light py-2 px-4" style="border-radius: 0 0 12px 12px;">
                                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" style="background-color: #3157A4; border-color: #3157A4; border-radius: 6px;">
                                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <div class="mb-3">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #EFF4FB; color: #3157A4; display: inline-flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                                        <i class="bi bi-person-x"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Akun Guru BK</h6>
                                <p class="text-muted small mb-3" style="max-width: 460px; margin: 0 auto;">
                                    Saat ini belum ada akun konselor Guru BK yang terdaftar di sistem. Buat akun pertama untuk mulai memetakan kelas binaan.
                                </p>
                                <button type="button" class="btn btn-primary btn-sm fw-semibold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahGuruBk" style="background-color: #3157A4; border-color: #3157A4; border-radius: 8px;">
                                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Guru BK Baru
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- KARTU PANDUAN & CARA KERJA --}}
<div class="card border-0 shadow-sm p-3 mb-4" style="background: #FFFFFF; border-left: 4px solid #3157A4 !important; border-radius: 10px;">
    <div class="d-flex align-items-start gap-3">
        <div style="width: 38px; height: 38px; border-radius: 8px; background: #EFF4FB; color: #3157A4; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
            <i class="bi bi-info-circle-fill"></i>
        </div>
        <div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Prinsip Kerja Akses Terisolasi Guru BK (Class Scoping)</h6>
            <p class="text-muted small mb-0" style="line-height: 1.5;">
                Saat akun Guru BK masuk ke aplikasi, sistem secara otomatis menerapkan filter keamanan berdasarkan kelas binaan yang Anda tentukan di atas. Guru BK hanya dapat melihat statistik rekap, daftar nama siswa, hasil asesmen minat karier RIASEC, pilihan kelulusan, dan unduhan file laporan dari <strong>siswa di kelas binaan mereka saja</strong>. Akun Administrator Utama tetap memiliki akses penuh ke seluruh kelas sekolah.
            </p>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH GURU BK BARU --}}
<div class="modal fade" id="modalTambahGuruBk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4" style="background-color: #F8FAFC; border-radius: 12px 12px 0 0;">
                <div>
                    <h5 class="modal-title fw-bold fs-6 text-dark d-flex align-items-center gap-2 mb-0">
                        <i class="bi bi-person-plus-fill text-primary"></i> Tambah Akun Guru BK Baru
                    </h5>
                    <div class="text-muted small" style="font-size: 0.78rem;">Buat akun login dan petakan kelas siswa yang akan dibina</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.guru-bk.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    {{-- Profil Akun --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.88rem;">
                            <i class="bi bi-person-fill text-primary"></i> Informasi Akun Login
                        </h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Guru BK <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Dra. Hj. Siti Aminah, M.Pd" required style="border-radius: 8px; font-size: 0.88rem;">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold small text-dark mb-1">Email / Login ID <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="Contoh: bk@smk.sch.id" required style="border-radius: 8px; font-size: 0.88rem;">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark mb-1">Password Login <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required style="border-radius: 8px; font-size: 0.88rem;">
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Password ini akan digunakan Guru BK untuk login ke dashboard.</div>
                            </div>
                        </div>
                    </div>

                    {{-- Class Mapping --}}
                    <div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                            <div>
                                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 0.88rem;">
                                    <i class="bi bi-layers-fill text-primary"></i> Pemetaan Kelas Binaan (Class Mapping)
                                </h6>
                                <div class="text-muted" style="font-size: 0.76rem;">Centang kelas-kelas yang dibina oleh Guru BK ini.</div>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 fw-semibold" style="font-size:0.75rem; border-radius: 6px;" onclick="toggleCheckboxes('modalTambahGuruBk', true)">
                                    <i class="bi bi-check-all me-1"></i> Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 fw-semibold" style="font-size:0.75rem; border-radius: 6px;" onclick="toggleCheckboxes('modalTambahGuruBk', false)">
                                    <i class="bi bi-x-lg me-1"></i> Kosongkan
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm class-filter-input" placeholder="🔍 Cari nama kelas (cth: TKJ, RPL, AKL)..." onkeyup="filterClassTiles(this, 'modalTambahGuruBk')" style="border-radius: 6px; font-size: 0.82rem;">
                        </div>

                        <div class="p-3 bg-light rounded-3 border" style="max-height: 250px; overflow-y: auto;">
                            <div class="row g-2 class-tile-container">
                                @forelse($availableClasses as $kelas)
                                    @php
                                        $jmlSiswa = $classCounts[$kelas] ?? 0;
                                    @endphp
                                    <div class="col-6 col-sm-4 col-md-3 class-item" data-class-name="{{ strtolower($kelas) }}">
                                        <label class="class-checkbox-card p-2 rounded border d-flex align-items-center justify-content-between gap-2 mb-0" for="new_k_{{ Str::slug($kelas) }}" style="cursor: pointer; background: #FFFFFF; font-size: 0.82rem; transition: all 0.15s;">
                                            <div class="d-flex align-items-center gap-2 text-truncate">
                                                <input class="form-check-input mt-0 class-checkbox" type="checkbox" name="binaan_kelas[]" value="{{ $kelas }}" id="new_k_{{ Str::slug($kelas) }}" onchange="updateClassTile(this)">
                                                <span class="fw-semibold text-dark text-truncate">{{ $kelas }}</span>
                                            </div>
                                            <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 0.7rem;">{{ $jmlSiswa }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">Belum ada data kelas siswa di sistem.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2 px-4" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" style="background-color: #3157A4; border-color: #3157A4; border-radius: 6px;">
                        <i class="bi bi-person-plus-fill me-1"></i> Buat Akun Guru BK
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    /* Styling interaktif untuk tile kelas */
    .class-checkbox-card {
        border-color: #E2E8F0 !important;
    }
    .class-checkbox-card:hover {
        border-color: #94A3B8 !important;
        background-color: #F8FAFC !important;
    }
    .class-checkbox-card.is-selected {
        background-color: #EFF4FB !important;
        border-color: #3157A4 !important;
    }
    .class-checkbox-card.is-selected span.fw-semibold {
        color: #1F355F !important;
        font-weight: 700 !important;
    }
</style>

<script>
    // Search filter tabel daftar guru BK
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#guruBkTable tbody tr.bk-row');
        rows.forEach(row => {
            const data = row.getAttribute('data-search') || '';
            if (data.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Toggle pilih semua / kosongkan checkbox kelas binaan
    function toggleCheckboxes(modalId, selectAll) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        const visibleItems = modal.querySelectorAll('.class-item');
        visibleItems.forEach(item => {
            if (item.style.display !== 'none') {
                const cb = item.querySelector('input.class-checkbox');
                if (cb) {
                    cb.checked = selectAll;
                    updateClassTile(cb);
                }
            }
        });
    }

    // Update style card saat checkbox berubah
    function updateClassTile(checkbox) {
        const card = checkbox.closest('.class-checkbox-card');
        if (!card) return;
        if (checkbox.checked) {
            card.classList.add('is-selected');
        } else {
            card.classList.remove('is-selected');
        }
    }

    // Filter pencarian tile kelas di dalam modal
    function filterClassTiles(input, modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        const query = input.value.toLowerCase().trim();
        const items = modal.querySelectorAll('.class-item');
        items.forEach(item => {
            const name = item.getAttribute('data-class-name') || '';
            if (name.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endpush
