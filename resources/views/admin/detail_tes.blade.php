@extends('layouts.admin')

@section('title', 'Detail & Lembar Jawaban Siswa — ' . ($user->name ?? 'Hasil Tes'))

@section('content')
{{-- Action Bar / Top Navigation --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.hasil-tes') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Hasil
        </a>
        <span class="text-muted">/</span>
        <span class="fw-bold text-dark" style="font-size: 0.9rem;">Pratinjau Hasil & Jawaban Siswa</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" onclick="window.print()" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;">
            <i class="bi bi-printer"></i> Cetak Lembar Hasil
        </button>
        <form action="{{ route('admin.hasil-tes.reset', $user->id) }}"
              method="POST"
              onsubmit="return confirm('Apakah Anda yakin ingin mereset hasil tes minat siswa {{ $user->name }}? Siswa dapat mengikuti tes ulang.');"
              class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Tes Siswa
            </button>
        </form>
    </div>
</div>

{{-- Student Profile & Summary Card --}}
<div class="panel-card mb-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #1F355F 100%); color: #ffffff; border-radius: 14px; padding: 24px;">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; color: #fff;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="badge" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); font-size: 0.78rem;">
                        Kelas {{ $user->kelas ?? '-' }}
                    </span>
                    <span class="badge font-monospace" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); font-size: 0.78rem;">
                        NISN: {{ $user->nisn ?? '-' }}
                    </span>
                    @if($user->jk)
                        <span class="badge" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); font-size: 0.78rem;">
                            {{ $user->jenis_kelamin_text }}
                        </span>
                    @endif
                </div>
                <h1 style="font-size: 1.45rem; font-weight: 800; margin: 0; color: #ffffff; line-height: 1.2;">
                    {{ $user->name }}
                </h1>
                <div style="font-size: 0.82rem; color: #cbd5e1; margin-top: 4px;">
                    <i class="bi bi-clock me-1"></i> Waktu Selesai Tes: {{ $careerResult->completed_at ? $careerResult->completed_at->translatedFormat('l, d F Y - H:i') : '-' }} WIB
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="p-3 rounded-3 text-center" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); min-width: 130px;">
                <div style="font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Holland Code</div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #38bdf8; font-family: monospace;">
                    {{ $careerResult->holland_code }}
                </div>
                <div style="font-size: 0.75rem; color: #ffffff; font-weight: 600;">
                    {{ $careerResult->dominant_type }}
                </div>
            </div>

            <div class="p-3 rounded-3 text-center" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); min-width: 140px;">
                <div style="font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Jalur Eksekusi</div>
                <div style="font-size: 1.05rem; font-weight: 800; color: #facc15; margin-top: 3px;">
                    {{ $careerResult->execution_path_badge['label'] ?? '-' }}
                </div>
                <div style="font-size: 0.72rem; color: #cbd5e1; margin-top: 2px;">
                    {{ $careerResult->dominant_anchor ?? 'Jangkar Karier' }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 6 RIASEC Dimension Score Cards --}}
<div class="row g-3 mb-4">
    @php
        $dimensiData = [
            ['code' => 'R', 'name' => 'Realistic (Praktis & Teknik)', 'score' => $careerResult->realistic_score, 'max' => 50, 'color' => '#2563eb', 'bg' => '#eff6ff'],
            ['code' => 'I', 'name' => 'Investigative (Analitis & Riset)', 'score' => $careerResult->investigative_score, 'max' => 25, 'color' => '#0284c7', 'bg' => '#f0f9ff'],
            ['code' => 'A', 'name' => 'Artistic (Kreatif & Desain)', 'score' => $careerResult->artistic_score, 'max' => 40, 'color' => '#db2777', 'bg' => '#fdf2f8'],
            ['code' => 'S', 'name' => 'Social (Sosial & Edukasi)', 'score' => $careerResult->social_score, 'max' => 40, 'color' => '#16a34a', 'bg' => '#f0fdf4'],
            ['code' => 'E', 'name' => 'Enterprising (Bisnis & Manajerial)', 'score' => $careerResult->enterprising_score, 'max' => 40, 'color' => '#d97706', 'bg' => '#fffbeb'],
            ['code' => 'C', 'name' => 'Conventional (Keteraturan & Data)', 'score' => $careerResult->conventional_score, 'max' => 45, 'color' => '#7c3aed', 'bg' => '#f5f3ff'],
        ];
    @endphp

    @foreach($dimensiData as $dim)
        @php
            $pct = $dim['max'] > 0 ? round(($dim['score'] / $dim['max']) * 100) : 0;
            $isDominant = str_contains($careerResult->holland_code, $dim['code']);
        @endphp
        <div class="col-6 col-md-4 col-xl-2">
            <div class="panel-card p-3 h-100 position-relative" style="background: #ffffff; border: 1.5px solid {{ $isDominant ? $dim['color'] : '#e2e8f0' }}; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                @if($isDominant)
                    <span class="badge position-absolute top-0 end-0 m-2" style="background: {{ $dim['color'] }}; font-size: 0.65rem;">Top 3</span>
                @endif
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge font-monospace" style="background: {{ $dim['color'] }}; color: #fff; font-size: 0.85rem; font-weight: 800;">
                        {{ $dim['code'] }}
                    </span>
                    <span style="font-size: 0.76rem; font-weight: 700; color: #1e293b;" class="text-truncate">
                        {{ $dim['name'] }}
                    </span>
                </div>
                <div class="d-flex align-items-baseline gap-1 mt-2 mb-1">
                    <span style="font-size: 1.35rem; font-weight: 900; color: {{ $dim['color'] }};">
                        {{ $dim['score'] }}
                    </span>
                    <span style="font-size: 0.75rem; color: #64748b;">/ {{ $dim['max'] }}</span>
                    <span class="ms-auto" style="font-size: 0.78rem; font-weight: 700; color: #475569;">{{ $pct }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px; background: #e2e8f0;">
                    <div class="progress-bar" style="width: {{ $pct }}%; background-color: {{ $dim['color'] }};"></div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Rekomendasi Linier Kejuruan Siswa --}}
