@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
{{-- Page Title & Date Badge --}}
<div class="page-title-row">
    <div>
        <h1>Dashboard</h1>
        <p>Sistem Perencanaan Karier & Studi Siswa v2</p>
    </div>
    <div class="date-badge">
        <i class="bi bi-calendar3" style="color: #2563eb;"></i>
        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>

{{-- 4 Stat Cards (PRD Section 19) --}}
<div class="row g-3 mb-4">
    {{-- Total Siswa --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle stat-icon-total">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
                <div class="stat-subtext">Siswa Kelas 12 terdaftar</div>
            </div>
        </div>
    </div>

    {{-- Tes Selesai --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background-color: #06b6d4;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Tes Selesai</div>
                <div class="stat-value">{{ $totalTesSelesai }}</div>
                <div class="stat-subtext">{{ $totalSiswa > 0 ? round(($totalTesSelesai / $totalSiswa) * 100) : 0 }}% telah tes RIASEC</div>
            </div>
        </div>
    </div>

    {{-- Belum Tes --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background-color: #f59e0b;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Belum Tes</div>
                <div class="stat-value">{{ $totalBelumTes }}</div>
                <div class="stat-subtext">Perlu mengikuti tes minat</div>
            </div>
        </div>
    </div>

    {{-- Data Lengkap --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle stat-icon-kuliah">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Data Lengkap</div>
                <div class="stat-value">{{ $totalDataLengkap }}</div>
                <div class="stat-subtext">Tes minat + Rencana terisi</div>
            </div>
        </div>
    </div>
</div>

{{-- Row of 2 Charts: Distribusi Rencana (Donut) & Distribusi RIASEC (Bar) --}}
<div class="row g-3 mb-4">
    {{-- Chart 1: Distribusi Rencana Siswa (Donut Chart) --}}
    <div class="col-12 col-lg-5">
        <div class="panel-card h-100 mb-0">
            <div class="panel-header">
                <div>
                    <h2>Distribusi Rencana Siswa</h2>
                    <p>Status pilihan rencana kelulusan siswa</p>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-2">
                <div style="position: relative; width: 190px; height: 190px; flex-shrink: 0;" class="d-flex align-items-center justify-content-center">
                    <canvas id="distribusiChart"></canvas>
                    @if($totalSubmitRencana === 0)
                        <div style="position: absolute; text-align: center; pointer-events: none;">
                            <div style="font-size: 0.72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Belum Ada</div>
                            <div style="font-size: 0.65rem; color: #cbd5e1;">Data Masuk</div>
                        </div>
                    @endif
                </div>

                {{-- Legend Kolom Kanan --}}
                <div class="d-flex flex-column gap-2 w-100 ps-sm-3">
                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%; background-color:#2563eb; display:inline-block;"></span>
                            <span style="font-size:0.82rem; font-weight:600; color:#334155;">Kuliah</span>
                        </div>
                        <span style="font-size:0.82rem; font-weight:700; color:#0f172a;">{{ $totalKuliah }} ({{ $pctKuliah }}%)</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%; background-color:#f59e0b; display:inline-block;"></span>
                            <span style="font-size:0.82rem; font-weight:600; color:#334155;">Bekerja</span>
                        </div>
                        <span style="font-size:0.82rem; font-weight:700; color:#0f172a;">{{ $totalBekerja }} ({{ $pctBekerja }}%)</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%; background-color:#8b5cf6; display:inline-block;"></span>
                            <span style="font-size:0.82rem; font-weight:600; color:#334155;">Berwirausaha</span>
                        </div>
                        <span style="font-size:0.82rem; font-weight:700; color:#0f172a;">{{ $totalWirausaha }} ({{ $pctWirausaha }}%)</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between py-1">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%; background-color:#cbd5e1; display:inline-block;"></span>
                            <span style="font-size:0.82rem; font-weight:600; color:#64748b;">Belum Memilih</span>
                        </div>
                        <span style="font-size:0.82rem; font-weight:700; color:#64748b;">{{ $totalBelumRencana }} ({{ $pctBelumRencana }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart 2: Distribusi Tipe Dominan RIASEC (Bar Chart 6 Dimensi) --}}
    <div class="col-12 col-lg-7">
        <div class="panel-card h-100 mb-0">
            <div class="panel-header">
                <div>
                    <h2>Distribusi Minat Karier Siswa</h2>
                    <p>Jumlah siswa berdasarkan tipe dominan Holland / RIASEC</p>
                </div>
                <div>
                    <span class="badge bg-primary-subtle text-primary fw-bold" style="font-size: 0.78rem;">
                        {{ $totalTesSelesai }} Siswa Telah Tes
                    </span>
                </div>
            </div>

            <div style="height: 220px; width: 100%;">
                <canvas id="riasecBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Data Rencana Siswa Terbaru Table Panel --}}
<div class="panel-card" id="tabel-rencana">
    <div class="panel-header">
        <div>
            <h2>Data Pilihan Terbaru</h2>
            <p>Pengisian rencana kelulusan siswa terbaru</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.rencana') }}" class="btn-export-outline">
                <i class="bi bi-arrow-right"></i> Lihat Semua Rencana
            </a>
            <a href="{{ route('admin.laporan.export') }}" class="btn-export-outline" style="background:#2563eb; color:#ffffff; border-color:#2563eb;">
                <i class="bi bi-download"></i> Export Data Lengkap
            </a>
        </div>
    </div>

    {{-- Tabel Data Terbaru --}}
    <div class="table-responsive-custom">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Holland Code</th>
                    <th>Dominan</th>
                    <th>Rencana</th>
                    <th>Pilihan Studi / Kerja / Usaha</th>
                    <th>Waktu Submit</th>
                    <th style="text-align: right; padding-right: 20px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pilihanTerbaru as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
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
                            @if($item->user->careerResult)
                                <span style="font-size: 0.82rem; font-weight: 600; color: #334155;">
                                    {{ $item->user->careerResult->dominant_type }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.78rem;">Belum Tes</span>
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
                                <div style="font-weight: 600; color: #1e293b;">{{ $item->nama_perguruan_tinggi ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $item->nama_program_studi ?? '-' }} ({{ $item->jenjang ?? '-' }})</div>
                            @elseif($item->rencana === 'bekerja')
                                <div style="font-weight: 600; color: #1e293b;">{{ $item->bidang_pekerjaan ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $item->keterangan_pekerjaan ?: 'Dunia Kerja' }}</div>
                            @else
                                <div style="font-weight: 600; color: #1e293b;">{{ $item->bidang_usaha ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $item->keterangan_usaha ?: 'Wirausaha Mandiri' }}</div>
                            @endif
                        </td>
                        <td style="color: #64748b; font-size: 0.8rem;">
                            {{ $item->submitted_at ? \Carbon\Carbon::parse($item->submitted_at)->format('d M Y H:i') : '-' }}
                        </td>
                        <td style="text-align: right; padding-right: 16px;">
                            <form action="{{ route('admin.rencana-siswa.reset', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin mereset pilihan siswa {{ $item->user->name ?? '' }}?');"
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
                            Belum ada siswa yang mengisi rencana setelah lulus.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Donut Chart Distribusi Rencana
        const ctxDistribusi = document.getElementById('distribusiChart').getContext('2d');
        const totalSub = {{ $totalSubmitRencana }};
        const hasData = totalSub > 0;

        new Chart(ctxDistribusi, {
            type: 'doughnut',
            data: {
                labels: hasData ? ['Kuliah', 'Bekerja', 'Berwirausaha', 'Belum Memilih'] : ['Belum Ada Data'],
                datasets: [{
                    data: hasData ? [{{ $totalKuliah }}, {{ $totalBekerja }}, {{ $totalWirausaha }}, {{ $totalBelumRencana }}] : [1],
                    backgroundColor: hasData ? ['#2563eb', '#f59e0b', '#8b5cf6', '#cbd5e1'] : ['#e2e8f0'],
                    borderWidth: 0,
                    hoverOffset: hasData ? 4 : 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: hasData,
                        callbacks: {
                            label: function (context) {
                                const total = {{ $totalSiswa > 0 ? $totalSiswa : 1 }};
                                const value = context.parsed;
                                const pct = Math.round((value / total) * 100);
                                return ` ${context.label}: ${value} siswa (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });

        // 2. Bar Chart Distribusi RIASEC 6 Dimensi
        const ctxRiasec = document.getElementById('riasecBarChart').getContext('2d');
        new Chart(ctxRiasec, {
            type: 'bar',
            data: {
                labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: [
                        {{ $riasecDistribution['Realistic'] }},
                        {{ $riasecDistribution['Investigative'] }},
                        {{ $riasecDistribution['Artistic'] }},
                        {{ $riasecDistribution['Social'] }},
                        {{ $riasecDistribution['Enterprising'] }},
                        {{ $riasecDistribution['Conventional'] }}
                    ],
                    backgroundColor: [
                        '#3b82f6', // Realistic (Biru)
                        '#06b6d4', // Investigative (Cyan)
                        '#ec4899', // Artistic (Pink)
                        '#10b981', // Social (Hijau)
                        '#f59e0b', // Enterprising (Oranye)
                        '#8b5cf6'  // Conventional (Ungu)
                    ],
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 10,
                        ticks: {
                            precision: 0,
                            font: { size: 10 },
                            color: '#64748b'
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: {
                            font: { size: 10, weight: '600' },
                            color: '#334155'
                        },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ` ${context.parsed.y} siswa`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
