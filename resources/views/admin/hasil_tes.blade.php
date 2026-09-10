@extends('layouts.admin')

@section('title', 'Hasil Tes Minat Karier RIASEC')

@section('content')
<div class="page-title-row">
    <div>
        <h1>Hasil Tes Minat Karier</h1>
        <p>Daftar skor, tipe dominan, dan Holland Code siswa</p>
    </div>
    <div class="date-badge">
        <i class="bi bi-patch-check-fill" style="color: #2563eb;"></i>
        <span>Total: {{ $hasilList->total() }} Hasil Selesai</span>
    </div>
</div>

<div class="panel-card">
    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.hasil-tes') }}" class="filter-grid mb-4">
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

        {{-- Filter Dominan --}}
        <div class="filter-item">
            <label for="filterDominan">Tipe Dominan</label>
            <select name="dominan" id="filterDominan" class="filter-select">
                <option value="Semua">Semua Tipe</option>
                @foreach($dominantTypes as $type)
                    <option value="{{ $type }}" {{ request('dominan') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Search Keyword --}}
        <div class="filter-item">
            <label for="searchQ">Pencarian</label>
            <input type="text" name="q" id="searchQ" class="filter-select" placeholder="Nama, NISN, atau Code (ICR)..." value="{{ request('q') }}">
        </div>

        <div>
            <button type="submit" class="btn-filter">
                <i class="bi bi-search"></i> Terapkan
            </button>
            @if(request()->hasAny(['kelas', 'dominan', 'q']) && (request('kelas') !== 'Semua' || request('dominan') !== 'Semua' || request('q')))
                <a href="{{ route('admin.hasil-tes') }}" class="btn btn-sm btn-link text-muted mt-1 d-block text-center" style="font-size:0.75rem;">
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
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Holland Code</th>
                    <th>Tipe Dominan</th>
                    <th>Jangkar & Jalur Eksekusi</th>
                    <th class="text-center">R</th>
                    <th class="text-center">I</th>
                    <th class="text-center">A</th>
                    <th class="text-center">S</th>
                    <th class="text-center">E</th>
                    <th class="text-center">C</th>
                    <th>Waktu Tes</th>
                    <th style="text-align: right; padding-right: 16px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilList as $index => $item)
                    <tr>
                        <td>{{ $hasilList->firstItem() + $index }}</td>
                        <td style="font-weight: 700; color: #0f172a;">
                            {{ $item->user->name ?? '-' }}
                            <div style="font-size: 0.75rem; color: #64748b; font-family: monospace;">{{ $item->user->nisn ?? '-' }}</div>
                        </td>
                        <td>{{ $item->user->kelas ?? '-' }}</td>
                        <td>
                            <span class="badge bg-primary font-monospace px-2 py-1" style="font-size: 0.82rem; letter-spacing: 0.05em;">
                                {{ $item->holland_code }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #1e293b; font-size: 0.85rem;">
                                {{ $item->dominant_type }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.82rem; font-weight: 700; color: #0f172a;">
                                {{ $item->dominant_anchor ?? '-' }}
                            </div>
                            <span class="badge {{ $item->execution_path_badge['class'] }}" style="font-size: 0.7rem;">
                                {{ $item->execution_path_badge['label'] }}
                            </span>
                        </td>
                        <td class="text-center fw-bold text-primary">{{ $item->realistic_score }}</td>
                        <td class="text-center fw-bold text-info">{{ $item->investigative_score }}</td>
                        <td class="text-center fw-bold text-pink" style="color:#ec4899;">{{ $item->artistic_score }}</td>
                        <td class="text-center fw-bold text-success">{{ $item->social_score }}</td>
                        <td class="text-center fw-bold text-warning">{{ $item->enterprising_score }}</td>
                        <td class="text-center fw-bold text-purple" style="color:#8b5cf6;">{{ $item->conventional_score }}</td>
                        <td style="color: #64748b; font-size: 0.8rem;">
                            {{ $item->completed_at ? $item->completed_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td style="text-align: right; padding-right: 16px;">
                            <form action="{{ route('admin.hasil-tes.reset', $item->user_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin mereset hasil tes minat siswa {{ $item->user->name ?? '' }}? Siswa dapat mengikuti tes ulang.');"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset Tes" style="font-size: 0.75rem; padding: 4px 8px;">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Tidak ada data hasil tes minat yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($hasilList->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div style="font-size: 0.82rem; color: #64748b;">
                Menampilkan {{ $hasilList->firstItem() }} - {{ $hasilList->lastItem() }} dari {{ $hasilList->total() }} data
            </div>
            <div>
                {{ $hasilList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
