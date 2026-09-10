@extends('layouts.admin')

@section('title', 'Laporan & Ekspor Data')

@section('content')
<div class="page-title-row">
    <div>
        <h1>Laporan & Ekspor Data</h1>
        <p>Rekapitulasi komprehensif profil siswa, hasil RIASEC, dan rencana kelulusan</p>
    </div>
    <div class="date-badge">
        <i class="bi bi-file-earmark-excel-fill" style="color: #10b981;"></i>
        <span>Format Ekspor: CSV UTF-8 (Excel Ready)</span>
    </div>
</div>

{{-- 4 Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle stat-icon-total">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
                <div class="stat-subtext">Siswa Kelas 12</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background:#06b6d4;">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Tes Selesai</div>
                <div class="stat-value">{{ $totalTes }}</div>
                <div class="stat-subtext">Siswa telah tes RIASEC</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle" style="background:#8b5cf6;">
                <i class="bi bi-compass-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Rencana Terisi</div>
                <div class="stat-value">{{ $totalRencana }}</div>
                <div class="stat-subtext">Telah memilih rencana</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-custom">
            <div class="stat-icon-circle stat-icon-kuliah">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Data Lengkap</div>
                <div class="stat-value">{{ $totalLengkap }}</div>
                <div class="stat-subtext">Kedua tahap terselesaikan</div>
            </div>
        </div>
    </div>
</div>

{{-- Export Generator Panel --}}
<div class="panel-card">
    <div class="panel-header">
        <div>
            <h2>Ekspor Dokumen Laporan Lengkap</h2>
            <p>Filter data sebelum mengekspor file rekapitulasi sekolah</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.laporan.export') }}" class="p-3">
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <label for="expKelas" class="form-label fw-bold" style="font-size:0.84rem;">Filter Berdasarkan Kelas</label>
                <select name="kelas" id="expKelas" class="form-select filter-select">
                    <option value="Semua">Semua Kelas (815 Siswa)</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label for="expRencana" class="form-label fw-bold" style="font-size:0.84rem;">Filter Rencana Pilihan</label>
                <select name="rencana" id="expRencana" class="form-select filter-select">
                    <option value="Semua">Semua Rencana</option>
                    <option value="kuliah">Kuliah di Perguruan Tinggi</option>
                    <option value="bekerja">Bekerja di Industri</option>
                    <option value="berwirausaha">Berwirausaha Mandiri</option>
                    <option value="belum">Belum Menentukan Rencana</option>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label for="expRiasec" class="form-label fw-bold" style="font-size:0.84rem;">Filter Tipe Dominan RIASEC</label>
                <select name="riasec" id="expRiasec" class="form-select filter-select">
                    <option value="Semua">Semua Tipe Dominan</option>
                    <option value="Realistic">Realistic (Praktis & Teknis)</option>
                    <option value="Investigative">Investigative (Analitis & Logika)</option>
                    <option value="Artistic">Artistic (Kreatif & Seni)</option>
                    <option value="Social">Social (Sosial & Membimbing)</option>
                    <option value="Enterprising">Enterprising (Bisnis & Memimpin)</option>
                    <option value="Conventional">Conventional (Terstruktur & Data)</option>
                </select>
            </div>
        </div>

        <div class="p-4 rounded-3 mb-4" style="background:#f8fafc; border:1px solid #e2e8f0;">
            <div style="font-size:0.86rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                <i class="bi bi-file-earmark-spreadsheet-fill text-success me-1"></i> Kolom yang disertakan dalam Laporan Ekspor:
            </div>
            <div style="font-size:0.8rem; color:#64748b; line-height:1.6;">
                Nomor, Nama Lengkap Siswa, NISN, Kelas, Tempat/Tanggal Lahir, Status Tes, Holland Code (3 Huruf), Tipe Dominan, Nilai 6 Dimensi (R, I, A, S, E, C), Status Rencana, Rencana Pilihan (Kuliah/Kerja/Usaha), Perguruan Tinggi, Program Studi, Jenjang, Akreditasi, Bidang Pekerjaan, Keterangan Pekerjaan, Bidang Usaha, Keterangan Usaha, Status Kelengkapan, Waktu Submit.
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn-brand-primary px-4 py-3" style="font-size: 0.92rem;">
                <i class="bi bi-download me-2"></i> Unduh File Laporan (CSV / Excel)
            </button>
        </div>
    </form>
</div>
@endsection
