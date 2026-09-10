@extends('layouts.app')

@section('title', 'Tes Minat Karier & Jangkar Karier (RIASEC + Career Anchors)')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        {{-- Hero Header --}}
        <div class="card-pro p-4 p-md-5 mb-4 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%); color: #ffffff; border:none; border-radius: var(--radius-xl);">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="background: rgba(255, 255, 255, 0.15); width: 64px; height: 64px;">
                <i class="bi bi-compass-fill fs-2 text-white"></i>
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 10px;">
                Tes Minat Karier & Jangkar Karier Digital
            </h1>
            <p style="font-size: 0.95rem; color: #e0e7ff; max-width: 720px; margin: 0 auto; line-height: 1.6;">
                Sistem analisis berbasis <strong>Model Holland (RIASEC)</strong> &amp; <strong>Career Anchors (Edgar Schein)</strong>. Menentukan objek keahlianmu sekaligus mengunci jalur eksekusi terbaik (Kuliah, Kerja, atau Berwirausaha).
            </p>
        </div>

        {{-- Jika Sudah Pernah Tes --}}
        @if($hasTakenTest && $careerResult)
            <div class="card-pro p-4 mb-4 border-success-subtle bg-success-subtle bg-opacity-25">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: #166534; text-transform: uppercase;">
                                Tes Telah Diselesaikan
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #065f46;">
                                Holland Code: {{ $careerResult->holland_code }} ({{ $careerResult->dominant_type }}) | Jangkar: {{ $careerResult->dominant_anchor_name ?? $careerResult->dominant_anchor }}
                            </div>
                            <div class="mt-1">
                                <span class="badge {{ $careerResult->execution_path_badge['class'] }} me-1">
                                    {{ $careerResult->execution_path_badge['label'] }}
                                </span>
                                <span style="font-size: 0.8rem; color: #047857;">
                                    Selesai pada: {{ $careerResult->completed_at ? $careerResult->completed_at->format('d M Y, H:i') : '-' }} WIB
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                        <a href="{{ route('tes.hasil') }}" class="btn btn-success fw-bold px-3 py-2" style="border-radius: var(--radius-md); font-size: 0.85rem;">
                            <i class="bi bi-bar-chart-fill me-1"></i> Lihat Hasil & Rekomendasi
                        </a>
                        <div class="bg-white text-secondary border px-3 py-2 fw-semibold d-flex align-items-center gap-1 rounded-3" style="font-size: 0.8rem;">
                            <i class="bi bi-info-circle text-primary"></i> Untuk mengulang / reset tes, silakan <strong>hubungi Guru BK</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Penjelasan Dual Sesi --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="card-pro p-4 h-100 style-border-left" style="border-left: 5px solid #3b82f6;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary fs-6">Sesi 1</span>
                        <h3 class="mb-0 fs-6 fw-bold text-dark">Minat Karier Digital (RIASEC)</h3>
                    </div>
                    <p style="font-size: 0.85rem; color: #475569; line-height: 1.6;">
                        <strong>48 Pertanyaan.</strong> Digunakan untuk menentukan <strong>Rekomendasi Jurusan Kuliah &amp; Spesifikasi Profesi</strong> (Objek/Bidang kerjanya) berdasarkan 6 tipe kepribadian Holland.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card-pro p-4 h-100 style-border-left" style="border-left: 5px solid #f59e0b;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark fs-6">Sesi 2</span>
                        <h3 class="mb-0 fs-6 fw-bold text-dark">Angket Motivasi Kerja (Career Anchors)</h3>
                    </div>
                    <p style="font-size: 0.85rem; color: #475569; line-height: 1.6;">
                        <strong>24 Pertanyaan.</strong> Digunakan untuk mengunci <strong>Jalur Eksekusi Akhir</strong> (Apakah bidang tersebut harus dijalani via <strong>Kuliah</strong>, <strong>Kerja Langsung</strong>, atau <strong>Berwirausaha</strong>).
                    </p>
                </div>
            </div>
        </div>

        {{-- Petunjuk Pengerjaan & Tombol Mulai --}}
        <div class="card-pro p-4 p-md-4 mb-4" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-8">
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                        Petunjuk Pengerjaan
                    </h3>
                    <ul class="mb-0 ps-3" style="font-size: 0.85rem; color: #475569; line-height: 1.7;">
                        <li>Total terdapat <strong>{{ $totalQuestions }} butir pertanyaan</strong> (Sesi 1: {{ $riasecCount ?? 48 }} RIASEC + Sesi 2: {{ $anchorCount ?? 24 }} Career Anchors).</li>
                        <li>Pilihlah respon dari skala <strong>1 (Sangat Tidak Suka)</strong> hingga <strong>5 (Sangat Suka)</strong> sesuai kondisi hatimu.</li>
                        <li><strong>Tidak ada jawaban salah atau benar</strong>. Kejujuran responmu akan menghasilkan rekomendasi kurasi yang paling akurat.</li>
                        <li>Estimasi waktu pengerjaan sekitar <strong>6 hingga 10 menit</strong>.</li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="{{ route('tes.mulai') }}" class="btn-brand-primary w-100 py-3 text-center">
                        <i class="bi bi-play-circle-fill"></i> Mulai Mengerjakan Tes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

