@extends('layouts.app')

@section('title', 'Data Rencana Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 fade-in-up">
    <div class="page-header mb-0">
        <h1><i class="bi bi-people me-2" style="color:var(--primary);"></i>Data Rencana Siswa</h1>
        <p>Daftar rencana siswa setelah lulus</p>
    </div>
    <a href="{{ route('admin.rencana-siswa.export', request()->query()) }}" class="btn btn-outline-custom">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

{{-- Filter --}}
<div class="card-glass mb-4 fade-in-up delay-1">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.rencana-siswa') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--gray-600);text-transform:uppercase;">Rencana</label>
                    <select name="rencana" class="form-select form-control-custom">
                        <option value="">Semua</option>
                        <option value="kuliah" {{ request('rencana') == 'kuliah' ? 'selected' : '' }}>Kuliah</option>
                        <option value="bekerja" {{ request('rencana') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                        <option value="berwirausaha" {{ request('rencana') == 'berwirausaha' ? 'selected' : '' }}>Berwirausaha</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--gray-600);text-transform:uppercase;">Kampus</label>
                    <input type="text" name="kampus" class="form-control form-control-custom" placeholder="Cari kampus..." value="{{ request('kampus') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--gray-600);text-transform:uppercase;">Prodi</label>
                    <input type="text" name="prodi" class="form-control form-control-custom" placeholder="Cari prodi..." value="{{ request('prodi') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--gray-600);text-transform:uppercase;">Jenjang</label>
                    <select name="jenjang" class="form-select form-control-custom">
                        <option value="">Semua</option>
                        @foreach($jenjangList as $j)
                            <option value="{{ $j }}" {{ request('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('admin.rencana-siswa') }}" class="btn btn-outline-custom" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card-glass fade-in-up delay-2">
    <div class="card-body p-0">
        @if($pilihan->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Rencana</th>
                            <th>Kampus</th>
                            <th>Prodi</th>
                            <th>Jenjang</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pilihan as $index => $item)
                            <tr>
                                <td style="color:var(--gray-400);font-weight:500;">{{ $pilihan->firstItem() + $index }}</td>
                                <td style="font-weight:600;">{{ $item->user->name ?? '-' }}</td>
                                <td><code style="background:var(--gray-100);padding:0.15rem 0.4rem;border-radius:4px;font-size:0.8rem;">{{ $item->user->nisn ?? '-' }}</code></td>
                                <td>
                                    <span class="badge-rencana badge-{{ $item->rencana }}">
                                        {{ ucfirst($item->rencana) }}
                                    </span>
                                </td>
                                <td style="font-size:0.85rem;">{{ $item->nama_perguruan_tinggi ?? '-' }}</td>
                                <td style="font-size:0.85rem;">{{ $item->nama_program_studi ?? '-' }}</td>
                                <td>{{ $item->jenjang ?? '-' }}</td>
                                <td style="font-size:0.8rem;color:var(--gray-500);">{{ $item->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.rencana-siswa.reset', $item->id) }}"
                                          onsubmit="return confirm('Reset pilihan {{ $item->user->name ?? 'siswa' }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="color:var(--danger);background:rgba(239,68,68,0.08);border-radius:8px;font-size:0.8rem;padding:0.3rem 0.6rem;" title="Reset pilihan">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pilihan->hasPages())
                <div class="p-3 d-flex justify-content-center">
                    {{ $pilihan->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                <h5 style="font-weight:700;color:var(--gray-700);">Tidak Ada Data</h5>
                <p style="color:var(--gray-500);font-size:0.9rem;">Belum ada siswa yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection
