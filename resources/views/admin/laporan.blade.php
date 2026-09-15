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

{{-- Cetak Form Rekapitulasi Sekolah (Sesuai Blanko Fisik) --}}
<div class="panel-card mb-4" style="border: 2px solid #3b82f6; background: linear-gradient(to right, #ffffff, #f0f7ff);">
    <div class="panel-header" style="border-bottom: 1px solid #bfdbfe;">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">FORMAT RESMI SEKOLAH</span>
                <span class="badge bg-success px-2 py-1" style="font-size: 0.72rem;">DATA REVISI AKTIF</span>
            </div>
            <h2 class="mt-1" style="color: #1e3a8a;"><i class="bi bi-printer-fill me-2 text-primary"></i>Cetak Form Laporan Hasil & Rekapitulasi Siswa</h2>
            <p style="color: #475569;">Format cetak landscape standar blanko sekolah (NO, NISN, NAMA, L/P, NIK, TTL, ALAMAT, HP/WA, MINAT BEKERJA/KULIAH/WIRAUSAHA, REKAP L/P, TTD GURU BK)</p>
        </div>
    </div>

    <div class="p-3">
        <form method="GET" action="{{ route('admin.laporan.cetak') }}" target="_blank" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="cetakKelas" class="form-label fw-bold" style="font-size:0.84rem;">Pilih Kelas untuk Dicetak</label>
                <select name="kelas" id="cetakKelas" class="form-select filter-select">
                    <option value="Semua">Seluruh Siswa (Semua Kelas)</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label for="cetakTahun" class="form-label fw-bold" style="font-size:0.84rem;">Tahun Lulus</label>
                <input type="text" name="tahun_lulus" id="cetakTahun" class="form-control" value="TAHUN 2025" style="border-radius: 8px; font-size: 0.88rem;">
            </div>

            <div class="col-12 col-md-3">
                <label for="cetakMode" class="form-label fw-bold" style="font-size:0.84rem;">Mode Isian</label>
                <select name="mode" id="cetakMode" class="form-select filter-select">
                    <option value="isi">Data Terisi Otomatis dari Sistem</option>
                    <option value="kosong">Blanko Kosong (Sesuai Foto / Pengisian Manual)</option>
                </select>
            </div>

            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-bold" style="background-color: #2563eb; border-color: #2563eb; border-radius: 8px; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                    <i class="bi bi-printer"></i> Buka Form
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Export Generator Panel --}}
<div class="panel-card">
    <div class="panel-header">
        <div>
            <h2>Ekspor Dokumen Laporan Lengkap (CSV / Excel)</h2>
            <p>Filter data sebelum mengekspor file rekapitulasi sekolah yang memuat seluruh biodata baru dan status rencana</p>
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
                <i class="bi bi-file-earmark-spreadsheet-fill text-success me-1"></i> Kolom yang disertakan dalam Laporan Ekspor Lengkap:
            </div>
            <div style="font-size:0.8rem; color:#64748b; line-height:1.6;">
                Nomor, Nama Lengkap Siswa, NIPD, L/P, NISN, NIK, Kelas, Tempat/Tanggal Lahir, Alamat, RT, RW, Kelurahan, Kecamatan, Kabupaten/Kota, Kode Pos, HP/WA, Minat Bekerja (V), Minat Melanjutkan (V), Minat Wirausaha (V), Keterangan Minat Pilihan, Perguruan Tinggi, Program Studi, Jenjang, Akreditasi, Bidang Pekerjaan, Keterangan Pekerjaan, Bidang Usaha, Keterangan Usaha, Status Tes RIASEC, Holland Code, Tipe Dominan, Status Rencana, Status Kelengkapan, Waktu Submit.
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn-brand-primary px-4 py-3" style="font-size: 0.92rem;">
                <i class="bi bi-download me-2"></i> Unduh File Laporan Lengkap (CSV / Excel)
            </button>
        </div>
    </form>
</div>
@endsection
