@extends('layouts.app')

@section('title', 'Profil Digital Karier & Studi Siswa')

@push('styles')
<style>
    /* =========================================================
       PRINT STYLES FOR DOKUMEN PROFIL KARIER & STUDI (A4)
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
        .col-lg-9 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            flex: 0 0 100% !important;
        }

        #printableCard {
            border: 1.5px solid #0f172a !important;
            box-shadow: none !important;
            border-radius: 6px !important;
            overflow: visible !important;
            margin: 0 !important;
        }

        .doc-header {
            background: #f0f7ff !important;
            color: #0f172a !important;
            border-bottom: 2px solid #0f172a !important;
            padding: 12px 16px !important;
        }

        .doc-header .doc-kemen {
            color: #1e40af !important;
            font-size: 8pt !important;
            font-weight: 800 !important;
        }

        .doc-header h1 {
            color: #0f172a !important;
            font-size: 1.25rem !important;
            font-weight: 900 !important;
            margin: 2px 0 !important;
        }

        .doc-header .doc-school {
            color: #334155 !important;
            font-size: 8.5pt !important;
        }

        .doc-body {
            padding: 14px 18px !important;
        }

        .print-section {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            margin-bottom: 10px !important;
            padding-bottom: 8px !important;
        }

        .print-signature {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            margin-top: 18px !important;
            padding-top: 10px !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">

        {{-- Action Bar --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
            <div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <span style="font-size: 0.88rem; font-weight: 700; color: #475569;">
                    Dokumen Profil Karier & Studi Siswa
                </span>
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2" onclick="window.print()" style="border-radius: var(--radius-md);">
                    <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        {{-- OFFICIAL DIGITAL PROOF CARD (PRD SECTION 30) --}}
        <div class="card-pro mb-4 p-0 shadow-sm" id="printableCard" style="border: 2px solid #e2e8f0; border-radius: var(--radius-xl); overflow:hidden; background:#ffffff;">
            {{-- Official Kop Surat Resmi (Print Mode) --}}
            <div class="d-none d-print-block p-3">
                @include('partials.kop_surat', [
                    'judulDokumen' => 'PROFIL PERENCANAAN KARIER & STUDI SISWA',
                    'subJudulDokumen' => 'Tahun Ajaran ' . \App\Models\Setting::get('tahun_ajaran', '2026/2027') . ' • Dokumen Resmi Bimbingan Konseling',
                    'align' => 'center'
                ])
            </div>

            {{-- Document Header (Screen Mode) --}}
            <div class="p-4 text-center border-bottom doc-header d-print-none" style="background: #1F355F; color:#ffffff;">
                <div class="doc-kemen" style="font-size: 0.75rem; font-weight: 600; color: #E4E7EC; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 4px;">
                    {{ \App\Models\Setting::get('kop_instansi_atas', 'PEMERINTAH PROVINSI LAMPUNG') }} • {{ \App\Models\Setting::get('kop_instansi_tengah', 'DINAS PENDIDIKAN DAN KEBUDAYAAN') }}
                </div>
                <h1 style="font-size: 1.45rem; font-weight: 700; margin: 0; letter-spacing: -0.01em;">
                    PROFIL PERENCANAAN KARIER & STUDI SISWA
                </h1>
                <div class="doc-school" style="font-size: 0.86rem; color: #E4E7EC; margin-top: 4px;">
                    {{ \App\Models\Setting::get('kop_nama_sekolah', \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 4 Bandar Lampung')) }} • Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
                </div>
            </div>

            <div class="p-4 p-sm-5 doc-body">
                {{-- 1. IDENTITAS SISWA --}}
                <div class="print-section mb-4 pb-4 border-bottom">
                    <div style="font-size: 0.74rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                        I. Identitas Siswa
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div style="font-size: 0.75rem; color: #64748b;">Nama Lengkap Siswa</div>
                            <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">{{ $user->name }}</div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div style="font-size: 0.75rem; color: #64748b;">NISN</div>
                            <div class="font-monospace fw-bold" style="font-size: 0.95rem; color: #0f172a;">{{ $user->nisn ?? '-' }}</div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div style="font-size: 0.75rem; color: #64748b;">Kelas / Rombel</div>
                            <div class="fw-bold" style="font-size: 0.95rem; color: #0f172a;">{{ $user->kelas ?? '-' }}</div>
                        </div>
                        @if($user->tempat_lahir || $user->tanggal_lahir)
                            <div class="col-12 col-sm-6">
                                <div style="font-size: 0.75rem; color: #64748b;">Tempat, Tanggal Lahir</div>
                                <div style="font-size: 0.9rem; color: #334155; font-weight: 600;">
                                    {{ $user->tempat_lahir ? $user->tempat_lahir . ', ' : '' }}{{ $user->tanggal_lahir ?? '-' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 2. HASIL TES MINAT HOLLAND / RIASEC --}}
                <div class="print-section mb-4 pb-4 border-bottom">
                    <div style="font-size: 0.74rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                        II. Profil Minat Karier (Model Holland / RIASEC)
                    </div>

                    @if($careerResult)
                        <div class="row align-items-center g-3 mb-3">
                            <div class="col-12 col-sm-4 text-center text-sm-start">
                                <div class="p-3 rounded-3" style="background: #eff6ff; border: 1.5px solid #bfdbfe;">
                                    <div style="font-size: 0.72rem; font-weight: 700; color: #1d4ed8; text-transform: uppercase;">
                                        Holland Code
                                    </div>
                                    <div style="font-size: 2.2rem; font-weight: 900; font-family: monospace; color: #1e3a8a;">
                                        {{ $careerResult->holland_code }}
                                    </div>
                                    <div style="font-size: 0.78rem; font-weight: 700; color: #2563eb;">
                                        Dominan: {{ $careerResult->dominant_type }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-8">
                                <div style="font-size: 0.88rem; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    Karakteristik Tipe: {{ $careerResult->dominant_type }}
                                </div>
                                <p style="font-size: 0.82rem; color: #475569; line-height: 1.5; margin: 0;">
                                    {{ $careerResult->dominant_description }}
                                </p>
                            </div>
                        </div>

                        {{-- Skor RIASEC Mini Bar --}}
                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="row text-center g-2">
                                @foreach($careerResult->scores_map as $code => $d)
                                    <div class="col-4 col-sm-2">
                                        <div style="font-size: 0.72rem; font-weight: 700; color: #64748b;">
                                            {{ $code }} ({{ $d['name'] }})
                                        </div>
                                        <div style="font-size: 1.1rem; font-weight: 800; color: {{ $d['color'] }};">
                                            {{ $d['score'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-3 rounded-3 bg-light text-muted" style="font-size: 0.85rem;">
                            <i class="bi bi-exclamation-circle me-1"></i> Siswa belum menyelesaikan Tes Minat Karier RIASEC.
                        </div>
                    @endif
                </div>

                {{-- 3. RENCANA SETELAH LULUS --}}
                <div class="print-section mb-4 pb-4 border-bottom">
                    <div style="font-size: 0.74rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                        III. Rencana Pilihan Siswa Setelah Lulus
                    </div>

                    @if($pilihan)
                        <div class="p-3 p-sm-4 rounded-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                @if($pilihan->rencana === 'kuliah')
                                    <span class="badge-pill-soft badge-kuliah-soft py-1 px-3" style="font-size: 0.85rem; font-weight:700;">
                                        <i class="bi bi-mortarboard-fill"></i> Kuliah di Perguruan Tinggi
                                    </span>
                                @elseif($pilihan->rencana === 'bekerja')
                                    <span class="badge-pill-soft badge-bekerja-soft py-1 px-3" style="font-size: 0.85rem; font-weight:700;">
                                        <i class="bi bi-briefcase-fill"></i> Bekerja di Dunia Industri
                                    </span>
                                @else
                                    <span class="badge-pill-soft badge-wirausaha-soft py-1 px-3" style="font-size: 0.85rem; font-weight:700;">
                                        <i class="bi bi-shop"></i> Membangun Wirausaha Mandiri
                                    </span>
                                @endif
                            </div>

                            @if($pilihan->rencana === 'kuliah')
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <div style="font-size: 0.74rem; color: #64748b;">Perguruan Tinggi / Kampus</div>
                                        <div style="font-size: 1.02rem; font-weight: 800; color: #0f172a;">{{ $pilihan->nama_perguruan_tinggi }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div style="font-size: 0.74rem; color: #64748b;">Program Studi / Jurusan</div>
                                        <div style="font-size: 1.02rem; font-weight: 800; color: #0f172a;">{{ $pilihan->nama_program_studi }}</div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div style="font-size: 0.74rem; color: #64748b;">Jenjang Pendidikan</div>
                                        <div class="fw-bold text-dark">{{ $pilihan->jenjang ?? '-' }}</div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div style="font-size: 0.74rem; color: #64748b;">Akreditasi Prodi</div>
                                        <div class="fw-bold text-dark">{{ $pilihan->akreditasi ? $pilihan->akreditasi . ' ⭐' : '-' }}</div>
                                    </div>
                                </div>
                            @elseif($pilihan->rencana === 'bekerja')
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <div style="font-size: 0.74rem; color: #64748b;">Bidang Pekerjaan Industri</div>
                                        <div style="font-size: 1.02rem; font-weight: 800; color: #0f172a;">{{ $pilihan->bidang_pekerjaan ?? '-' }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div style="font-size: 0.74rem; color: #64748b;">Keterangan / Profesi Diminati</div>
                                        <div style="font-size: 0.95rem; font-weight: 600; color: #334155;">{{ $pilihan->keterangan_pekerjaan ?: '-' }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <div style="font-size: 0.74rem; color: #64748b;">Bidang Usaha Bisnis</div>
                                        <div style="font-size: 1.02rem; font-weight: 800; color: #0f172a;">{{ $pilihan->bidang_usaha ?? '-' }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div style="font-size: 0.74rem; color: #64748b;">Jenis Usaha yang Diminati</div>
                                        <div style="font-size: 0.95rem; font-weight: 600; color: #334155;">{{ $pilihan->keterangan_usaha ?: '-' }}</div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3 pt-2 border-top" style="font-size: 0.75rem; color: #64748b;">
                                <i class="bi bi-clock-history me-1"></i> Data disubmit secara mandiri oleh siswa pada: {{ $pilihan->submitted_at ? $pilihan->submitted_at->translatedFormat('l, d F Y - H:i') : '-' }} WIB
                            </div>
                        </div>
                    @else
                        <div class="p-3 rounded-3 bg-light text-muted" style="font-size: 0.85rem;">
                            <i class="bi bi-exclamation-circle me-1"></i> Siswa belum menentukan rencana kelulusan.
                        </div>
                    @endif
                </div>

                {{-- 4. REKOMENDASI TERKAIT (LINIER JURUSAN SISWA & HOLLAND) --}}
                @if(!empty($recommendations))
                    @php
                        $listJurusan = !empty($recommendations['jurusan_linier']) ? $recommendations['jurusan_linier'] : ($recommendations['jurusan'] ?? collect());
                        $listProfesi = !empty($recommendations['profesi_linier']) ? $recommendations['profesi_linier'] : ($recommendations['profesi'] ?? collect());
                        $listUsaha   = !empty($recommendations['usaha_linier'])   ? $recommendations['usaha_linier']   : ($recommendations['usaha'] ?? collect());
                        $majorCode   = $recommendations['student_major_code'] ?? null;
                    @endphp
                    <div class="print-section mb-4 pb-4 border-bottom">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div style="font-size: 0.74rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
                                IV. Rekomendasi Terarah ({{ $majorCode ? 'Linier ' . $majorCode . ' • ' : '' }}Holland: {{ $careerResult->holland_code ?? '-' }})
                            </div>
                            @if($majorCode)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.68rem; font-weight: 700;">
                                    Kurasi Linier {{ $majorCode }}
                                </span>
                            @endif
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div style="font-size: 0.78rem; font-weight: 700; color: #2563eb; margin-bottom: 4px;">
                                    <i class="bi bi-mortarboard-fill me-1"></i> Jurusan / Prodi:
                                </div>
                                <ul class="mb-0 ps-3" style="font-size: 0.82rem; color: #334155; line-height: 1.55;">
                                    @foreach(collect($listJurusan)->take(3) as $r)
                                        <li>{{ is_array($r) ? $r['name'] : $r->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12 col-md-4">
                                <div style="font-size: 0.78rem; font-weight: 700; color: #d97706; margin-bottom: 4px;">
                                    <i class="bi bi-briefcase-fill me-1"></i> Profesi / Karier:
                                </div>
                                <ul class="mb-0 ps-3" style="font-size: 0.82rem; color: #334155; line-height: 1.55;">
                                    @foreach(collect($listProfesi)->take(3) as $r)
                                        <li>{{ is_array($r) ? $r['name'] : $r->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12 col-md-4">
                                <div style="font-size: 0.78rem; font-weight: 700; color: #7c3aed; margin-bottom: 4px;">
                                    <i class="bi bi-shop me-1"></i> Bidang Usaha:
                                </div>
                                <ul class="mb-0 ps-3" style="font-size: 0.82rem; color: #334155; line-height: 1.55;">
                                    @foreach(collect($listUsaha)->take(3) as $r)
                                        <li>{{ is_array($r) ? $r['name'] : $r->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TANDA TANGAN VALIDASI (PRINT ONLY) --}}
                <div class="print-signature d-none d-print-block">
                    <div style="font-size: 8.5pt; text-align: right; margin-bottom: 12px; color: #475569;">
                        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB
                    </div>
                    <div class="row text-center" style="font-size: 8.5pt;">
                        <div class="col-6">
                            <div style="color: #475569; margin-bottom: 50px;">Siswa Bersangkutan,</div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem; text-decoration: underline;">{{ $user->name }}</div>
                            <div style="color: #64748b;">NISN: {{ $user->nisn ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div style="color: #475569; margin-bottom: 50px;">Guru Bimbingan Konseling / Wali Kelas,</div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">( ............................................ )</div>
                            <div style="color: #64748b;">NIP. -</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
