@extends('layouts.app')

@section('title', 'Hasil Tes Minat Karier (Holland / RIASEC)')

@push('styles')
<style>
    /* =========================================================
       PRINT STYLES FOR HASIL TES RIASEC (A4 PORTRAIT)
       ========================================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }

        body {
            background: #ffffff !important;
            color: #0f172a !important;
            font-size: 9pt !important;
            line-height: 1.35 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .student-topbar,
        .mobile-bottom-nav,
        .btn,
        button,
        .alert,
        .d-print-none {
            display: none !important;
        }

        .student-main-content,
        .container,
        .row,
        .col-12,
        .col-lg-10 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            flex: 0 0 100% !important;
        }

        .card-pro {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            margin-bottom: 10px !important;
            background: #ffffff !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* 1. KOP SURAT */
        .print-kop-wrapper {
            display: block !important;
            text-align: center;
            margin-bottom: 12px !important;
            padding-bottom: 8px !important;
            border-bottom: 2.5px solid #0f172a;
            position: relative;
        }
        .print-kop-wrapper::after {
            content: '';
            display: block;
            border-bottom: 1px solid #0f172a;
            margin-top: 2px;
        }
        .print-kop-instansi {
            font-size: 8.5pt !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            color: #475569 !important;
        }
        .print-kop-sekolah {
            font-size: 13pt !important;
            font-weight: 900 !important;
            letter-spacing: 0.04em !important;
            text-transform: uppercase !important;
            color: #0f172a !important;
            margin: 2px 0 !important;
        }
        .print-kop-title {
            font-size: 10pt !important;
            font-weight: 800 !important;
            color: #1e3a8a !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
        }
        .print-kop-sub {
            font-size: 8pt !important;
            color: #64748b !important;
        }

        /* 2. IDENTITAS SISWA */
        .print-student-info {
            display: block !important;
            background: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 8px 12px !important;
            margin-bottom: 10px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* 3. HOLLAND CODE HERO */
        .holland-hero-card {
            background: #f0f7ff !important;
            color: #1e3a8a !important;
            border: 1.5px solid #3b82f6 !important;
            padding: 10px 16px !important;
            margin-bottom: 10px !important;
            text-align: center !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .holland-hero-card .glow-circle {
            display: none !important;
        }
        .holland-hero-card .hero-badge {
            background: #dbeafe !important;
            color: #1d4ed8 !important;
            border: 1px solid #93c5fd !important;
            font-size: 7.5pt !important;
            padding: 2px 8px !important;
        }
        .holland-hero-card .hero-code-val {
            font-size: 2.2rem !important;
            font-weight: 900 !important;
            color: #1e3a8a !important;
            margin: 2px 0 !important;
            text-shadow: none !important;
        }
        .holland-hero-card .hero-dominant-pill {
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 8.5pt !important;
            padding: 3px 12px !important;
        }

        /* 4. TIPE DOMINAN CARD */
        .dominant-desc-card {
            padding: 10px 14px !important;
            margin-bottom: 10px !important;
            border-left: 4px solid #2563eb !important;
            background: #ffffff !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* 5. DUA KOLOM CHART & SKOR (SIDE-BY-SIDE DALAM PRINT) */
        .print-chart-grid {
            display: flex !important;
            flex-direction: row !important;
            gap: 10px !important;
            margin-bottom: 10px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .print-chart-left {
            width: 48% !important;
            flex: 0 0 48% !important;
            max-width: 48% !important;
        }
        .print-chart-right {
            width: 52% !important;
            flex: 0 0 52% !important;
            max-width: 52% !important;
        }
        .radar-canvas-box {
            height: 195px !important;
            width: 100% !important;
        }
        .score-row-item {
            margin-bottom: 4px !important;
        }
        .score-row-bar {
            height: 5px !important;
            background: #f1f5f9 !important;
        }

        /* 6. REKOMENDASI 3 KOLOM SEJAJAR */
        .print-rec-wrapper {
            margin-bottom: 10px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .print-rec-grid {
            display: flex !important;
            flex-direction: row !important;
            gap: 8px !important;
        }
        .print-rec-col {
            width: 33.333% !important;
            flex: 0 0 33.333% !important;
            max-width: 33.333% !important;
            padding: 8px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            background: #f8fafc !important;
        }

        /* 7. SIGNATURE BLOCK */
        .print-signature-block {
            display: block !important;
            margin-top: 16px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">

        {{-- ACTION BAR (TOMBOL KEMBALI & CETAK - SCREEN ONLY) --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
            <div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Dashboard Siswa
                </a>
                <span style="font-size: 0.88rem; font-weight: 700; color: #475569;">
                    Hasil Tes Minat Karier (Holland / RIASEC)
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('tes.rekomendasi') }}" class="btn btn-outline-primary btn-sm fw-bold">
                    <i class="bi bi-stars me-1"></i> Rekomendasi Lengkap
                </a>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2" onclick="window.print()" style="border-radius: var(--radius-md);">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Hasil Tes (PDF)
                </button>
            </div>
        </div>

        {{-- OFFICIAL KOP SURAT (PRINT ONLY) --}}
        <div class="print-kop-wrapper d-none d-print-block">
            <div class="print-kop-instansi">Pemerintah Provinsi Lampung
            </div>
            <div class="print-kop-sekolah">
                {{ \App\Models\Setting::get('nama_sekolah', 'SMK NEGERI 1 BANDAR LAMPUNG') }}
            </div>
            <div class="print-kop-title">
                LEMBAR HASIL ASESMEN MINAT KARIER & KEPRIBADIAN (HOLLAND / RIASEC)
            </div>
            <div class="print-kop-sub">
                Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2024/2025') }} • Sistem Perencanaan Karier & Studi Siswa
            </div>
        </div>

        {{-- DATA IDENTITAS SISWA (PRINT & SCREEN OPTIMIZED) --}}
        @php
            $student = $user ?? auth()->user();
        @endphp
        <div class="card-pro print-student-info p-3 mb-3">
            <div class="row g-2 align-items-center" style="font-size: 0.86rem;">
                <div class="col-12 col-sm-4">
                    <div class="text-muted" style="font-size: 0.74rem;">Nama Lengkap Siswa:</div>
                    <div class="fw-bold text-dark" style="font-size: 0.98rem;">{{ $student->name }}</div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="text-muted" style="font-size: 0.74rem;">NISN:</div>
                    <div class="fw-bold text-dark font-monospace" style="font-size: 0.92rem;">{{ $student->nisn ?? '-' }}</div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="text-muted" style="font-size: 0.74rem;">Kelas / Konsentrasi:</div>
                    <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $student->kelas ?? '-' }}</div>
                </div>
                <div class="col-12 col-sm-2 text-sm-end">
                    <div class="text-muted" style="font-size: 0.74rem;">Tanggal Asesmen:</div>
                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">
                        {{ $careerResult->created_at ? $careerResult->created_at->translatedFormat('d/m/Y') : date('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- HOLLAND CODE HERO BANNER --}}
        <div class="card-pro holland-hero-card p-4 p-md-5 mb-4 text-center" style="background: #1F355F; color:#ffffff; border:1px solid #1F355F; border-radius: 12px; position:relative; overflow:hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
            <div class="position-relative">
                <span class="badge hero-badge" style="background: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.18); font-size: 0.78rem; font-weight: 600; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                    Profil Minat Holland / RIASEC
                </span>

                <div class="my-3">
                    <div class="hero-code-title" style="font-size: 0.88rem; color: #E4E7EC; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">
                        Holland Code Kamu
                    </div>
                    <div class="hero-code-val" style="font-size: 3.2rem; font-weight: 800; letter-spacing: 0.15em; font-family: monospace; color: #ffffff;">
                        {{ $careerResult->holland_code }}
                    </div>
                </div>

                <div class="hero-dominant-pill d-inline-flex align-items-center gap-2 px-3 py-2" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); font-size: 0.92rem; font-weight: 600; border-radius: 6px;">
                    <i class="bi bi-award text-warning"></i>
                    <span>Tipe Dominan: {{ $careerResult->dominant_type }}</span>
                    @if($careerResult->secondary_types)
                        <span class="text-white-50 d-print-inline">• Didukung {{ $careerResult->secondary_types }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- PENJELASAN TIPE DOMINAN (PRD SECTION 11 & 29) --}}
        <div class="card-pro dominant-desc-card p-4 mb-4" style="border-left: 5px solid #2563eb;">
            <div class="d-flex align-items-start gap-3">
                <div class="d-print-none" style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <div class="w-100">
                    <h2 style="font-size: 1.12rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                        Tentang Tipe Minatmu: {{ $careerResult->dominant_type }}
                    </h2>
                    <p style="font-size: 0.9rem; color: #334155; line-height: 1.6; margin-bottom: 8px;">
                        {{ $careerResult->dominant_description }}
                    </p>
                    <div class="p-2 px-3 rounded-2 info-note" style="background: #f8fafc; font-size: 0.78rem; color: #64748b; border: 1px solid #e2e8f0;">
                        <i class="bi bi-info-circle me-1"></i>
                        <em>Catatan Eksplorasi:</em> Hasil asesmen ini merupakan instrumen pendukung bimbingan karier untuk memetakan keselarasan minat terhadap studi lanjut, dunia kerja, maupun wirausaha.
                    </div>
                </div>
            </div>
        </div>

        {{-- REKOMENDASI HASIL ANALISIS KOLABORASI (RIASEC + CAREER ANCHORS) --}}
        @if(!empty($collaborationReport))
        <div class="card-pro p-4 mb-4" style="background: #1F355F; color: #ffffff; border-radius: 12px; border: 1px solid #1F355F;">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-diagram-3 text-white fs-4"></i>
                <h2 style="font-size: 1.15rem; font-weight: 700; color: #ffffff; margin: 0;">
                    Rekomendasi Hasil Analisis Kolaborasi (RIASEC + Career Anchors)
                </h2>
            </div>

            <div class="p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(8px);">
                <p style="font-size: 0.95rem; font-weight: 600; color: #f8fafc; margin-bottom: 8px;">
                    {{ $collaborationReport['greeting'] ?? '' }}
                </p>

                <div class="d-flex align-items-center flex-wrap gap-2 my-2">
                    <span class="badge fs-6 px-3 py-2 {{ $careerResult->execution_path_badge['class'] }}">
                        <i class="bi {{ $careerResult->execution_path_badge['icon'] }} me-1"></i>
                        Jalur Utama: {{ $collaborationReport['anchor_recommendation_title'] ?? $careerResult->execution_path_badge['label'] }}
                    </span>
                </div>

                <p style="font-size: 0.88rem; color: #cbd5e1; line-height: 1.6; margin-top: 10px; margin-bottom: 0;">
                    {{ $collaborationReport['reason_narrative'] ?? '' }}
                </p>
            </div>

            @if(!empty($collaborationReport['ideas']))
                <div class="mt-3">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px;">
                        💼 Ide Wirausaha / Karier / Studi Mandiri Terbaik:
                    </div>
                    <div class="row g-2">
                        @foreach($collaborationReport['ideas'] as $idea)
                            <div class="col-12 col-md-6">
                                <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12);">
                                    <div class="fw-bold text-white mb-1" style="font-size: 0.92rem;">
                                        <i class="bi bi-lightbulb text-warning me-1"></i> {{ $idea['title'] }}
                                    </div>
                                    <div style="font-size: 0.8rem; color: #94a3b8; line-height: 1.45;">
                                        {{ $idea['desc'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        @endif

        {{-- RINCIAN 8 ANGKET JANGKAR KARIER (CAREER ANCHORS - SCHEIN) --}}
        <div class="card-pro p-4 mb-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
                <div>
                    <span style="font-size: 0.72rem; font-weight:700; color:#f59e0b; text-transform:uppercase;">Model Edgar Schein</span>
                    <h3 style="font-size: 1.05rem; font-weight:800; color:#0f172a; margin:0;">Profil 8 Jangkar Karier (Career Anchors)</h3>
                </div>
                <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.82rem; font-weight:700;">
                    Jangkar Utama: {{ $careerResult->dominant_anchor_name ?? $careerResult->dominant_anchor }}
                </span>
            </div>

            <div class="row g-2">
                @foreach($careerResult->anchor_scores_map as $ancCode => $anc)
                    <div class="col-12 col-md-6">
                        <div class="p-2 px-3 rounded-3 border" style="background: {{ $ancCode === $careerResult->dominant_anchor ? '#fffbeb' : '#ffffff' }}; border-color: {{ $ancCode === $careerResult->dominant_anchor ? '#f59e0b' : '#e2e8f0' }} !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background: {{ $anc['color'] }}; font-size: 0.72rem;">{{ $ancCode }}</span>
                                    <span style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">{{ $anc['label'] }}</span>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">
                                    {{ $anc['score'] }} <span style="font-size:0.7rem; color:#94a3b8;">/ 15</span>
                                </span>
                            </div>
                            <div class="progress mt-1" style="height: 4px; background: #f1f5f9;">
                                <div class="progress-bar" style="width: {{ min(100, ($anc['score'] / 15) * 100) }}%; background-color: {{ $anc['color'] }};"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ROW OF 2 CHARTS: RADAR & SCORE MAP (PRINT-GRID SIDE BY SIDE) --}}
        <div class="row g-4 mb-4 print-chart-grid">
            {{-- Radar Chart --}}
            <div class="col-12 col-lg-6 print-chart-left">
                <div class="card-pro h-100">
                    <div class="card-pro-header pb-2">
                        <div>
                            <span style="font-size: 0.72rem; font-weight:700; color:#64748b; text-transform:uppercase;">Visualisasi Radar</span>
                            <h3 style="font-size: 1.05rem; font-weight:800; color:#0f172a; margin:0;">Grafik Profil 6 Dimensi</h3>
                        </div>
                    </div>
                    <div class="card-pro-body pt-2">
                        <div class="radar-canvas-box" style="position: relative; height: 280px; width: 100%;">
                            <canvas id="radarChartHasil"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Score Breakdown List --}}
            <div class="col-12 col-lg-6 print-chart-right">
                <div class="card-pro h-100 d-flex flex-column justify-content-between">
                    <div class="card-pro-header pb-2">
                        <div>
                            <span style="font-size: 0.72rem; font-weight:700; color:#64748b; text-transform:uppercase;">Rincian Skor</span>
                            <h3 style="font-size: 1.05rem; font-weight:800; color:#0f172a; margin:0;">Nilai RIASEC Kamu</h3>
                        </div>
                    </div>
                    <div class="card-pro-body pt-2">
                        <div class="d-flex flex-column gap-2 gap-sm-3">
                            @foreach($careerResult->scores_map as $dimCode => $dim)
                                <div class="score-row-item">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 22px; height: 22px; border-radius: 5px; background: {{ $dim['color'] }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.74rem; font-weight: 800;">
                                                {{ $dimCode }}
                                            </span>
                                            <span style="font-size: 0.86rem; font-weight: 700; color: #1e293b;">
                                                {{ $dim['name'] }}
                                            </span>
                                            <span style="font-size: 0.75rem; color: #64748b;">
                                                ({{ $dim['label'] }})
                                            </span>
                                        </div>
                                        <span style="font-size: 0.88rem; font-weight: 800; color: #0f172a;">
                                            {{ $dim['score'] }} / {{ $dim['max_score'] }} <span style="font-size:0.74rem; color:#64748b; font-weight:600;">({{ number_format($dim['percentage'], 1) }}%)</span>
                                        </span>
                                    </div>
                                    <div class="progress score-row-bar" style="height: 6px; border-radius: 3px; background: #f1f5f9;">
                                        <div class="progress-bar" style="width: {{ $dim['percentage'] }}%; background-color: {{ $dim['color'] }}; border-radius: 3px;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-2 px-3 bg-light border-top text-center" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                        <span style="font-size: 0.74rem; color: #64748b;">
                            Persentase Hasil RIASEC: Width (%) = (Skor Mentah / Skor Maksimal) * 100. Batas Maksimal: R (50), I (25), A (40), S (40), E (40), C (45).
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREVIEW REKOMENDASI TERARAH (JURUSAN, PROFESI, USAHA) --}}
        <div class="card-pro print-rec-wrapper p-4 mb-4">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
                <div>
                    <span style="font-size: 0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.04em;">
                        Rekomendasi Berdasarkan {{ $careerResult->holland_code }}
                    </span>
                    <h3 style="font-size: 1.15rem; font-weight:800; color:#0f172a; margin:0;">
                        Pilihan Bidang yang Relevan untukmu
                    </h3>
                </div>
                <a href="{{ route('tes.rekomendasi') }}" class="btn btn-outline-primary btn-sm fw-bold d-print-none">
                    <i class="bi bi-stars me-1"></i> Eksplorasi Semua
                </a>
            </div>

            <div class="row g-3 print-rec-grid">
                {{-- Top Jurusan Linier --}}
                <div class="col-12 col-md-4 print-rec-col">
                    <div class="p-3 rounded-3 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge-pill-soft badge-kuliah-soft py-1 px-2" style="font-size: 0.76rem; font-weight:700;">
                                <i class="bi bi-mortarboard-fill"></i> Jurusan Kuliah
                            </span>
                            @if(!empty($recommendations['student_major_code']))
                                <span class="badge bg-light text-primary border" style="font-size: 0.68rem; font-weight: 700;">
                                    Linier {{ $recommendations['student_major_code'] }}
                                </span>
                            @endif
                        </div>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            @php
                                $jList = !empty($recommendations['jurusan_linier']) ? $recommendations['jurusan_linier'] : $recommendations['jurusan']->take(3);
                            @endphp
                            @forelse($jList as $rec)
                                @php
                                    $recName = is_array($rec) ? $rec['name'] : $rec->name;
                                    $recDesc = is_array($rec) ? $rec['description'] : $rec->description;
                                @endphp
                                <li class="d-flex align-items-start gap-2" style="font-size: 0.83rem; font-weight: 600; color: #1e293b;">
                                    <i class="bi bi-check-circle-fill text-primary mt-1" style="font-size: 0.75rem; flex-shrink:0;"></i>
                                    <div>
                                        <div>{{ $recName }}</div>
                                        <div style="font-size: 0.72rem; font-weight: 400; color: #64748b;">{{ Str::limit($recDesc, 60) }}</div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-muted" style="font-size: 0.80rem;">Belum ada rekomendasi jurusan.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Top Profesi Linier --}}
                <div class="col-12 col-md-4 print-rec-col">
                    <div class="p-3 rounded-3 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge-pill-soft badge-bekerja-soft py-1 px-2" style="font-size: 0.76rem; font-weight:700;">
                                <i class="bi bi-briefcase-fill"></i> Profesi / Karier
                            </span>
                            @if(!empty($recommendations['student_major_code']))
                                <span class="badge bg-light text-warning border text-dark" style="font-size: 0.68rem; font-weight: 700;">
                                    Linier {{ $recommendations['student_major_code'] }}
                                </span>
                            @endif
                        </div>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            @php
                                $pList = !empty($recommendations['profesi_linier']) ? $recommendations['profesi_linier'] : $recommendations['profesi']->take(3);
                            @endphp
                            @forelse($pList as $rec)
                                @php
                                    $recName = is_array($rec) ? $rec['name'] : $rec->name;
                                    $recDesc = is_array($rec) ? $rec['description'] : $rec->description;
                                @endphp
                                <li class="d-flex align-items-start gap-2" style="font-size: 0.83rem; font-weight: 600; color: #1e293b;">
                                    <i class="bi bi-check-circle-fill text-warning mt-1" style="font-size: 0.75rem; flex-shrink:0;"></i>
                                    <div>
                                        <div>{{ $recName }}</div>
                                        <div style="font-size: 0.72rem; font-weight: 400; color: #64748b;">{{ Str::limit($recDesc, 60) }}</div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-muted" style="font-size: 0.80rem;">Belum ada rekomendasi profesi.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Top Usaha Linier --}}
                <div class="col-12 col-md-4 print-rec-col">
                    <div class="p-3 rounded-3 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge-pill-soft badge-wirausaha-soft py-1 px-2" style="font-size: 0.76rem; font-weight:700;">
                                <i class="bi bi-shop"></i> Bidang Usaha
                            </span>
                            @if(!empty($recommendations['student_major_code']))
                                <span class="badge bg-light border" style="font-size: 0.68rem; font-weight: 700; color:#7c3aed;">
                                    Linier {{ $recommendations['student_major_code'] }}
                                </span>
                            @endif
                        </div>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            @php
                                $uList = !empty($recommendations['usaha_linier']) ? $recommendations['usaha_linier'] : $recommendations['usaha']->take(3);
                            @endphp
                            @forelse($uList as $rec)
                                @php
                                    $recName = is_array($rec) ? $rec['name'] : $rec->name;
                                    $recDesc = is_array($rec) ? $rec['description'] : $rec->description;
                                @endphp
                                <li class="d-flex align-items-start gap-2" style="font-size: 0.83rem; font-weight: 600; color: #1e293b;">
                                    <i class="bi bi-check-circle-fill mt-1" style="font-size: 0.75rem; color: #8b5cf6; flex-shrink:0;"></i>
                                    <div>
                                        <div>{{ $recName }}</div>
                                        <div style="font-size: 0.72rem; font-weight: 400; color: #64748b;">{{ Str::limit($recDesc, 60) }}</div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-muted" style="font-size: 0.80rem;">Belum ada rekomendasi usaha.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- TANDA TANGAN VALIDASI (PRINT ONLY) --}}
        <div class="print-signature-block d-none d-print-block">
            <div style="font-size: 8.5pt; text-align: right; margin-bottom: 12px; color: #475569;">
                Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB
            </div>
            <div class="row text-center" style="font-size: 8.5pt;">
                <div class="col-6">
                    <div style="color: #475569; margin-bottom: 50px;">Siswa Bersangkutan,</div>
                    <div class="fw-bold text-dark" style="font-size: 9.5pt; text-decoration: underline;">{{ $student->name }}</div>
                    <div style="color: #64748b;">NISN: {{ $student->nisn ?? '-' }}</div>
                </div>
                <div class="col-6">
                    <div style="color: #475569; margin-bottom: 50px;">Guru Bimbingan Konseling / Wali Kelas,</div>
                    <div class="fw-bold text-dark" style="font-size: 9.5pt;">( ..................................................... )</div>
                    <div style="color: #64748b;">NIP. -</div>
                </div>
            </div>
        </div>

        {{-- CALL TO ACTION BERIKUTNYA (SCREEN ONLY) --}}
        <div class="card-pro p-4 p-md-5 mb-4 text-center d-print-none" style="background: #FFFFFF; border: 1px solid #E4E7EC; border-radius: 12px; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
            <div style="font-size: 0.78rem; font-weight: 600; color: #3157A4; text-transform: uppercase;">
                Langkah Selanjutnya
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 700; color: #1F2937; margin-top: 4px; margin-bottom: 8px;">
                Tentukan Pilihan Nyata Rencanamu Setelah Lulus
            </h3>
            <p style="font-size: 0.88rem; color: #667085; max-width: 600px; margin: 0 auto 20px;">
                Gunakan hasil minat ini untuk memantapkan pilihanmu, lalu tentukan apakah kamu akan berkuliah, bekerja, atau berwirausaha.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('siswa.rencana') }}" class="btn-brand-primary px-4 py-3">
                    <i class="bi bi-compass-fill"></i> Tentukan Rencana Sekarang
                </a>
                <a href="{{ route('tes.rekomendasi') }}" class="btn-brand-outline px-4 py-3">
                    <i class="bi bi-stars"></i> Lihat Rekomendasi Lengkap
                </a>
                <button type="button" class="btn btn-outline-secondary px-3 py-2" onclick="window.print()" style="border-radius: var(--radius-md); font-size: 0.85rem;">
                    <i class="bi bi-printer me-1"></i> Cetak Hasil Tes
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctxRadar = document.getElementById('radarChartHasil');
        if (ctxRadar) {
            const chartInstance = new Chart(ctxRadar.getContext('2d'), {
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
                        backgroundColor: 'rgba(37, 99, 235, 0.22)',
                        borderColor: '#2563eb',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#2563eb',
                        pointRadius: 4.5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    scales: {
                        r: {
                            angleLines: { color: '#e2e8f0' },
                            grid: { color: '#f1f5f9' },
                            pointLabels: {
                                font: { size: 10, weight: '700', family: "'Plus Jakarta Sans', sans-serif" },
                                color: '#334155'
                            },
                            ticks: { beginAtZero: true, max: 100, stepSize: 20, display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });

            // Pastikan chart me-render ulang dengan ukuran pas sebelum window.print
            window.addEventListener('beforeprint', function () {
                chartInstance.resize();
            });
            window.addEventListener('afterprint', function () {
                chartInstance.resize();
            });
        }
    });
</script>
@endpush