@if(!empty($recommendations))
    @php
        $listJurusan = !empty($recommendations['jurusan_linier']) ? $recommendations['jurusan_linier'] : ($recommendations['jurusan'] ?? collect());
        $listProfesi = !empty($recommendations['profesi_linier']) ? $recommendations['profesi_linier'] : ($recommendations['profesi'] ?? collect());
        $listUsaha   = !empty($recommendations['usaha_linier'])   ? $recommendations['usaha_linier']   : ($recommendations['usaha'] ?? collect());
        $majorCode   = $recommendations['student_major_code'] ?? null;
    @endphp
    <div class="panel-card mb-4 p-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                    <i class="bi bi-stars text-primary me-2"></i>Rekomendasi Terarah Berdasarkan Holland & Jurusan Siswa
                </h3>
                <p style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">
                    Hasil sintesis antara Holland Code ({{ $careerResult->holland_code }}) dengan program keahlian <strong>{{ $recommendations['student_major'] ?? ($user->kelas ?? 'SMK') }}</strong>
                </p>
            </div>
            @if($majorCode)
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1" style="font-size: 0.76rem; font-weight: 700;">
                    Linier {{ $majorCode }}
                </span>
            @endif
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3 h-100" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                    <div style="font-size: 0.8rem; font-weight: 800; color: #1d4ed8; margin-bottom: 8px;">
                        <i class="bi bi-mortarboard-fill me-1"></i> Rekomendasi Jurusan Kuliah:
                    </div>
                    <ul class="mb-0 ps-3" style="font-size: 0.84rem; color: #1e3a8a; line-height: 1.6;">
                        @foreach(collect($listJurusan)->take(4) as $r)
                            <li><strong>{{ is_array($r) ? $r['name'] : $r->name }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3 h-100" style="background: #fffbeb; border: 1px solid #fde68a;">
                    <div style="font-size: 0.8rem; font-weight: 800; color: #b45309; margin-bottom: 8px;">
                        <i class="bi bi-briefcase-fill me-1"></i> Rekomendasi Profesi / Karier:
                    </div>
                    <ul class="mb-0 ps-3" style="font-size: 0.84rem; color: #78350f; line-height: 1.6;">
                        @foreach(collect($listProfesi)->take(4) as $r)
                            <li><strong>{{ is_array($r) ? $r['name'] : $r->name }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3 h-100" style="background: #faf5ff; border: 1px solid #ddd6fe;">
                    <div style="font-size: 0.8rem; font-weight: 800; color: #6d28d9; margin-bottom: 8px;">
                        <i class="bi bi-shop me-1"></i> Rekomendasi Bidang Usaha:
                    </div>
                    <ul class="mb-0 ps-3" style="font-size: 0.84rem; color: #4c1d95; line-height: 1.6;">
                        @foreach(collect($listUsaha)->take(4) as $r)
                            <li><strong>{{ is_array($r) ? $r['name'] : $r->name }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- TABEL 72 RINCIAN JAWABAN SISWA --}}
<div class="panel-card mb-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #f8fafc;">
        <div>
            <h2 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="bi bi-card-checklist text-primary me-2"></i>Rincian Jawaban Butir Kuesioner Siswa (72 Pertanyaan)
            </h2>
            <p style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">
                Daftar lengkap respon jawaban siswa dari skala 1 (Sangat Tidak Suka) hingga 5 (Sangat Suka)
            </p>
        </div>
        <ul class="nav nav-pills" id="tabJawaban" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active btn-sm fw-bold px-3 py-1.5" id="riasec-tab" data-bs-toggle="pill" data-bs-target="#tab-riasec" type="button" role="tab" style="font-size: 0.8rem; border-radius: 8px;">
                    Sesi 1: RIASEC ({{ $riasecAnswers->count() }} Soal)
                </button>
            </li>
            <li class="nav-item ms-2" role="presentation">
                <button class="nav-link btn-sm fw-bold px-3 py-1.5" id="anchor-tab" data-bs-toggle="pill" data-bs-target="#tab-anchor" type="button" role="tab" style="font-size: 0.8rem; border-radius: 8px;">
                    Sesi 2: Motivasi Kerja ({{ $anchorAnswers->count() }} Soal)
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="tabJawabanContent">
        {{-- TAB 1: RIASEC --}}
        <div class="tab-pane fade show active" id="tab-riasec" role="tabpanel">
            <div class="table-responsive-custom">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Pernyataan Minat Karier</th>
                            <th style="width: 140px; text-align: center;">Dimensi Holland</th>
                            <th style="width: 130px; text-align: center;">Skor Respon</th>
                            <th style="width: 180px;">Keterangan Respon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riasecAnswers as $ans)
                            @php
                                $dimCode = $ans->question->type_riasec;
                                $dimColors = [
                                    'R' => ['bg' => '#eff6ff', 'text' => '#1d4ed8'],
                                    'I' => ['bg' => '#f0f9ff', 'text' => '#0369a1'],
                                    'A' => ['bg' => '#fdf2f8', 'text' => '#be185d'],
                                    'S' => ['bg' => '#f0fdf4', 'text' => '#15803d'],
                                    'E' => ['bg' => '#fffbeb', 'text' => '#b45309'],
                                    'C' => ['bg' => '#f5f3ff', 'text' => '#6d28d9'],
                                ];
                                $color = $dimColors[$dimCode] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];

                                $scoreLabels = [
                                    1 => ['label' => 'Sangat Tidak Suka', 'class' => 'bg-danger text-white'],
                                    2 => ['label' => 'Tidak Suka', 'class' => 'bg-warning-subtle text-warning-emphasis border border-warning'],
                                    3 => ['label' => 'Netral / Ragu', 'class' => 'bg-secondary-subtle text-secondary border border-secondary'],
                                    4 => ['label' => 'Suka', 'class' => 'bg-primary-subtle text-primary border border-primary'],
                                    5 => ['label' => 'Sangat Suka', 'class' => 'bg-success text-white'],
                                ];
                                $resp = $scoreLabels[$ans->score] ?? ['label' => 'Skor ' . $ans->score, 'class' => 'bg-light text-dark'];
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #64748b;">
                                    {{ $ans->question->order_num ?? '-' }}
                                </td>
                                <td style="font-size: 0.85rem; color: #1e293b; font-weight: 500;">
                                    {{ $ans->question->question }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge font-monospace" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; border: 1px solid {{ $color['text'] }}33; font-size: 0.78rem; font-weight: 700; padding: 4px 10px;">
                                        {{ $dimCode }} ({{ \App\Services\RiasecService::DIMENSIONS[$dimCode] ?? '-' }})
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 1.15rem; font-weight: 900; color: #0f172a;">
                                        {{ $ans->score }}
                                    </span>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">/ 5</span>
                                </td>
                                <td>
                                    <span class="badge {{ $resp['class'] }}" style="font-size: 0.74rem; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                                        {{ $resp['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Tidak ada data rincian jawaban sesi 1.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: ANCHOR --}}
        <div class="tab-pane fade" id="tab-anchor" role="tabpanel">
            <div class="table-responsive-custom">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Pernyataan Motivasi & Gaya Kerja</th>
                            <th style="width: 170px; text-align: center;">Kategori Jangkar</th>
                            <th style="width: 130px; text-align: center;">Skor Respon</th>
                            <th style="width: 180px;">Keterangan Respon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anchorAnswers as $ans)
                            @php
                                $anchorCode = $ans->question->type_anchor;
                                $scoreLabels = [
                                    1 => ['label' => 'Sangat Tidak Setuju', 'class' => 'bg-danger text-white'],
                                    2 => ['label' => 'Tidak Setuju', 'class' => 'bg-warning-subtle text-warning-emphasis border border-warning'],
                                    3 => ['label' => 'Netral / Ragu', 'class' => 'bg-secondary-subtle text-secondary border border-secondary'],
                                    4 => ['label' => 'Setuju', 'class' => 'bg-primary-subtle text-primary border border-primary'],
                                    5 => ['label' => 'Sangat Setuju', 'class' => 'bg-success text-white'],
                                ];
                                $resp = $scoreLabels[$ans->score] ?? ['label' => 'Skor ' . $ans->score, 'class' => 'bg-light text-dark'];
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #64748b;">
                                    {{ $ans->question->order_num ?? '-' }}
                                </td>
                                <td style="font-size: 0.85rem; color: #1e293b; font-weight: 500;">
                                    {{ $ans->question->question }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px;">
                                        {{ $anchorCode }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 1.15rem; font-weight: 900; color: #0f172a;">
                                        {{ $ans->score }}
                                    </span>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">/ 5</span>
                                </td>
                                <td>
                                    <span class="badge {{ $resp['class'] }}" style="font-size: 0.74rem; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                                        {{ $resp['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Tidak ada data rincian jawaban sesi 2.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
