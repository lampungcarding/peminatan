@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
{{-- STUDENT GREETING & PROFILE HERO --}}
<div class="card-pro mb-4 p-4 p-md-5" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%); color:#ffffff; border:none; position:relative; overflow:hidden;">
    {{-- Decorative background circles --}}
    <div style="position:absolute; right:-40px; top:-40px; width:220px; height:220px; border-radius:50%; background:radial-gradient(circle, rgba(59,130,246,0.2) 0%, transparent 70%); pointer-events:none;"></div>
    <div style="position:absolute; right:80px; bottom:-60px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle, rgba(147,197,253,0.15) 0%, transparent 70%); pointer-events:none;"></div>

    <div class="position-relative d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                @if($user->kelas)
                    <span class="badge" style="background: rgba(255,255,255,0.18); backdrop-filter:blur(4px); font-size:0.75rem; font-weight:700; padding:6px 12px; border-radius:var(--radius-full);">
                        <i class="bi bi-mortarboard me-1"></i> Kelas {{ $user->kelas }}
                    </span>
                @endif
                @if($user->nisn)
                    <span class="badge font-monospace" style="background: rgba(255,255,255,0.18); backdrop-filter:blur(4px); font-size:0.75rem; padding:6px 12px; border-radius:var(--radius-full);">
                        NISN: {{ $user->nisn }}
                    </span>
                @endif
            </div>

            <h1 style="font-size: 1.7rem; font-weight: 800; letter-spacing: -0.02em; margin:0; line-height:1.25;">
                Halo, {{ $user->name }}! 👋
            </h1>
            <p style="font-size: 0.88rem; color: #cbd5e1; margin-top: 6px; margin-bottom: 0;">
                {{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 1') }} • Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2024/2025') }}
            </p>
        </div>

        <div>
            @if($user->is_data_lengkap)
                <span class="badge-pill-soft badge-verified-soft py-2 px-3" style="font-size:0.84rem; background:#dcfce7; color:#166534; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                    <i class="bi bi-patch-check-fill text-success fs-6"></i>
                    <span>Data Pengisian Lengkap</span>
                </span>
            @elseif($careerResult || $pilihan)
                <span class="badge-pill-soft py-2 px-3" style="font-size:0.84rem; background:#e0f2fe; color:#0369a1; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                    <i class="bi bi-hourglass-split text-info fs-6"></i>
                    <span>Pengisian Sebagian</span>
                </span>
            @else
                <span class="badge-pill-soft py-2 px-3" style="font-size:0.84rem; background:#fef3c7; color:#92400e; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                    <i class="bi bi-clock-history text-warning fs-6"></i>
                    <span>Belum Mengisi</span>
                </span>
            @endif
        </div>
    </div>
</div>

