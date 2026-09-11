@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
{{-- STUDENT GREETING & PROFILE HERO (Point 1) --}}
<div class="card-pro mb-4 p-4 p-md-5" style="background: #1F355F; color: #ffffff; border: 1px solid #1F355F; border-radius: 12px; position: relative; overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
    <div class="position-relative d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                @if($user->kelas)
                    <span class="badge" style="background: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.18); font-size: 0.78rem; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                        Kelas {{ $user->kelas }}
                    </span>
                @endif
                @if($user->nisn)
                    <span class="badge font-monospace" style="background: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.18); font-size: 0.78rem; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                        NISN: {{ $user->nisn }}
                    </span>
                @endif
            </div>

            <h1 style="font-size: 1.65rem; font-weight: 700; letter-spacing: -0.01em; margin: 0; line-height: 1.25;">
                Halo, {{ $user->name }}! 👋
            </h1>
            <p style="font-size: 0.88rem; color: #E4E7EC; margin-top: 6px; margin-bottom: 0;">
                {{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 1') }} • Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2024/2025') }}
            </p>
        </div>

        <div>
            @if($user->is_data_lengkap)
                <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: 6px;">
                    <i class="bi bi-check-circle-fill me-1"></i> Data Pengisian Lengkap
                </span>
            @elseif($careerResult || $pilihan)
                <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: 6px;">
                    <i class="bi bi-clock-history me-1"></i> Pengisian Sebagian
                </span>
            @else
                <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: 6px;">
                    <i class="bi bi-exclamation-circle me-1"></i> Belum Mengisi
                </span>
            @endif
        </div>
    </div>
</div>

