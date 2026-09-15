@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
{{-- Page Title --}}
<div class="page-title-row">
    <div>
        <h1>Data Siswa Kelas 12</h1>
        <p>Kelola data siswa, informasi kelas, dan status kelengkapan tes/rencana (Total {{ $totalSiswa }} Siswa Terdaftar)</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <button type="button" class="btn btn-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa" style="background-color: #3157A4; border-color: #3157A4; border-radius: 8px; font-size: 0.88rem; box-shadow: 0 2px 6px rgba(49, 87, 164, 0.2);">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Siswa Baru</span>
        </button>
        <span class="badge bg-primary px-3 py-2 d-none d-md-inline-block" style="font-size:0.82rem; border-radius:8px; background-color: #2563eb !important;">
            <i class="bi bi-patch-check-fill me-1"></i> {{ $totalTes }} Telah Tes
        </span>
        <span class="badge bg-success px-3 py-2 d-none d-md-inline-block" style="font-size:0.82rem; border-radius:8px; background-color: #10b981 !important;">
            <i class="bi bi-compass-fill me-1"></i> {{ $totalRencana }} Isi Rencana
        </span>
    </div>
</div>

{{-- Error Validation Alert --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 p-3 mb-4" role="alert" style="background-color: #FEF2F2; border-left: 4px solid #DC2626 !important; border-radius: 8px; color: #991B1B;">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <strong>Terdapat kesalahan pada isian data siswa:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="panel-card">
    {{-- Search & Filter Grid --}}
    <form method="GET" action="{{ route('admin.siswa') }}" class="filter-grid mb-4">
        {{-- Cari Nama / NISN --}}
        <div class="filter-item">
            <label for="searchQuery">Cari Nama / NISN</label>
            <input type="text"
                   name="q"
                   id="searchQuery"
                   value="{{ request('q') }}"
                   class="filter-select"
                   placeholder="Ketik nama atau NISN...">
        </div>

        {{-- Filter Kelas --}}
        <div class="filter-item">
            <label for="filterKelas">Kelas</label>
            <select name="kelas" id="filterKelas" class="filter-select">
                <option value="Semua">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        {{ $kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Rencana --}}
        <div class="filter-item">
            <label for="filterRencana">Rencana</label>
            <select name="rencana" id="filterRencana" class="filter-select">
                <option value="Semua">Semua Rencana</option>
                <option value="kuliah" {{ request('rencana') == 'kuliah' ? 'selected' : '' }}>Kuliah</option>
                <option value="bekerja" {{ request('rencana') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                <option value="berwirausaha" {{ request('rencana') == 'berwirausaha' ? 'selected' : '' }}>Berwirausaha</option>
                <option value="belum" {{ request('rencana') == 'belum' ? 'selected' : '' }}>Belum Memilih</option>
            </select>
        </div>

        {{-- Filter Tipe RIASEC --}}
        <div class="filter-item">
            <label for="filterRiasec">Tipe Dominan</label>
            <select name="riasec" id="filterRiasec" class="filter-select">
                <option value="Semua">Semua Tipe</option>
                <option value="Realistic" {{ request('riasec') == 'Realistic' ? 'selected' : '' }}>Realistic</option>
                <option value="Investigative" {{ request('riasec') == 'Investigative' ? 'selected' : '' }}>Investigative</option>
                <option value="Artistic" {{ request('riasec') == 'Artistic' ? 'selected' : '' }}>Artistic</option>
                <option value="Social" {{ request('riasec') == 'Social' ? 'selected' : '' }}>Social</option>
                <option value="Enterprising" {{ request('riasec') == 'Enterprising' ? 'selected' : '' }}>Enterprising</option>
                <option value="Conventional" {{ request('riasec') == 'Conventional' ? 'selected' : '' }}>Conventional</option>
            </select>
        </div>

        {{-- Button Filter --}}
        <div>
            <button type="submit" class="btn-filter">
                <i class="bi bi-search"></i> Terapkan Filter
            </button>
            @if(request()->hasAny(['q', 'kelas', 'rencana', 'riasec']) && (request('q') || request('kelas') !== 'Semua' || request('rencana') !== 'Semua' || request('riasec') !== 'Semua'))
                <a href="{{ route('admin.siswa') }}" class="btn btn-sm btn-link text-muted mt-1 d-block text-center" style="font-size:0.75rem;">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel Master Siswa (CRUD) --}}
    <div class="table-responsive-custom">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">No</th>
                    <th style="min-width: 180px;">Nama Siswa</th>
                    <th style="min-width: 110px;">NISN</th>
                    <th style="min-width: 100px;">Kelas</th>
                    <th>Holland Code</th>
                    <th>Tipe Dominan</th>
                    <th>Rencana</th>
                    <th style="min-width: 200px;">Pilihan Kampus / Kerja / Usaha</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $index => $siswa)
                    <tr>
                        <td style="text-align: center; color: #64748b; font-weight: 600;">{{ $siswaList->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-weight: 700; color: #0f172a;">{{ $siswa->name }}</span>
                                @if($siswa->jk)
                                    <span class="badge {{ $siswa->jk === 'L' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}" style="font-size: 0.68rem; padding: 2px 6px; border-radius: 4px;">
                                        {{ $siswa->jk }}
                                    </span>
                                @endif
                            </div>
                            <div style="font-size: 0.74rem; color: #64748b;">
                                {{ $siswa->email }}
                                @if($siswa->nik) • <span style="font-family: monospace;">NIK: {{ $siswa->nik }}</span> @endif
                                @if($siswa->no_hp) • <i class="bi bi-whatsapp text-success"></i> {{ $siswa->no_hp }} @endif
                            </div>
                        </td>
                        <td style="font-family: monospace; color: #475569; font-weight: 600;">
                            {{ $siswa->nisn ?? '-' }}
                        </td>
                        <td>
                            <span class="badge border px-2 py-1" style="background: #F8FAFC; color: #1E293B; font-size:0.75rem; font-weight: 600; border-radius: 6px;">
                                <i class="bi bi-mortarboard text-primary me-1"></i>{{ $siswa->kelas ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($siswa->careerResult)
                                <span class="badge bg-primary font-monospace px-2 py-1" style="font-size: 0.8rem; border-radius: 6px;">
                                    {{ $siswa->careerResult->holland_code }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.78rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->careerResult)
                                <span style="font-size: 0.82rem; font-weight: 700; color: #334155;">
                                    {{ $siswa->careerResult->dominant_type }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.75rem;">Belum Tes</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->pilihanSetelahLulus)
                                @php $p = $siswa->pilihanSetelahLulus; @endphp
                                @if($p->rencana === 'kuliah')
                                    <span class="badge-pill-rencana badge-pill-kuliah">Kuliah</span>
                                @elseif($p->rencana === 'bekerja')
                                    <span class="badge-pill-rencana badge-pill-bekerja">Bekerja</span>
                                @else
                                    <span class="badge-pill-rencana badge-pill-berwirausaha">Berwirausaha</span>
                                @endif
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size:0.72rem; border-radius: 6px;">
                                    Belum Memilih
                                </span>
                            @endif
                        </td>
                        <td style="font-size: 0.82rem;">
                            @if($siswa->pilihanSetelahLulus)
                                @php $p = $siswa->pilihanSetelahLulus; @endphp
                                @if($p->rencana === 'kuliah')
                                    <div style="font-weight: 700; color: #0f172a;">{{ $p->nama_perguruan_tinggi }}</div>
                                    <div style="color: #2563eb; font-size: 0.75rem;">{{ $p->nama_program_studi }} ({{ $p->jenjang }})</div>
                                @elseif($p->rencana === 'bekerja')
                                    <div style="font-weight: 700; color: #0f172a;">{{ $p->bidang_pekerjaan }}</div>
                                    <div style="color: #64748b; font-size: 0.75rem;">{{ $p->keterangan_pekerjaan ?: '-' }}</div>
                                @else
                                    <div style="font-weight: 700; color: #0f172a;">{{ $p->bidang_usaha }}</div>
                                    <div style="color: #64748b; font-size: 0.75rem;">{{ $p->keterangan_usaha ?: '-' }}</div>
                                @endif
                            @else
                                <span class="text-muted italic" style="font-size: 0.78rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->careerResult && $siswa->pilihanSetelahLulus)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:0.72rem; border-radius: 6px;">
                                    <i class="bi bi-check-all me-1"></i> Lengkap
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size:0.72rem; border-radius: 6px;">
                                    Belum Lengkap
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $siswa->id }}" title="Edit Data Siswa" style="border-radius: 6px; font-size: 0.78rem;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $siswa->name }}? Tindakan ini juga akan menghapus hasil tes dan pilihan rencana siswa ini.');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 d-inline-flex align-items-center" title="Hapus Siswa" style="border-radius: 6px; font-size: 0.78rem;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- MODAL EDIT SISWA --}}
                            <div class="modal fade" id="modalEditSiswa{{ $siswa->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                                        <div class="modal-header border-bottom py-3 px-4" style="background-color: #F8FAFC; border-radius: 12px 12px 0 0;">
                                            <div>
                                                <h5 class="modal-title fw-bold fs-6 text-dark d-flex align-items-center gap-2 mb-0">
                                                    <i class="bi bi-pencil-square text-primary"></i> Edit Data Siswa: {{ $siswa->name }}
                                                </h5>
                                                <div class="text-muted small" style="font-size: 0.78rem;">Perbarui biodata siswa dan akun login</div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" value="{{ $siswa->name }}" required style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">NISN <span class="text-danger">*</span></label>
                                                        <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}" required style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Jenis Kelamin (L/P)</label>
                                                        <select name="jk" class="form-select" style="border-radius: 8px; font-size: 0.88rem;">
                                                            <option value="" {{ empty($siswa->jk) ? 'selected' : '' }}>- Pilih -</option>
                                                            <option value="L" {{ $siswa->jk === 'L' ? 'selected' : '' }}>L (Laki-laki)</option>
                                                            <option value="P" {{ $siswa->jk === 'P' ? 'selected' : '' }}>P (Perempuan)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Kelas <span class="text-danger">*</span></label>
                                                        <select name="kelas" class="form-select" required style="border-radius: 8px; font-size: 0.88rem;">
                                                            @foreach($kelasList as $k)
                                                                <option value="{{ $k }}" {{ $siswa->kelas === $k ? 'selected' : '' }}>
                                                                    {{ $k }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold small text-dark mb-1">NIK (KTP/KK)</label>
                                                        <input type="text" name="nik" class="form-control" value="{{ $siswa->nik }}" placeholder="16 digit NIK" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold small text-dark mb-1">NIPD</label>
                                                        <input type="text" name="nipd" class="form-control" value="{{ $siswa->nipd }}" placeholder="Nomor Induk Peserta Didik" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">No HP / WhatsApp</label>
                                                        <input type="text" name="no_hp" class="form-control" value="{{ $siswa->no_hp }}" placeholder="Contoh: 08123456789" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Email / Login ID</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $siswa->email }}" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Tempat Lahir</label>
                                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ $siswa->tempat_lahir }}" placeholder="Contoh: Bandar Lampung" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir }}" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Alamat Domisili (Jalan/Gang)</label>
                                                        <input type="text" name="alamat" class="form-control" value="{{ $siswa->alamat }}" placeholder="Contoh: Jl. Perintis Kemerdekaan No. 10" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">RT</label>
                                                        <input type="text" name="rt" class="form-control" value="{{ $siswa->rt }}" placeholder="RT" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">RW</label>
                                                        <input type="text" name="rw" class="form-control" value="{{ $siswa->rw }}" placeholder="RW" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Kelurahan / Desa</label>
                                                        <input type="text" name="kelurahan" class="form-control" value="{{ $siswa->kelurahan }}" placeholder="Kelurahan" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Kecamatan</label>
                                                        <input type="text" name="kecamatan" class="form-control" value="{{ $siswa->kecamatan }}" placeholder="Kecamatan" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Kabupaten / Kota</label>
                                                        <input type="text" name="kabupaten_kota" class="form-control" value="{{ $siswa->kabupaten_kota ?: 'Kota Bandar Lampung' }}" placeholder="Kota Bandar Lampung" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Kode Pos</label>
                                                        <input type="text" name="kode_pos" class="form-control" value="{{ $siswa->kode_pos }}" placeholder="Kode Pos" style="border-radius: 8px; font-size: 0.88rem;">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Reset Password (Opsional)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password" style="border-radius: 8px; font-size: 0.88rem;">
                                                        <div class="form-text text-muted" style="font-size: 0.75rem;">Password default siswa biasanya adalah NISN atau 'password'.</div>
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-2 d-block mb-2 text-secondary"></i>
                            Tidak ada data siswa yang cocok dengan kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($siswaList->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div style="font-size: 0.82rem; color: #64748b;">
                Menampilkan {{ $siswaList->firstItem() ?? 0 }} - {{ $siswaList->lastItem() ?? 0 }} dari {{ $siswaList->total() }} siswa
            </div>
            <div>
                {{ $siswaList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

{{-- MODAL TAMBAH SISWA BARU --}}
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4" style="background-color: #F8FAFC; border-radius: 12px 12px 0 0;">
                <div>
                    <h5 class="modal-title fw-bold fs-6 text-dark d-flex align-items-center gap-2 mb-0">
                        <i class="bi bi-person-plus-fill text-primary"></i> Tambah Data Siswa Baru
                    </h5>
                    <div class="text-muted small" style="font-size: 0.78rem;">Tambahkan data siswa kelas 12 secara manual ke dalam sistem</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: AHMAD ZAKY" required style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">NISN <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" placeholder="Contoh: 0061234567" required style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">Jenis Kelamin (L/P)</label>
                            <select name="jk" class="form-select" style="border-radius: 8px; font-size: 0.88rem;">
                                <option value="" selected>- Pilih -</option>
                                <option value="L">L (Laki-laki)</option>
                                <option value="P">P (Perempuan)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">Kelas Siswa <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-select" required style="border-radius: 8px; font-size: 0.88rem;">
                                <option value="" disabled selected>-- Pilih Kelas Siswa --</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">NIK (KTP/KK)</label>
                            <input type="text" name="nik" class="form-control" placeholder="16 digit NIK" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">NIPD</label>
                            <input type="text" name="nipd" class="form-control" placeholder="NIPD siswa" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">No HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Email Akun (Opsional)</label>
                            <input type="email" name="email" class="form-control" placeholder="Biarkan kosong untuk auto-generate (nisn@sekolah.id)" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Tempat Lahir (Opsional)</label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: Lampung" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Tanggal Lahir (Opsional)</label>
                            <input type="date" name="tanggal_lahir" class="form-control" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">Alamat Lengkap</label>
                            <input type="text" name="alamat" class="form-control" placeholder="Jl. Contoh No. 123" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">RT</label>
                            <input type="text" name="rt" class="form-control" placeholder="RT" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">RW</label>
                            <input type="text" name="rw" class="form-control" placeholder="RW" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" placeholder="Kelurahan" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">Password Akun (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong untuk menggunakan NISN sebagai password default" style="border-radius: 8px; font-size: 0.88rem;">
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Default password adalah NISN siswa jika dikosongkan.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2 px-4" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" style="background-color: #3157A4; border-color: #3157A4; border-radius: 6px;">
                        <i class="bi bi-person-plus-fill me-1"></i> Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