{{-- 3 STATUS TRACKER CARDS (PRD SECTION 18) --}}
<div class="row g-3 mb-4">
    {{-- Status 1: Tes Minat & Jangkar Karier --}}
    <div class="col-12 col-md-4">
        <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                    1. Tes Minat & Jangkar Karier
                </span>
                @if($careerResult)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:0.72rem;">
                        <i class="bi bi-check2-circle"></i> Selesai
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size:0.72rem;">
                        <i class="bi bi-circle"></i> Belum Dikerjakan
                    </span>
                @endif
            </div>

            <div>
                @if($careerResult)
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-primary text-white font-monospace px-2 py-1" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                            {{ $careerResult->holland_code }}
                        </span>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #0f172a;">
                            {{ $careerResult->dominant_type }}
                        </span>
                    </div>
                    <div class="mt-1">
                        <span class="badge {{ $careerResult->execution_path_badge['class'] }} px-2 py-1" style="font-size:0.7rem;">
                            {{ $careerResult->execution_path_badge['label'] }}
                        </span>
                    </div>
                @else
                    <div style="font-size: 0.88rem; font-weight: 700; color: #334155;" class="mb-1">
                        72 Pertanyaan (RIASEC + Anchors)
                    </div>
                    <div style="font-size: 0.78rem; color: #94a3b8;">
                        Ketahui Holland Code & Jalur Eksekusi Kariermu (~6-10 menit)
                    </div>
                @endif
            </div>

            <div class="mt-3 pt-2 border-top">
                @if($careerResult)
                    <a href="{{ route('tes.hasil') }}" class="btn btn-sm btn-outline-primary w-100 fw-bold" style="font-size: 0.78rem;">
                        <i class="bi bi-bar-chart-fill me-1"></i> Lihat Hasil & Rekomendasi
                    </a>
                @else
                    <a href="{{ route('tes.mulai') }}" class="btn btn-sm btn-primary w-100 fw-bold" style="font-size: 0.78rem;">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Mulai Tes Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Status 2: Rencana Setelah Lulus --}}
    <div class="col-12 col-md-4">
        <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                    2. Rencana Kelulusan
                </span>
                @if($pilihan)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:0.72rem;">
                        <i class="bi bi-check2-circle"></i> Selesai
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size:0.72rem;">
                        <i class="bi bi-circle"></i> Belum Dipilih
                    </span>
                @endif
            </div>

            <div>
                @if($pilihan)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($pilihan->rencana === 'kuliah')
                            <span class="badge-pill-soft badge-kuliah-soft py-1 px-2" style="font-size: 0.76rem;">
                                <i class="bi bi-mortarboard-fill"></i> Kuliah
                            </span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #0f172a;" class="text-truncate">
                                {{ $pilihan->nama_perguruan_tinggi }}
                            </span>
                        @elseif($pilihan->rencana === 'bekerja')
                            <span class="badge-pill-soft badge-bekerja-soft py-1 px-2" style="font-size: 0.76rem;">
                                <i class="bi bi-briefcase-fill"></i> Bekerja
                            </span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #0f172a;" class="text-truncate">
                                {{ $pilihan->bidang_pekerjaan ?? 'Dunia Kerja' }}
                            </span>
                        @else
                            <span class="badge-pill-soft badge-wirausaha-soft py-1 px-2" style="font-size: 0.76rem;">
                                <i class="bi bi-shop"></i> Berwirausaha
                            </span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #0f172a;" class="text-truncate">
                                {{ $pilihan->bidang_usaha ?? 'Wirausaha Mandiri' }}
                            </span>
                        @endif
                    </div>
                    <div style="font-size: 0.78rem; color: #64748b;" class="text-truncate">
                        @if($pilihan->rencana === 'kuliah')
                            Prodi: {{ $pilihan->nama_program_studi }}
                        @elseif($pilihan->rencana === 'bekerja')
                            {{ $pilihan->keterangan_pekerjaan ?: 'Siap memasuki dunia industri' }}
                        @else
                            {{ $pilihan->keterangan_usaha ?: 'Merintis usaha bisnis mandiri' }}
                        @endif
                    </div>
                @else
                    <div style="font-size: 0.88rem; font-weight: 700; color: #334155;" class="mb-1">
                        Kuliah / Bekerja / Usaha
                    </div>
                    <div style="font-size: 0.78rem; color: #94a3b8;">
                        Pilih arah masa depanmu setelah lulus kelas 12
                    </div>
                @endif
            </div>

            <div class="mt-3 pt-2 border-top">
                @if($pilihan)
                    <a href="{{ route('siswa.pilihan-saya') }}" class="btn btn-sm btn-outline-primary w-100 fw-bold" style="font-size: 0.78rem;">
                        <i class="bi bi-file-earmark-person me-1"></i> Lihat Bukti Pilihan
                    </a>
                @elseif($careerResult)
                    <a href="{{ route('siswa.rencana') }}" class="btn btn-sm btn-outline-primary w-100 fw-bold" style="font-size: 0.78rem;">
                        <i class="bi bi-compass me-1"></i> Tentukan Rencana Sekarang
                    </a>
                @else
                    <a href="{{ route('tes.index') }}" class="btn btn-sm btn-warning text-dark w-100 fw-bold" style="font-size: 0.78rem;">
                        <i class="bi bi-lock-fill me-1"></i> Wajib Tes Minat Dulu
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Status 3: Status Pengisian & Profil --}}
    <div class="col-12 col-md-4">
        <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                    3. Profil Kelengkapan
                </span>
                @if($user->is_data_lengkap)
                    <span class="badge bg-success text-white px-2 py-1" style="font-size:0.72rem;">
                        <i class="bi bi-shield-check"></i> Lengkap 100%
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size:0.72rem;">
                        {{ ($careerResult ? 50 : 0) + ($pilihan ? 50 : 0) }}% Selesai
                    </span>
                @endif
            </div>

            <div>
                <div class="progress mb-2" style="height: 8px; border-radius: 4px;">
                    @php
                        $progressPct = ($careerResult ? 50 : 0) + ($pilihan ? 50 : 0);
                    @endphp
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPct }}%" aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div style="font-size: 0.78rem; color: #64748b;">
                    @if($user->is_data_lengkap)
                        Seluruh berkas dan profil perencanaan telah lengkap.
                    @else
                        Lengkapi kedua tahap di samping untuk menyelesaikan pendataan sekolah.
                    @endif
                </div>
            </div>

            <div class="mt-3 pt-2 border-top">
                <a href="{{ route('siswa.pilihan-saya') }}" class="btn btn-sm btn-outline-secondary w-100 fw-bold" style="font-size: 0.78rem;">
                    <i class="bi bi-person-lines-fill me-1"></i> Profil Digital Karier & Studi
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SECTION DETAIL BERDASARKAN PROGRES SISWA --}}
@if($careerResult)
    {{-- RINGKASAN GRAFIK RIASEC JIKA SUDAH TES --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card-pro h-100">
                <div class="card-pro-header">
                    <div>
                        <span style="font-size: 0.74rem; font-weight:700; color:#64748b; text-transform:uppercase;">Visualisasi Minat</span>
                        <h2 style="font-size: 1.15rem; font-weight:800; color:#0f172a; margin:0;">Grafik Radar RIASEC</h2>
                    </div>
                    <div>
                        <span class="badge bg-primary-subtle text-primary font-monospace fw-bold px-2 py-1" style="font-size: 0.85rem;">
                            Holland Code: {{ $careerResult->holland_code }}
                        </span>
                    </div>
                </div>
                <div class="card-pro-body">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="studentRadarChart"></canvas>
                    </div>

                    <div class="mt-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-lightbulb-fill text-warning fs-5"></i>
                            <span style="font-size: 0.88rem; font-weight: 700; color: #0f172a;">
                                Tipe Dominan: {{ $careerResult->dominant_type }}
                            </span>
                        </div>
                        <p style="font-size: 0.82rem; color: #475569; margin: 0; line-height: 1.5;">
                            {{ $careerResult->dominant_description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card-pro h-100 d-flex flex-column justify-content-between">
                <div class="card-pro-header">
                    <div>
                        <span style="font-size: 0.74rem; font-weight:700; color:#64748b; text-transform:uppercase;">Peta Nilai</span>
                        <h2 style="font-size: 1.15rem; font-weight:800; color:#0f172a; margin:0;">Skor 6 Dimensi Holland</h2>
                    </div>
                    <a href="{{ route('tes.rekomendasi') }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.78rem;">
                        <i class="bi bi-stars"></i> Rekomendasi
                    </a>
                </div>
                <div class="card-pro-body">
                    <div class="d-flex flex-column gap-3">
                        @foreach($careerResult->scores_map as $dimCode => $dim)
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width: 22px; height: 22px; border-radius: 4px; background: {{ $dim['color'] }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800;">
                                            {{ $dimCode }}
                                        </span>
                                        <span style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">
                                            {{ $dim['name'] }}
                                        </span>
                                        <span style="font-size: 0.75rem; color: #94a3b8;">
                                            ({{ $dim['label'] }})
                                        </span>
                                    </div>
                                    <span style="font-size: 0.88rem; font-weight: 800; color: #0f172a;">
                                        {{ $dim['score'] }} / {{ $dim['max_score'] }} <span style="font-size:0.74rem; color:#64748b; font-weight:600;">({{ number_format($dim['percentage'], 1) }}%)</span>
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 3px; background: #f1f5f9;">
                                    <div class="progress-bar" style="width: {{ $dim['percentage'] }}%; background-color: {{ $dim['color'] }}; border-radius: 3px;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-3 bg-light border-top text-center" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <span class="text-muted" style="font-size: 0.78rem;">
                        <i class="bi bi-info-circle me-1 text-primary"></i> Untuk mengulang atau reset hasil tes, silakan <strong>hubungi Guru BK</strong>.
                    </span>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- ONBOARDING CTA BANNER: BELUM TES MINAT --}}
    <div class="card-pro mb-4 p-4 p-md-5" style="border-left: 5px solid var(--brand-primary);">
        <div class="row align-items-center g-4">
            <div class="col-12 col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary text-white py-1 px-2" style="font-size:0.75rem;">Langkah 1 Disarankan</span>
                    <span style="font-size:0.82rem; font-weight:600; color:#64748b;">Tes Minat Karier Digital</span>
                </div>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                    Kenali Potensi & Arah Minatmu Sebelum Menentukan Rencana
                </h2>
                <p style="font-size: 0.88rem; color: #475569; margin: 0; line-height: 1.6;">
                    Ikuti Tes Minat Karier Digital berbasis teori <strong>Holland (RIASEC)</strong>. Sistem akan menganalisis kepribadian kerjamu, memberikan <strong>Holland Code 3 huruf</strong>, serta rekomendasi program studi, profesi, dan bidang usaha yang sesuai.
                </p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="{{ route('tes.mulai') }}" class="btn-brand-primary w-100 py-3 text-center">
                    <i class="bi bi-lightning-charge-fill"></i> Mulai Tes Minat Sekarang
                </a>
            </div>
        </div>
    </div>
@endif

@if(!$pilihan)
    {{-- CTA PILIH RENCANA JIKA BELUM MEMILIH --}}
    <div class="card-pro p-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border: 1.5px dashed #bfdbfe;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 mb-2" style="font-size: 0.75rem;">
                    Langkah 2: Rencana Setelah Lulus
                </span>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                    Sudah Punya Keputusan Rencana Masa Depan?
                </h3>
                <p style="font-size: 0.85rem; color: #475569; margin-top: 4px; margin-bottom: 0;">
                    Tentukan apakah kamu berencana melanjutkan ke Perguruan Tinggi, langsung Bekerja di Industri, atau Berwirausaha mandiri.
                </p>
            </div>
            <div>
                <a href="{{ route('siswa.rencana') }}" class="btn btn-primary fw-bold px-4 py-2" style="border-radius: var(--radius-md); font-size: 0.88rem; white-space: nowrap;">
                    <i class="bi bi-compass-fill me-1"></i> Tentukan Rencana Sekarang
                </a>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
@if($careerResult)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctxRadar = document.getElementById('studentRadarChart');
        if (ctxRadar) {
            new Chart(ctxRadar.getContext('2d'), {
                type: 'radar',
                data: {
                    labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
                    datasets: [{
                        label: 'Persentase Minat (%)',
                        data: [
                            {{ number_format(($careerResult->scores_map['R']['percentage'] ?? 0), 1) }},
                            {{ number_format(($careerResult->scores_map['I']['percentage'] ?? 0), 1) }},
                            {{ number_format(($careerResult->scores_map['A']['percentage'] ?? 0), 1) }},
                            {{ number_format(($careerResult->scores_map['S']['percentage'] ?? 0), 1) }},
                            {{ number_format(($careerResult->scores_map['E']['percentage'] ?? 0), 1) }},
                            {{ number_format(($careerResult->scores_map['C']['percentage'] ?? 0), 1) }}
                        ],
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        borderColor: '#2563eb',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#2563eb',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                color: '#e2e8f0'
                            },
                            grid: {
                                color: '#f1f5f9'
                            },
                            pointLabels: {
                                font: {
                                    size: 11,
                                    weight: '700',
                                    family: "'Plus Jakarta Sans', sans-serif"
                                },
                                color: '#334155'
                            },
                            ticks: {
                                beginAtZero: true,
                                max: 100,
                                stepSize: 20,
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endif
@endpush