{{-- 3 STATUS TRACKER CARDS (Point 4 & 5) --}}
<div class="row g-3 mb-4">
    {{-- Status 1: Tes Minat & Jangkar Karier --}}
    <div class="col-12 col-md-4">
        <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between" style="background:#ffffff; border:1px solid #E4E7EC; border-radius:12px; box-shadow:0 1px 2px rgba(16,24,40,0.04);">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size: 0.75rem; font-weight: 600; color: #667085; text-transform: uppercase;">
                    1. Tes Minat & Jangkar Karier
                </span>
                @if($careerResult)
                    <span class="badge" style="background: #F0FDF4; color: #276749; border: 1px solid #B7E4C7; border-radius: 6px; font-size: 0.72rem; font-weight: 600; padding: 4px 8px;">
                        <i class="bi bi-check2"></i> Selesai
                    </span>
                @else
                    <span class="badge" style="background: #FFF7E6; color: #8A6116; border: 1px solid #F1D99B; border-radius: 6px; font-size: 0.72rem; font-weight: 600; padding: 4px 8px;">
                        Belum Dikerjakan
                    </span>
                @endif
            </div>

            <div>
                @if($careerResult)
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge font-monospace" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.82rem; font-weight: 700; padding: 4px 8px;">
                            {{ $careerResult->holland_code }}
                        </span>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #1F2937;">
                            {{ $careerResult->dominant_type }}
                        </span>
                    </div>
                    <div class="mt-1">
                        <span class="badge" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.7rem; font-weight: 600; padding: 3px 6px;">
                            {{ $careerResult->execution_path_badge['label'] }}
                        </span>
                    </div>
                @else
                    <div style="font-size: 0.88rem; font-weight: 700; color: #1F2937;" class="mb-1">
                        72 Pertanyaan (RIASEC + Anchors)
                    </div>
                    <div style="font-size: 0.78rem; color: #667085;">
                        Ketahui Holland Code & Jalur Eksekusi Kariermu (~6-10 menit)
                    </div>
                @endif
            </div>

            <div class="mt-3 pt-2 border-top" style="border-color: #E4E7EC !important;">
                @if($careerResult)
                    <a href="{{ route('tes.hasil') }}" class="btn-brand-outline w-100 text-center py-2" style="font-size: 0.78rem; min-height:36px;">
                        <i class="bi bi-bar-chart-fill me-1"></i> Lihat Hasil & Rekomendasi
                    </a>
                @else
                    <a href="{{ route('tes.mulai') }}" class="btn-brand-primary w-100 text-center py-2" style="font-size: 0.78rem; min-height:36px;">
                        <i class="bi bi-lightning-charge me-1"></i> Mulai Tes Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Status 2: Rencana Setelah Lulus --}}
    <div class="col-12 col-md-4">
        <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between" style="background:#ffffff; border:1px solid #E4E7EC; border-radius:12px; box-shadow:0 1px 2px rgba(16,24,40,0.04);">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size: 0.75rem; font-weight: 600; color: #667085; text-transform: uppercase;">
                    2. Rencana Kelulusan
                </span>
                @if($pilihan)
                    <span class="badge" style="background: #F0FDF4; color: #276749; border: 1px solid #B7E4C7; border-radius: 6px; font-size: 0.72rem; font-weight: 600; padding: 4px 8px;">
                        <i class="bi bi-check2"></i> Selesai
                    </span>
                @else
                    <span class="badge" style="background: #FFF7E6; color: #8A6116; border: 1px solid #F1D99B; border-radius: 6px; font-size: 0.72rem; font-weight: 600; padding: 4px 8px;">
                        Belum Dipilih
                    </span>
                @endif
            </div>

            <div>
                @if($pilihan)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($pilihan->rencana === 'kuliah')
                            <span class="badge" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.76rem; font-weight: 600; padding: 4px 8px;">
                                <i class="bi bi-mortarboard me-1"></i> Kuliah
                            </span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #1F2937;" class="text-truncate">
                                {{ $pilihan->nama_perguruan_tinggi }}
                            </span>
                        @elseif($pilihan->rencana === 'bekerja')
                            <span class="badge" style="background: #FFF7E6; color: #8A6116; border: 1px solid #F1D99B; border-radius: 6px; font-size: 0.76rem; font-weight: 600; padding: 4px 8px;">
                                <i class="bi bi-briefcase me-1"></i> Bekerja
                            </span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #1F2937;" class="text-truncate">
                                {{ $pilihan->bidang_pekerjaan ?? 'Dunia Kerja' }}
                            </span>
                        @else
                            <span class="badge" style="background: #F4F3FF; color: #5925DC; border: 1px solid #D9D6FE; border-radius: 6px; font-size: 0.76rem; font-weight: 600; padding: 4px 8px;">
                                <i class="bi bi-shop me-1"></i> Berwirausaha
                            </span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #1F2937;" class="text-truncate">
                                {{ $pilihan->bidang_usaha ?? 'Wirausaha Mandiri' }}
                            </span>
                        @endif
                    </div>
                    <div style="font-size: 0.78rem; color: #667085;" class="text-truncate">
                        @if($pilihan->rencana === 'kuliah')
                            Prodi: {{ $pilihan->nama_program_studi }}
                        @elseif($pilihan->rencana === 'bekerja')
                            {{ $pilihan->keterangan_pekerjaan ?: 'Siap memasuki dunia industri' }}
                        @else
                            {{ $pilihan->keterangan_usaha ?: 'Merintis usaha bisnis mandiri' }}
                        @endif
                    </div>
                @else
                    <div style="font-size: 0.88rem; font-weight: 700; color: #1F2937;" class="mb-1">
                        Kuliah / Bekerja / Usaha
                    </div>
                    <div style="font-size: 0.78rem; color: #667085;">
                        Pilih arah masa depanmu setelah lulus kelas 12
                    </div>
                @endif
            </div>

            <div class="mt-3 pt-2 border-top" style="border-color: #E4E7EC !important;">
                @if($pilihan)
                    <a href="{{ route('siswa.pilihan-saya') }}" class="btn-brand-outline w-100 text-center py-2" style="font-size: 0.78rem; min-height:36px;">
                        <i class="bi bi-file-earmark-person me-1"></i> Lihat Bukti Pilihan
                    </a>
                @elseif($careerResult)
                    <a href="{{ route('siswa.rencana') }}" class="btn-brand-primary w-100 text-center py-2" style="font-size: 0.78rem; min-height:36px;">
                        <i class="bi bi-compass me-1"></i> Tentukan Rencana Sekarang
                    </a>
                @else
                    <a href="{{ route('tes.index') }}" class="btn-brand-outline w-100 text-center py-2" style="font-size: 0.78rem; min-height:36px;">
                        <i class="bi bi-lock me-1"></i> Wajib Tes Minat Dulu
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Status 3: Status Pengisian & Profil --}}
    <div class="col-12 col-md-4">
        <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between" style="background:#ffffff; border:1px solid #E4E7EC; border-radius:12px; box-shadow:0 1px 2px rgba(16,24,40,0.04);">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size: 0.75rem; font-weight: 600; color: #667085; text-transform: uppercase;">
                    3. Profil Kelengkapan
                </span>
                @if($user->is_data_lengkap)
                    <span class="badge" style="background: #F0FDF4; color: #276749; border: 1px solid #B7E4C7; border-radius: 6px; font-size: 0.72rem; font-weight: 600; padding: 4px 8px;">
                        Lengkap 100%
                    </span>
                @else
                    <span class="badge" style="background: #FFF7E6; color: #8A6116; border: 1px solid #F1D99B; border-radius: 6px; font-size: 0.72rem; font-weight: 600; padding: 4px 8px;">
                        {{ ($careerResult ? 50 : 0) + ($pilihan ? 50 : 0) }}% Selesai
                    </span>
                @endif
            </div>

            <div>
                <div class="progress mb-2" style="height: 6px; border-radius: 3px; background: #F2F4F7;">
                    @php
                        $progressPct = ($careerResult ? 50 : 0) + ($pilihan ? 50 : 0);
                    @endphp
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPct }}%" aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div style="font-size: 0.78rem; color: #667085;">
                    @if($user->is_data_lengkap)
                        Seluruh berkas dan profil perencanaan telah lengkap.
                    @else
                        Lengkapi kedua tahap di samping untuk menyelesaikan pendataan sekolah.
                    @endif
                </div>
            </div>

            <div class="mt-3 pt-2 border-top" style="border-color: #E4E7EC !important;">
                <a href="{{ route('siswa.pilihan-saya') }}" class="btn-brand-outline w-100 text-center py-2" style="font-size: 0.78rem; min-height:36px;">
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
                        <span style="font-size: 0.74rem; font-weight:600; color:#667085; text-transform:uppercase;">Visualisasi Minat</span>
                        <h2 style="font-size: 1.15rem; font-weight:700; color:#1F2937; margin:0;">Grafik Radar RIASEC</h2>
                    </div>
                    <div>
                        <span class="badge font-monospace" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.82rem; font-weight: 700; padding: 4px 8px;">
                            Holland Code: {{ $careerResult->holland_code }}
                        </span>
                    </div>
                </div>
                <div class="card-pro-body">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="studentRadarChart"></canvas>
                    </div>

                    <div class="mt-3 p-3" style="background: #F7F8FA; border: 1px solid #E4E7EC; border-radius: 8px;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-lightbulb" style="color: #B7791F; font-size: 1.1rem;"></i>
                            <span style="font-size: 0.88rem; font-weight: 700; color: #1F2937;">
                                Tipe Dominan: {{ $careerResult->dominant_type }}
                            </span>
                        </div>
                        <p style="font-size: 0.82rem; color: #667085; margin: 0; line-height: 1.5;">
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
                        <span style="font-size: 0.74rem; font-weight:600; color:#667085; text-transform:uppercase;">Peta Nilai</span>
                        <h2 style="font-size: 1.15rem; font-weight:700; color:#1F2937; margin:0;">Skor 6 Dimensi Holland</h2>
                    </div>
                    <a href="{{ route('tes.rekomendasi') }}" class="btn-brand-outline py-1 px-3" style="font-size: 0.78rem; min-height:34px;">
                        <i class="bi bi-stars"></i> Rekomendasi
                    </a>
                </div>
                <div class="card-pro-body">
                    <div class="d-flex flex-column gap-3">
                        @foreach($careerResult->scores_map as $dimCode => $dim)
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width: 22px; height: 22px; border-radius: 4px; background: #3157A4; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700;">
                                            {{ $dimCode }}
                                        </span>
                                        <span style="font-size: 0.85rem; font-weight: 700; color: #1F2937;">
                                            {{ $dim['name'] }}
                                        </span>
                                        <span style="font-size: 0.75rem; color: #667085;">
                                            ({{ $dim['label'] }})
                                        </span>
                                    </div>
                                    <span style="font-size: 0.88rem; font-weight: 700; color: #1F2937;">
                                        {{ $dim['score'] }} / {{ $dim['max_score'] }} <span style="font-size:0.74rem; color:#667085; font-weight:500;">({{ number_format($dim['percentage'], 1) }}%)</span>
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 3px; background: #F2F4F7;">
                                    <div class="progress-bar" style="width: {{ $dim['percentage'] }}%; background-color: #3157A4; border-radius: 3px;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-3 text-center" style="background: #F7F8FA; border-top: 1px solid #E4E7EC;">
                    <span style="font-size: 0.78rem; color: #667085;">
                        <i class="bi bi-info-circle me-1" style="color:#3157A4;"></i> Untuk mengulang atau reset hasil tes, silakan <strong>hubungi Guru BK</strong>.
                    </span>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- ONBOARDING CTA BANNER: BELUM TES MINAT (Point 6) --}}
    <div class="card-pro mb-4 p-4 p-md-5" style="background: #FFFFFF; border: 1px solid #E4E7EC; border-left: 4px solid #3157A4; border-radius: 12px; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
        <div class="row align-items-center g-4">
            <div class="col-12 col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.75rem; font-weight: 600; padding: 4px 10px;">
                        Langkah 1 Disarankan
                    </span>
                </div>
                <h2 style="font-size: 1.35rem; font-weight: 700; color: #1F2937; margin-bottom: 8px;">
                    Kenali Potensi & Arah Minatmu
                </h2>
                <p style="font-size: 0.88rem; color: #667085; margin: 0; line-height: 1.6;">
                    Ikuti Tes Minat Karier Digital untuk mengetahui kecenderungan minat dan arah kariermu.
                </p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="{{ route('tes.mulai') }}" class="btn-brand-primary w-100 py-2 px-4 text-center">
                    <i class="bi bi-lightning-charge"></i> Mulai Tes Minat
                </a>
            </div>
        </div>
    </div>
