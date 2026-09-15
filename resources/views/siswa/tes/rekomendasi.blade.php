@extends('layouts.app')

@section('title', 'Rekomendasi Studi & Karier Terpadu')

@push('styles')
<style>
    /* Styling khusus Rekomendasi Terpadu */
    .recom-hero {
        background: #1F355F;
        color: #ffffff;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        border: 1px solid #1F355F;
    }
    .recom-hero-decor {
        display: none;
    }
    .analysis-card {
        background: #ffffff;
        border: 1px solid #E4E7EC;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
    }
    .nav-pill-custom .nav-link {
        border-radius: var(--radius-md);
        font-size: 0.88rem;
        font-weight: 700;
        padding: 10px 16px;
        color: #475569;
        transition: all 0.2s ease;
    }
    .nav-pill-custom .nav-link.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .card-recom-item {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-lg);
        transition: all 0.22s ease-in-out;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-recom-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }
    .card-recom-item.linier-card {
        border-left: 4.5px solid #2563eb;
    }
    .card-recom-item.linier-card:hover {
        border-color: #3b82f6;
    }
    .card-recom-item.cross-card {
        border-left: 4.5px solid #8b5cf6;
        background: #fafafa;
    }
    .card-recom-item.cross-card:hover {
        border-color: #a855f7;
        background: #ffffff;
    }
    .cross-divider {
        position: relative;
        text-align: center;
        margin: 35px 0 25px;
    }
    .cross-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1.5px;
        background: #e2e8f0;
        z-index: 1;
    }
    .cross-divider-badge {
        position: relative;
        z-index: 2;
        background: #f1f5f9;
        color: #475569;
        padding: 6px 18px;
        border-radius: var(--radius-full);
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 0.03em;
        border: 1px solid #cbd5e1;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">

        {{-- ACTION BAR (TOMBOL KEMBALI) --}}
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm" title="Kembali ke Dashboard">
                    <i class="bi bi-arrow-left"></i> Dashboard
                </a>
                <a href="{{ route('tes.hasil') }}" class="btn btn-outline-primary btn-sm fw-bold">
                    <i class="bi bi-bar-chart-fill me-1"></i> Grafik Radar
                </a>
            </div>
            <div>
                <a href="{{ route('siswa.rencana') }}" class="btn-brand-primary btn-sm px-3 py-2">
                    <i class="bi bi-compass-fill me-1"></i> Tentukan Rencana Sekarang
                </a>
            </div>
        </div>

        {{-- [BAGIAN ATAS: RINGKASAN & NARASI PSIKOLOGIS] --}}
        <div class="recom-hero p-4 p-md-5 mb-4 shadow-sm">
            <div class="recom-hero-decor"></div>
            
            <div class="position-relative">
                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                    <span class="badge" style="background: rgba(255,255,255,0.2); backdrop-filter:blur(4px); font-size:0.8rem; font-weight:800; padding:6px 14px; border-radius:var(--radius-full); letter-spacing:0.06em; font-family: monospace;">
                        Holland Code: {{ $careerResult->holland_code }}
                    </span>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1" style="font-size:0.75rem; border-radius:var(--radius-full);">
                        <i class="bi bi-mortarboard-fill me-1"></i> Jurusan Asal: {{ $recommendations['student_short_name'] }}
                    </span>
                </div>

                <h1 style="font-size: 1.55rem; font-weight: 900; margin-bottom: 6px; letter-spacing: -0.01em;">
                    Rekomendasi Studi, Profesi, & Usaha untuk: {{ $user->name }}
                </h1>
                <p style="font-size: 0.88rem; color: #cbd5e1; margin-bottom: 20px;">
                    Disesuaikan secara presisi menggabungkan kejuruan asalmu di <strong>{{ $recommendations['student_major'] }} ({{ $recommendations['student_major_code'] }})</strong> dengan profil tes minat <strong>{{ $careerResult->dominant_type }}</strong> ({{ $careerResult->holland_code }}).
                </p>

                {{-- KARTU ANALISIS KARAKTER PSIKOLOGIS --}}
                <div class="analysis-card p-3 p-md-4 text-dark mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:32px; height:32px; border-radius:8px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1.1rem;">
                            🔮
                        </span>
                        <div>
                            <span style="font-size:0.74rem; font-weight:800; text-transform:uppercase; color:#2563eb; letter-spacing:0.04em;">
                                Analisis Karakter Unik Siswa
                            </span>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a;">
                                {{ $recommendations['psychological_analysis']['persona_title'] }}
                            </div>
                        </div>
                    </div>

                    <p style="font-size: 0.88rem; color: #334155; line-height: 1.6; margin: 0; font-style: italic;">
                        {{ $recommendations['psychological_analysis']['narrative'] }}
                    </p>
                </div>

                {{-- KARTU REKOMENDASI KOLABORASI (RIASEC + CAREER ANCHORS) --}}
                @if(!empty($collaborationReport))
                <div class="p-3 p-md-4 rounded-3 text-dark mb-0" style="background: rgba(255, 255, 255, 0.98); border: 2px solid #818cf8; box-shadow: 0 4px 20px rgba(99, 102, 241, 0.12);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-5">⚓</span>
                            <span style="font-size:0.75rem; font-weight:800; text-transform:uppercase; color:#4f46e5; letter-spacing:0.04em;">
                                Matriks Integrasi Keputusan (RIASEC + Career Anchors)
                            </span>
                        </div>
                        <span class="badge {{ $careerResult->execution_path_badge['class'] }} px-3 py-2 fs-6">
                            <i class="bi {{ $careerResult->execution_path_badge['icon'] }} me-1"></i>
                            Jalur Eksekusi: {{ $collaborationReport['anchor_recommendation_title'] ?? $careerResult->execution_path_badge['label'] }}
                        </span>
                    </div>

                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.94rem; font-weight: 700; color: #1e1b4b; margin-bottom: 6px;">
                            {{ $collaborationReport['greeting'] ?? '' }}
                        </div>
                        <p style="font-size: 0.86rem; color: #334155; line-height: 1.6; margin: 0;">
                            {{ $collaborationReport['reason_narrative'] ?? '' }}
                        </p>
                    </div>

                    @if(!empty($collaborationReport['ideas']))
                        <div class="mt-3">
                            <div style="font-size: 0.8rem; font-weight: 800; color: #4338ca; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
                                💡 Ide Eksekusi Terkurasi Terbaik:
                            </div>
                            <div class="row g-2">
                                @foreach($collaborationReport['ideas'] as $idea)
                                    <div class="col-12 col-md-6">
                                        <div class="p-2 px-3 rounded-3 h-100" style="background: #eef2ff; border: 1px solid #c7d2fe;">
                                            <div class="fw-bold text-indigo mb-1" style="font-size: 0.88rem; color: #3730a3;">
                                                <i class="bi bi-lightbulb-fill text-warning me-1"></i> {{ $idea['title'] }}
                                            </div>
                                            <div style="font-size: 0.78rem; color: #4338ca; line-height: 1.4;">
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
            </div>
        </div>

        {{-- [BAGIAN TENGAH: FILTER & TAB SELEKSI] --}}
        <div class="card-pro p-2 mb-4">
            <ul class="nav nav-pills nav-fill nav-pill-custom gap-2" id="curatedRecomTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-btn-jurusan" data-bs-toggle="tab" data-bs-target="#panel-jurusan" type="button" role="tab">
                        <i class="bi bi-mortarboard-fill me-1"></i> 🎓 Jurusan Kuliah (Top 3 & Terkurasi)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-btn-profesi" data-bs-toggle="tab" data-bs-target="#panel-profesi" type="button" role="tab">
                        <i class="bi bi-briefcase-fill me-1"></i> 💼 Profesi & Karier (Top 3 & Terkurasi)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-btn-usaha" data-bs-toggle="tab" data-bs-target="#panel-usaha" type="button" role="tab">
                        <i class="bi bi-shop me-1"></i> 🚀 Bidang Usaha (Top 3 & Terkurasi)
                    </button>
                </li>
            </ul>
        </div>

        {{-- [BAGIAN BAWAH: LAYOUT CARD REKOMENDASI (STRUKTUR TEPAT & KAYA PILIHAN)] --}}
        <div class="tab-content" id="curatedRecomContent">

            {{-- =========================================================================
                 TAB 1: JURUSAN / PRODI KAMPUS
                 ========================================================================= --}}
            <div class="tab-pane fade show active" id="panel-jurusan" role="tabpanel">
                
                {{-- BARIS 1: 🌟 TOP 3 REKOMENDASI UTAMA & LINIER (SANGAT DISARANKAN) --}}
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div style="font-size:0.75rem; font-weight:800; color:#2563eb; text-transform:uppercase; letter-spacing:0.04em;">
                            Jalur Pendidikan Tinggi
                        </div>
                        <h2 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                            🌟 Top 3 Rekomendasi Utama (Pilihan Paling Tepat)
                        </h2>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="font-size:0.76rem;">
                        Linier {{ $recommendations['student_major_code'] }} • Minat {{ $careerResult->holland_code }}
                    </span>
                </div>

                @php
                    $jurusanTop3 = collect($recommendations['jurusan_linier'])->take(3);
                    $jurusanAlternatif = collect($recommendations['jurusan_linier'])->slice(3);
                @endphp

                <div class="row g-3 mb-4">
                    @foreach($jurusanTop3 as $item)
                        <div class="col-12 col-md-4">
                            <div class="card-recom-item linier-card p-4 h-100" style="border-left-width: 5px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-primary text-white fw-bold font-monospace px-2 py-1" style="font-size: 0.72rem;">
                                            {{ $item['badge_label'] }}
                                        </span>
                                        <span class="badge bg-light text-primary border" style="font-size: 0.70rem;">
                                            Tipe: {{ $item['riasec_code'] }}
                                        </span>
                                    </div>
                                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.35;">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p style="font-size: 0.82rem; color: #475569; line-height: 1.5; margin: 0;">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between">
                                    <span style="font-size: 0.73rem; color: #2563eb; font-weight: 700;">
                                        <i class="bi bi-patch-check-fill me-1"></i> Rekomendasi Utama
                                    </span>
                                    <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-primary fw-bold px-3 py-1" style="font-size: 0.76rem; border-radius: var(--radius-sm);">
                                        Pilih Opsi Ini →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- BARIS 2: ✨ ALTERNATIF PILIHAN LINIER LAINNYA --}}
                @if($jurusanAlternatif->isNotEmpty())
                    <div class="d-flex align-items-center justify-content-between mb-2 mt-4 flex-wrap gap-2">
                        <div>
                            <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.03em;">
                                ✨ Opsi Alternatif Linier Kejuruan (Eksplorasi Cabang Lain)
                            </span>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach($jurusanAlternatif as $item)
                            <div class="col-12 col-md-4">
                                <div class="card-recom-item p-3 h-100" style="border-left: 3.5px solid #94a3b8; background: #fafafa;">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-secondary-subtle text-secondary fw-bold px-2 py-1" style="font-size: 0.70rem;">
                                                {{ $item['badge_label'] }}
                                            </span>
                                            <span class="badge bg-light text-muted border" style="font-size: 0.68rem;">
                                                {{ $item['riasec_code'] }}
                                            </span>
                                        </div>
                                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; line-height: 1.35;">
                                            {{ $item['name'] }}
                                        </h4>
                                        <p style="font-size: 0.78rem; color: #64748b; line-height: 1.45; margin: 0;">
                                            {{ $item['description'] }}
                                        </p>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.70rem; color: #64748b; font-weight: 600;">
                                            Linier {{ $recommendations['student_major_code'] }}
                                        </span>
                                        <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-secondary fw-bold px-2 py-1" style="font-size: 0.73rem;">
                                            Pilih Ini →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- BARIS 3: 🔄 EKSPLORASI LINTAS JURUSAN (PELUANG BARU) --}}
                <div class="cross-divider">
                    <span class="cross-divider-badge">
                        <i class="bi bi-shuffle text-purple" style="color:#8b5cf6;"></i> Ingin Lintas Jurusan? Cek Opsi Ini
                    </span>
                </div>
                <div class="text-center mb-3">
                    <p style="font-size: 0.82rem; color: #64748b; max-width: 650px; margin: 0 auto;">
                        Peluang prodi non-{{ $recommendations['student_major_code'] }} yang terbuka lebar karena hasil tes mendeteksi skor kepribadianmu yang tinggi di ranah eksplorasi lain.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    @foreach($recommendations['jurusan_lintas'] as $item)
                        <div class="col-12 col-md-3">
                            <div class="card-recom-item cross-card p-3 h-100">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge fw-bold font-monospace px-2 py-1" style="font-size: 0.70rem; background:#ede9fe; color:#7c3aed;">
                                            Tipe: {{ $item['riasec_code'] }}
                                        </span>
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.68rem;">
                                            {{ $item['source_major'] ?? 'Lintas Bidang' }}
                                        </span>
                                    </div>
                                    <h4 style="font-size: 0.94rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.35;">
                                        {{ $item['name'] }}
                                    </h4>
                                    <p style="font-size: 0.78rem; color: #475569; line-height: 1.45; margin: 0;">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                    <span style="font-size: 0.70rem; color: #7c3aed; font-weight: 700;">
                                        <i class="bi bi-stars me-1"></i> Peluang Baru
                                    </span>
                                    <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-purple fw-bold px-2 py-1" style="font-size: 0.72rem; border-radius: var(--radius-sm); color:#7c3aed; border-color:#c4b5fd;">
                                        Pilih Ini →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- =========================================================================
                 TAB 2: PROFESI & KARIER INDUSTRI
                 ========================================================================= --}}
            <div class="tab-pane fade" id="panel-profesi" role="tabpanel">
                
                {{-- BARIS 1: 🌟 TOP 3 REKOMENDASI UTAMA (KARIER) --}}
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div style="font-size:0.75rem; font-weight:800; color:#d97706; text-transform:uppercase; letter-spacing:0.04em;">
                            Jalur Dunia Kerja & Industri
                        </div>
                        <h2 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                            🌟 Top 3 Rekomendasi Utama (Profesi & Karier Paling Tepat)
                        </h2>
                    </div>
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-2" style="font-size:0.76rem;">
                        Pekerjaan yang Membutuhkan Fondasi {{ $recommendations['student_major_code'] }}
                    </span>
                </div>

                @php
                    $profesiTop3 = collect($recommendations['profesi_linier'])->take(3);
                    $profesiAlternatif = collect($recommendations['profesi_linier'])->slice(3);
                @endphp

                <div class="row g-3 mb-4">
                    @foreach($profesiTop3 as $item)
                        <div class="col-12 col-md-4">
                            <div class="card-recom-item linier-card p-4 h-100" style="border-left-color: #f59e0b; border-left-width: 5px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.08);">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-warning text-dark fw-bold font-monospace px-2 py-1" style="font-size: 0.72rem;">
                                            {{ $item['badge_label'] }}
                                        </span>
                                        <span class="badge bg-light text-warning border text-dark" style="font-size: 0.70rem;">
                                            Tipe: {{ $item['riasec_code'] }}
                                        </span>
                                    </div>
                                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.35;">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p style="font-size: 0.82rem; color: #475569; line-height: 1.5; margin: 0;">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between">
                                    <span style="font-size: 0.73rem; color: #d97706; font-weight: 700;">
                                        <i class="bi bi-briefcase-fill me-1"></i> Rekomendasi Utama
                                    </span>
                                    <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold px-3 py-1" style="font-size: 0.76rem; border-radius: var(--radius-sm);">
                                        Pilih Opsi Ini →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- BARIS 2: ✨ ALTERNATIF PILIHAN PROFESI LAINNYA --}}
                @if($profesiAlternatif->isNotEmpty())
                    <div class="d-flex align-items-center justify-content-between mb-2 mt-4 flex-wrap gap-2">
                        <div>
                            <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.03em;">
                                ✨ Opsi Alternatif Profesi Linier (Karier Terkait {{ $recommendations['student_major_code'] }})
                            </span>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach($profesiAlternatif as $item)
                            <div class="col-12 col-md-4">
                                <div class="card-recom-item p-3 h-100" style="border-left: 3.5px solid #d97706; background: #fffdf5;">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1" style="font-size: 0.70rem;">
                                                {{ $item['badge_label'] }}
                                            </span>
                                            <span class="badge bg-light text-muted border" style="font-size: 0.68rem;">
                                                {{ $item['riasec_code'] }}
                                            </span>
                                        </div>
                                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; line-height: 1.35;">
                                            {{ $item['name'] }}
                                        </h4>
                                        <p style="font-size: 0.78rem; color: #64748b; line-height: 1.45; margin: 0;">
                                            {{ $item['description'] }}
                                        </p>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.70rem; color: #b45309; font-weight: 600;">
                                            Linier {{ $recommendations['student_major_code'] }}
                                        </span>
                                        <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold px-2 py-1" style="font-size: 0.73rem;">
                                            Pilih Ini →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- BARIS 3: 🔄 EKSPLORASI KARIER LINTAS BIDANG --}}
                <div class="cross-divider">
                    <span class="cross-divider-badge">
                        <i class="bi bi-shuffle text-purple" style="color:#8b5cf6;"></i> Ingin Lintas Bidang Karier? Cek Opsi Ini
                    </span>
                </div>
                <div class="text-center mb-3">
                    <p style="font-size: 0.82rem; color: #64748b; max-width: 650px; margin: 0 auto;">
                        Profesi alternatif yang sangat relevan dengan keunggulan kepribadianmu dan diminati pasar kerja modern.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    @foreach($recommendations['profesi_lintas'] as $item)
                        <div class="col-12 col-md-3">
                            <div class="card-recom-item cross-card p-3 h-100">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge fw-bold font-monospace px-2 py-1" style="font-size: 0.70rem; background:#ede9fe; color:#7c3aed;">
                                            Tipe: {{ $item['riasec_code'] }}
                                        </span>
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.68rem;">
                                            {{ $item['source_major'] ?? 'Lintas Bidang' }}
                                        </span>
                                    </div>
                                    <h4 style="font-size: 0.94rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.35;">
                                        {{ $item['name'] }}
                                    </h4>
                                    <p style="font-size: 0.78rem; color: #475569; line-height: 1.45; margin: 0;">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                    <span style="font-size: 0.70rem; color: #7c3aed; font-weight: 700;">
                                        <i class="bi bi-stars me-1"></i> Peluang Baru
                                    </span>
                                    <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-purple fw-bold px-2 py-1" style="font-size: 0.72rem; border-radius: var(--radius-sm); color:#7c3aed; border-color:#c4b5fd;">
                                        Pilih Ini →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- =========================================================================
                 TAB 3: BIDANG WIRAUSAHA
                 ========================================================================= --}}
            <div class="tab-pane fade" id="panel-usaha" role="tabpanel">
                
                {{-- BARIS 1: 🌟 TOP 3 REKOMENDASI UTAMA (WIRAUSAHA) --}}
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div style="font-size:0.75rem; font-weight:800; color:#8b5cf6; text-transform:uppercase; letter-spacing:0.04em;">
                            Jalur Wirausaha Mandiri
                        </div>
                        <h2 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                            🌟 Top 3 Rekomendasi Utama (Ide Bisnis Mandiri Paling Tepat)
                        </h2>
                    </div>
                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-3 py-2" style="font-size:0.76rem; background:#f5f3ff; color:#6d28d9;">
                        Peluang Usaha Berbasis Kompetensi {{ $recommendations['student_major_code'] }}
                    </span>
                </div>

                @php
                    $usahaTop3 = collect($recommendations['usaha_linier'])->take(3);
                    $usahaAlternatif = collect($recommendations['usaha_linier'])->slice(3);
                @endphp

                <div class="row g-3 mb-4">
                    @foreach($usahaTop3 as $item)
                        <div class="col-12 col-md-4">
                            <div class="card-recom-item linier-card p-4 h-100" style="border-left-color: #8b5cf6; border-left-width: 5px; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.08);">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge fw-bold font-monospace px-2 py-1 text-white" style="font-size: 0.72rem; background:#7c3aed;">
                                            {{ $item['badge_label'] }}
                                        </span>
                                        <span class="badge bg-light border text-purple" style="font-size: 0.70rem; color:#6d28d9;">
                                            Tipe: {{ $item['riasec_code'] }}
                                        </span>
                                    </div>
                                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.35;">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p style="font-size: 0.82rem; color: #475569; line-height: 1.5; margin: 0;">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between">
                                    <span style="font-size: 0.73rem; color: #7c3aed; font-weight: 700;">
                                        <i class="bi bi-shop me-1"></i> Rekomendasi Utama
                                    </span>
                                    <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-purple fw-bold px-3 py-1" style="font-size: 0.76rem; border-radius: var(--radius-sm); color:#7c3aed; border-color:#c4b5fd;">
                                        Pilih Opsi Ini →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- BARIS 2: ✨ ALTERNATIF PILIHAN WIRAUSAHA LAINNYA --}}
                @if($usahaAlternatif->isNotEmpty())
                    <div class="d-flex align-items-center justify-content-between mb-2 mt-4 flex-wrap gap-2">
                        <div>
                            <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.03em;">
                                ✨ Opsi Alternatif Wirausaha Linier (Bisnis Terkait {{ $recommendations['student_major_code'] }})
                            </span>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach($usahaAlternatif as $item)
                            <div class="col-12 col-md-4">
                                <div class="card-recom-item p-3 h-100" style="border-left: 3.5px solid #a855f7; background: #faf5ff;">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-purple-subtle text-purple fw-bold px-2 py-1" style="font-size: 0.70rem; color:#7c3aed;">
                                                {{ $item['badge_label'] }}
                                            </span>
                                            <span class="badge bg-light text-muted border" style="font-size: 0.68rem;">
                                                {{ $item['riasec_code'] }}
                                            </span>
                                        </div>
                                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; line-height: 1.35;">
                                            {{ $item['name'] }}
                                        </h4>
                                        <p style="font-size: 0.78rem; color: #64748b; line-height: 1.45; margin: 0;">
                                            {{ $item['description'] }}
                                        </p>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.70rem; color: #7c3aed; font-weight: 600;">
                                            Linier {{ $recommendations['student_major_code'] }}
                                        </span>
                                        <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-purple fw-bold px-2 py-1" style="font-size: 0.73rem; color:#7c3aed; border-color:#c4b5fd;">
                                            Pilih Ini →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- BARIS 3: 🔄 EKSPLORASI WIRAUSAHA LINTAS BIDANG --}}
                <div class="cross-divider">
                    <span class="cross-divider-badge">
                        <i class="bi bi-shuffle text-purple" style="color:#8b5cf6;"></i> Ingin Merintis Usaha Lintas Bidang? Cek Opsi Ini
                    </span>
                </div>
                <div class="text-center mb-3">
                    <p style="font-size: 0.82rem; color: #64748b; max-width: 650px; margin: 0 auto;">
                        Ide bisnis kreatif dan teknologi yang memanfaatkan kombinasi keterampilan unik kepribadianmu.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    @foreach($recommendations['usaha_lintas'] as $item)
                        <div class="col-12 col-md-3">
                            <div class="card-recom-item cross-card p-3 h-100">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge fw-bold font-monospace px-2 py-1" style="font-size: 0.70rem; background:#ede9fe; color:#7c3aed;">
                                            Tipe: {{ $item['riasec_code'] }}
                                        </span>
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.68rem;">
                                            {{ $item['source_major'] ?? 'Lintas Bidang' }}
                                        </span>
                                    </div>
                                    <h4 style="font-size: 0.94rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.35;">
                                        {{ $item['name'] }}
                                    </h4>
                                    <p style="font-size: 0.78rem; color: #475569; line-height: 1.45; margin: 0;">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                    <span style="font-size: 0.70rem; color: #7c3aed; font-weight: 700;">
                                        <i class="bi bi-stars me-1"></i> Peluang Baru
                                    </span>
                                    <a href="{{ route('siswa.rencana', ['rencana' => $item['action_plan'], 'item' => $item['name']]) }}" class="btn btn-sm btn-outline-purple fw-bold px-2 py-1" style="font-size: 0.72rem; border-radius: var(--radius-sm); color:#7c3aed; border-color:#c4b5fd;">
                                        Pilih Ini →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        {{-- CALL TO ACTION BAWAH --}}
        <div class="card-pro p-4 p-md-5 mb-4 text-center mt-5" style="background: #FFFFFF; border: 1px solid #E4E7EC; border-radius: 12px; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
            <div style="font-size: 0.78rem; font-weight: 600; color: #3157A4; text-transform: uppercase;">
                Langkah Eksekusi Karier
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 700; color: #1F2937; margin-top: 4px; margin-bottom: 8px;">
                Sudah Menemukan Arah yang Paling Pas?
            </h3>
            <p style="font-size: 0.88rem; color: #667085; max-width: 600px; margin: 0 auto 20px;">
                Kunci rencanamu sekarang juga agar terekam pada data bimbingan konseling sekolah dan laporan peminatan studi kelas 12.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('siswa.rencana') }}" class="btn-brand-primary px-4 py-3">
                    <i class="bi bi-compass-fill"></i> Tentukan Rencana Sekarang
                </a>
                <a href="{{ route('tes.hasil') }}" class="btn-brand-outline px-4 py-3">
                    <i class="bi bi-printer me-1"></i> Cetak Lembar Hasil Tes
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
