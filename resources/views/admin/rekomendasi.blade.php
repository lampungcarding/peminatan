@extends('layouts.admin')

@section('title', 'Bank Rekomendasi Karier & Studi')

@section('content')
<div class="page-title-row">
    <div>
        <h1>Bank Rekomendasi Karier & Studi</h1>
        <p>Database pemetaan Holland / RIASEC ke Jurusan, Profesi, dan Ide Usaha</p>
    </div>
    <div class="date-badge">
        <i class="bi bi-stars" style="color: #2563eb;"></i>
        <span>Total: {{ $recommendations->total() }} Rekomendasi</span>
    </div>
</div>

<div class="panel-card">
    {{-- Filter Grid --}}
    <form method="GET" action="{{ route('admin.rekomendasi') }}" class="filter-grid mb-4">
        {{-- Filter RIASEC --}}
        <div class="filter-item">
            <label for="filterRiasec">Kode Dimensi</label>
            <select name="riasec" id="filterRiasec" class="filter-select">
                <option value="Semua">Semua Dimensi</option>
                <option value="R" {{ request('riasec') == 'R' ? 'selected' : '' }}>R — Realistic</option>
                <option value="I" {{ request('riasec') == 'I' ? 'selected' : '' }}>I — Investigative</option>
                <option value="A" {{ request('riasec') == 'A' ? 'selected' : '' }}>A — Artistic</option>
                <option value="S" {{ request('riasec') == 'S' ? 'selected' : '' }}>S — Social</option>
                <option value="E" {{ request('riasec') == 'E' ? 'selected' : '' }}>E — Enterprising</option>
                <option value="C" {{ request('riasec') == 'C' ? 'selected' : '' }}>C — Conventional</option>
            </select>
        </div>

        {{-- Filter Category --}}
        <div class="filter-item">
            <label for="filterCategory">Kategori Rekomendasi</label>
            <select name="category" id="filterCategory" class="filter-select">
                <option value="Semua">Semua Kategori</option>
                <option value="jurusan" {{ request('category') == 'jurusan' ? 'selected' : '' }}>Program Studi / Jurusan</option>
                <option value="profesi" {{ request('category') == 'profesi' ? 'selected' : '' }}>Profesi / Karier</option>
                <option value="usaha" {{ request('category') == 'usaha' ? 'selected' : '' }}>Bidang Usaha</option>
            </select>
        </div>

        {{-- Search Q --}}
        <div class="filter-item">
            <label for="searchQ">Pencarian</label>
            <input type="text" name="q" id="searchQ" class="filter-select" placeholder="Nama jurusan, profesi, usaha..." value="{{ request('q') }}">
        </div>

        <div>
            <button type="submit" class="btn-filter">
                <i class="bi bi-search"></i> Terapkan
            </button>
            @if(request()->hasAny(['riasec', 'category', 'q']) && (request('riasec') !== 'Semua' || request('category') !== 'Semua' || request('q')))
                <a href="{{ route('admin.rekomendasi') }}" class="btn btn-sm btn-link text-muted mt-1 d-block text-center" style="font-size:0.75rem;">
                    Reset Filter
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel Data --}}
    <div class="table-responsive-custom">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 80px;">Dimensi</th>
                    <th style="width: 140px;">Kategori</th>
                    <th>Nama Rekomendasi</th>
                    <th>Deskripsi / Profil</th>
                    <th style="width: 80px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recommendations as $index => $rec)
                    <tr>
                        <td>{{ $recommendations->firstItem() + $index }}</td>
                        <td>
                            @php
                                $dimColor = match($rec->riasec_code) {
                                    'R' => '#3b82f6',
                                    'I' => '#06b6d4',
                                    'A' => '#ec4899',
                                    'S' => '#10b981',
                                    'E' => '#f59e0b',
                                    'C' => '#8b5cf6',
                                    default => '#64748b'
                                };
                            @endphp
                            <span class="badge text-white font-monospace px-2 py-1" style="background: {{ $dimColor }};">
                                {{ $rec->riasec_code }}
                            </span>
                        </td>
                        <td>
                            @if($rec->category === 'jurusan')
                                <span class="badge-pill-soft badge-kuliah-soft">Jurusan</span>
                            @elseif($rec->category === 'profesi')
                                <span class="badge-pill-soft badge-bekerja-soft">Profesi</span>
                            @else
                                <span class="badge-pill-soft badge-wirausaha-soft">Usaha</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">
                            {{ $rec->name }}
                        </td>
                        <td style="font-size: 0.84rem; color: #475569; line-height: 1.45;">
                            {{ $rec->description ?: '-' }}
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                Aktif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Tidak ada rekomendasi yang sesuai dengan kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($recommendations->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div style="font-size: 0.82rem; color: #64748b;">
                Menampilkan {{ $recommendations->firstItem() }} - {{ $recommendations->lastItem() }} dari {{ $recommendations->total() }} data
            </div>
            <div>
                {{ $recommendations->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