@endif

@if(!$pilihan)
    {{-- CTA PILIH RENCANA JIKA BELUM MEMILIH (Point 10) --}}
    <div class="card-pro p-4 mb-4" style="background: #FFFFFF; border: 1px solid #E4E7EC; border-radius: 12px; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <span class="badge mb-2" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.75rem; font-weight: 600; padding: 4px 10px;">
                    Langkah 2: Rencana Setelah Lulus
                </span>
                <h3 style="font-size: 1.15rem; font-weight: 700; color: #1F2937; margin: 0;">
                    Sudah Punya Keputusan Rencana Masa Depan?
                </h3>
                <p style="font-size: 0.85rem; color: #667085; margin-top: 4px; margin-bottom: 0;">
                    Tentukan apakah kamu berencana melanjutkan ke Perguruan Tinggi, langsung Bekerja di Industri, atau Berwirausaha mandiri.
                </p>
            </div>
            <div>
                <a href="{{ route('siswa.rencana') }}" class="btn-brand-primary py-2 px-4" style="white-space: nowrap;">
                    <i class="bi bi-compass me-1"></i> Tentukan Rencana Sekarang
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
                        backgroundColor: 'rgba(49, 87, 164, 0.15)',
                        borderColor: '#3157A4',
                        borderWidth: 2,
                        pointBackgroundColor: '#3157A4',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#3157A4',
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
                                color: '#E4E7EC'
                            },
                            grid: {
                                color: '#F7F8FA'
                            },
                            pointLabels: {
                                font: {
                                    size: 11,
                                    weight: '600',
                                    family: "'Plus Jakarta Sans', sans-serif"
                                },
                                color: '#667085'
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
