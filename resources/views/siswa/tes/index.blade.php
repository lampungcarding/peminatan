@extends('layouts.app')

@section('title', 'Tes Minat Karier & Jangkar Karier (RIASEC + Career Anchors)')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        {{-- Hero Header --}}
        <div class="card-pro p-4 p-md-5 mb-4 text-center" style="background: #1F355F; color: #ffffff; border: 1px solid #1F355F; border-radius: 12px; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.18); width: 56px; height: 56px;">
                <i class="bi bi-compass fs-2 text-white"></i>
            </div>
            <h1 style="font-size: 1.7rem; font-weight: 700; letter-spacing: -0.01em; margin-bottom: 8px;">
                Tes Minat Karier & Jangkar Karier Digital
            </h1>
            <p style="font-size: 0.92rem; color: #E4E7EC; max-width: 720px; margin: 0 auto; line-height: 1.6;">
                Sistem analisis berbasis <strong>Model Holland (RIASEC)</strong> &amp; <strong>Career Anchors (Edgar Schein)</strong>. Menentukan objek keahlianmu sekaligus mengunci jalur eksekusi terbaik (Kuliah, Kerja, atau Berwirausaha).
            </p>
        </div>

        {{-- Jika Sudah Pernah Tes --}}
        @if($hasTakenTest && $careerResult)
            <div class="card-pro p-4 mb-4" style="background: #F0FDF4; border: 1px solid #B7E4C7; border-radius: 12px;">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 44px; height: 44px; border-radius: 8px; background: #3F7D58; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; font-weight: 600; color: #276749; text-transform: uppercase;">
                                Tes Telah Diselesaikan
                            </div>
                            <div style="font-size: 1rem; font-weight: 700; color: #1F2937;">
                                Holland Code: {{ $careerResult->holland_code }} ({{ $careerResult->dominant_type }}) | Jangkar: {{ $careerResult->dominant_anchor_name ?? $careerResult->dominant_anchor }}
                            </div>
                            <div class="mt-1">
                                <span class="badge" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.75rem; padding: 4px 8px;">
                                    {{ $careerResult->execution_path_badge['label'] }}
                                </span>
                                <span style="font-size: 0.8rem; color: #667085;" class="ms-2">
                                    Selesai pada: {{ $careerResult->completed_at ? $careerResult->completed_at->format('d M Y, H:i') : '-' }} WIB
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                        <a href="{{ route('tes.hasil') }}" class="btn-brand-primary py-2 px-3" style="font-size: 0.85rem;">
                            <i class="bi bi-bar-chart-fill me-1"></i> Lihat Hasil & Rekomendasi
                        </a>
                        <div class="bg-white text-secondary border px-3 py-2 fw-medium d-flex align-items-center gap-1" style="font-size: 0.8rem; border-radius: 8px; border-color: #E4E7EC !important;">
                            <i class="bi bi-info-circle me-1" style="color: #3157A4;"></i> Reset via <strong>Guru BK</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Penjelasan Dual Sesi --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="card-pro p-4 h-100" style="background: #ffffff; border: 1px solid #E4E7EC; border-left: 4px solid #3157A4; border-radius: 12px;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge" style="background: #EFF4FB; color: #3157A4; border: 1px solid #D0D5DD; border-radius: 6px; font-size: 0.75rem;">Sesi 1</span>
                        <h3 class="mb-0 fs-6 fw-bold" style="color: #1F2937;">Minat Karier Digital (RIASEC)</h3>
                    </div>
                    <p style="font-size: 0.85rem; color: #667085; line-height: 1.6;" class="mb-0">
                        <strong>48 Pertanyaan.</strong> Digunakan untuk menentukan <strong>Rekomendasi Jurusan Kuliah &amp; Spesifikasi Profesi</strong> (Objek/Bidang kerjanya) berdasarkan 6 tipe kepribadian Holland.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card-pro p-4 h-100" style="background: #ffffff; border: 1px solid #E4E7EC; border-left: 4px solid #B7791F; border-radius: 12px;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge" style="background: #FFF7E6; color: #8A6116; border: 1px solid #F1D99B; border-radius: 6px; font-size: 0.75rem;">Sesi 2</span>
                        <h3 class="mb-0 fs-6 fw-bold" style="color: #1F2937;">Angket Motivasi Kerja (Career Anchors)</h3>
                    </div>
                    <p style="font-size: 0.85rem; color: #667085; line-height: 1.6;" class="mb-0">
                        <strong>24 Pertanyaan.</strong> Digunakan untuk mengunci <strong>Jalur Eksekusi Akhir</strong> (Apakah bidang tersebut harus dijalani via <strong>Kuliah</strong>, <strong>Kerja Langsung</strong>, atau <strong>Berwirausaha</strong>).
                    </p>
                </div>
            </div>
        </div>

        {{-- Petunjuk Pengerjaan & Tombol Mulai --}}
        <div class="card-pro p-4 mb-4" style="background: #FFFFFF; border: 1px solid #E4E7EC; border-radius: 12px; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-8">
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #1F2937; margin-bottom: 6px;">
                        Petunjuk Pengerjaan
                    </h3>
                    <ul class="mb-0 ps-3" style="font-size: 0.85rem; color: #667085; line-height: 1.7;">
                        <li>Total terdapat <strong>{{ $totalQuestions }} butir pertanyaan</strong> (Sesi 1: {{ $riasecCount ?? 48 }} RIASEC + Sesi 2: {{ $anchorCount ?? 24 }} Career Anchors).</li>
                        <li>Pilihlah respon dari skala <strong>1 (Sangat Tidak Suka)</strong> hingga <strong>5 (Sangat Suka)</strong> sesuai kondisi hatimu.</li>
                        <li><strong>Tidak ada jawaban salah atau benar</strong>. Kejujuran responmu akan menghasilkan rekomendasi kurasi yang paling akurat.</li>
                        <li>Estimasi waktu pengerjaan sekitar <strong>6 hingga 10 menit</strong>.</li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="{{ route('tes.mulai') }}" class="btn-brand-primary w-100 py-2 px-4 text-center">
                        <i class="bi bi-play-circle me-1"></i> Mulai Mengerjakan Tes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

