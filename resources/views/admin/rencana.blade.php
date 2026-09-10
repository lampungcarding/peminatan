@extends('layouts.admin')

@section('title', 'Data Rencana Siswa')

@section('content')
<div class="page-title-row">
    <div>
        <h1>Data Rencana Siswa</h1>
        <p>Seluruh pilihan rencana kelulusan siswa (Kuliah, Bekerja, Berwirausaha)</p>
    </div>
    <div class="date-badge">
        <i class="bi bi-compass-fill" style="color: #2563eb;"></i>
        <span>Total: {{ $pilihan->total() }} Rencana Masuk</span>
    </div>
</div>

<div class="panel-card">
    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.rencana') }}" class="filter-grid mb-4">
        {{-- Filter Rencana --}}
        <div class="filter-item">
            <label for="filterRencana">Pilihan Rencana</label>
            <select name="rencana" id="filterRencana" class="filter-select">
                <option value="Semua">Semua Rencana</option>
                <option value="kuliah" {{ request('rencana') == 'kuliah' ? 'selected' : '' }}>Kuliah</option>
                <option value="bekerja" {{ request('rencana') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                <option value="berwirausaha" {{ request('rencana') == 'berwirausaha' ? 'selected' : '' }}>Berwirausaha</option>
            </select>
        </div>

        {{-- Filter Kampus --}}
        <div class="filter-item">
            <label for="filterKampus">Perguruan Tinggi</label>
            <select name="kampus" id="filterKampus" class="filter-select">
                <option value="Semua">Semua Kampus</option>
                @foreach($kampusList as $kampus)
                    <option value="{{ $kampus }}" {{ request('kampus') == $kampus ? 'selected' : '' }}>
                        {{ $kampus }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Search Q --}}
        <div class="filter-item">
            <label for="searchQ">Pencarian Siswa</label>
            <input type="text" name="q" id="searchQ" class="filter-select" placeholder="Nama atau NISN..." value="{{ request('q') }}">
        </div>

        <div>
            <button type="submit" class="btn-filter">
                <i class="bi bi-search"></i> Terapkan
            </button>
            @if(request()->hasAny(['rencana', 'kampus', 'q']) && (request('rencana') !== 'Semua' || request('kampus') !== 'Semua' || request('q')))
                <a href="{{ route('admin.rencana') }}" class="btn btn-sm btn-link text-muted mt-1 d-block text-center" style="font-size:0.75rem;">
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
                    <th>Rencana</th>
                    <th>Detail Pilihan (Studi / Pekerjaan / Usaha)</th>
                    <th>Keterangan Tambahan</th>
                    <th>Waktu Submit</th>
                    <th style="text-align: right; padding-right: 16px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pilihan as $index => $item)
                    <tr>
                        <td>{{ $pilihan->firstItem() + $index }}</td>
                        <td style="font-weight: 700; color: #0f172a;">
                            {{ $item->user->name ?? '-' }}
                            <div style="font-size: 0.75rem; color: #64748b; font-family: monospace;">{{ $item->user->nisn ?? '-' }}</div>
                        </td>
                        <td>{{ $item->user->kelas ?? '-' }}</td>
                        <td>
                            @if($item->user->careerResult)
                                <span class="badge bg-primary font-monospace px-2 py-1" style="font-size: 0.78rem;">
                                    {{ $item->user->careerResult->holland_code }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.78rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->rencana === 'kuliah')
                                <span class="badge-pill-rencana badge-pill-kuliah">Kuliah</span>
                            @elseif($item->rencana === 'bekerja')
                                <span class="badge-pill-rencana badge-pill-bekerja">Bekerja</span>
                            @else
                                <span class="badge-pill-rencana badge-pill-berwirausaha">Berwirausaha</span>
                            @endif
                        </td>
                        <td>
                            @if($item->rencana === 'kuliah')
                                <div style="font-weight: 700; color: #1e293b;">{{ $item->nama_perguruan_tinggi ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">
                                    {{ $item->nama_program_studi ?? '-' }} ({{ $item->jenjang ?? '-' }})
                                    @if($item->akreditasi)
                                        • Akreditasi: {{ $item->akreditasi }}
                                    @endif
                                </div>
                            @elseif($item->rencana === 'bekerja')
                                <div style="font-weight: 700; color: #1e293b;">{{ $item->bidang_pekerjaan ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">Sektor Industri / Lapangan Kerja</div>
                            @else
                                <div style="font-weight: 700; color: #1e293b;">{{ $item->bidang_usaha ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">Rintisan Wirausaha Mandiri</div>
                            @endif
                        </td>
                        <td style="font-size: 0.82rem; color: #475569;">
                            @if($item->rencana === 'bekerja')
                                {{ $item->keterangan_pekerjaan ?: '-' }}
                            @elseif($item->rencana === 'berwirausaha')
                                {{ $item->keterangan_usaha ?: '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="color: #64748b; font-size: 0.8rem;">
                            {{ $item->submitted_at ? $item->submitted_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td style="text-align: right; padding-right: 16px;">
                            <form action="{{ route('admin.rencana-siswa.reset', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin mereset pilihan rencana siswa {{ $item->user->name ?? '' }}?');"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset Pilihan" style="font-size: 0.75rem; padding: 4px 8px;">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Tidak ada data rencana siswa yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pilihan->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div style="font-size: 0.82rem; color: #64748b;">
                Menampilkan {{ $pilihan->firstItem() }} - {{ $pilihan->lastItem() }} dari {{ $pilihan->total() }} data
            </div>
            <div>
                {{ $pilihan->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
