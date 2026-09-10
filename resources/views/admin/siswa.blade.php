@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
{{-- Page Title --}}
<div class="page-title-row">
    <div>
        <h1>Data Siswa Kelas 12</h1>
        <p>Master Data Siswa dari File KELAS 12.xlsx (Total {{ $totalSiswa }} Siswa Terdaftar)</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-primary px-3 py-2" style="font-size:0.82rem; border-radius:8px;">
            <i class="bi bi-patch-check-fill me-1"></i> {{ $totalTes }} Telah Tes Minat
        </span>
        <span class="badge bg-success px-3 py-2" style="font-size:0.82rem; border-radius:8px;">
            <i class="bi bi-compass-fill me-1"></i> {{ $totalRencana }} Mengisi Rencana
        </span>
    </div>
</div>

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

    {{-- Tabel Master Siswa (PRD Section 20) --}}
    <div class="table-responsive-custom">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>Holland Code</th>
                    <th>Tipe Dominan</th>
                    <th>Rencana</th>
                    <th>Pilihan Kampus / Kerja / Usaha</th>
                    <th>Status Kelengkapan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $index => $siswa)
                    <tr>
                        <td>{{ $siswaList->firstItem() + $index }}</td>
                        <td style="font-weight: 700; color: #0f172a;">
                            {{ $siswa->name }}
                        </td>
                        <td style="font-family: monospace; color: #64748b;">
                            {{ $siswa->nisn ?? '-' }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size:0.75rem;">
                                {{ $siswa->kelas ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($siswa->careerResult)
                                <span class="badge bg-primary font-monospace px-2 py-1" style="font-size: 0.8rem;">
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
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size:0.72rem;">
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
                                <span class="badge bg-success text-white px-2 py-1" style="font-size:0.72rem;">
                                    <i class="bi bi-check-all"></i> Lengkap
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning px-2 py-1" style="font-size:0.72rem;">
                                    Belum Lengkap
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
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
@endsection
