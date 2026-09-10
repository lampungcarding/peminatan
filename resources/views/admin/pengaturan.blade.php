@extends('layouts.admin')

@section('title', 'Pengaturan Aplikasi')

@section('content')
{{-- Page Title --}}
<div class="page-title-row">
    <div>
        <h1>Pengaturan Aplikasi</h1>
        <p>Konfigurasi identitas sekolah, periode pendataan, akun administrator, dan pemeliharaan sistem</p>
    </div>
</div>

<div class="row g-4">
    {{-- Kolom Kiri: Pengaturan Umum & Pendataan --}}
    <div class="col-12 col-lg-7">
        <div class="panel-card mb-4">
            <div class="panel-header">
                <div>
                    <h2><i class="bi bi-sliders me-2 text-primary"></i>Identitas Sekolah & Pendataan</h2>
                    <p>Atur informasi sekolah dan batas waktu pengisian rencana siswa</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.umum') }}">
                @csrf

                <div class="mb-3">
                    <label for="nama_sekolah" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                        Nama Sekolah / Institusi
                    </label>
                    <input type="text"
                           class="filter-select @error('nama_sekolah') is-invalid @enderror"
                           id="nama_sekolah"
                           name="nama_sekolah"
                           value="{{ old('nama_sekolah', $settings['nama_sekolah']) }}"
                           required>
                    @error('nama_sekolah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nama_aplikasi" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            Nama Aplikasi
                        </label>
                        <input type="text"
                               class="filter-select @error('nama_aplikasi') is-invalid @enderror"
                               id="nama_aplikasi"
                               name="nama_aplikasi"
                               value="{{ old('nama_aplikasi', $settings['nama_aplikasi']) }}"
                               required>
                        @error('nama_aplikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tahun_ajaran" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            Tahun Ajaran / Angkatan
                        </label>
                        <input type="text"
                               class="filter-select @error('tahun_ajaran') is-invalid @enderror"
                               id="tahun_ajaran"
                               name="tahun_ajaran"
                               value="{{ old('tahun_ajaran', $settings['tahun_ajaran']) }}"
                               placeholder="Contoh: 2024/2025"
                               required>
                        @error('tahun_ajaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="batas_pengisian" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            Batas Akhir Pengisian
                        </label>
                        <input type="date"
                               class="filter-select @error('batas_pengisian') is-invalid @enderror"
                               id="batas_pengisian"
                               name="batas_pengisian"
                               value="{{ old('batas_pengisian', $settings['batas_pengisian']) }}"
                               required>
                        @error('batas_pengisian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status_pendataan" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            Status Pendataan Siswa
                        </label>
                        <select name="status_pendataan" id="status_pendataan" class="filter-select">
                            <option value="buka" {{ old('status_pendataan', $settings['status_pendataan']) === 'buka' ? 'selected' : '' }}>
                                🟢 Buka (Siswa dapat mengisi rencana)
                            </option>
                            <option value="tutup" {{ old('status_pendataan', $settings['status_pendataan']) === 'tutup' ? 'selected' : '' }}>
                                🔴 Tutup (Pengisian dinonaktifkan)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="pesan_pengumuman" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                        Pesan Pengumuman / Instruksi untuk Siswa
                    </label>
                    <textarea class="filter-select @error('pesan_pengumuman') is-invalid @enderror"
                              id="pesan_pengumuman"
                              name="pesan_pengumuman"
                              rows="3"
                              placeholder="Tuliskan petunjuk atau instruksi untuk siswa saat mengisi...">{{ old('pesan_pengumuman', $settings['pesan_pengumuman']) }}</textarea>
                    @error('pesan_pengumuman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-filter" style="width: auto; padding: 10px 24px;">
                    <i class="bi bi-save me-2"></i> Simpan Pengaturan Umum
                </button>
            </form>
        </div>

        {{-- Profil & Keamanan Admin --}}
        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <h2><i class="bi bi-shield-lock me-2 text-primary"></i>Akun & Keamanan Administrator</h2>
                    <p>Perbarui profil dan ganti kata sandi akun admin</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.password') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="admin_name" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            Nama Admin
                        </label>
                        <input type="text"
                               class="filter-select @error('name') is-invalid @enderror"
                               id="admin_name"
                               name="name"
                               value="{{ old('name', $adminUser->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="admin_email" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            Email Admin
                        </label>
                        <input type="email"
                               class="filter-select @error('email') is-invalid @enderror"
                               id="admin_email"
                               name="email"
                               value="{{ old('email', $adminUser->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="p-3 mb-4" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                    <div class="fw-semibold text-dark mb-2" style="font-size:0.84rem;">
                        <i class="bi bi-key me-1"></i> Ubah Password (Kosongkan jika tidak ingin mengubah)
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label" style="font-size:0.78rem; font-weight:600; color:#64748b;">
                                Password Saat Ini
                            </label>
                            <input type="password"
                                   class="filter-select @error('current_password') is-invalid @enderror"
                                   id="current_password"
                                   name="current_password"
                                   placeholder="••••••••">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="new_password" class="form-label" style="font-size:0.78rem; font-weight:600; color:#64748b;">
                                Password Baru
                            </label>
                            <input type="password"
                                   class="filter-select @error('new_password') is-invalid @enderror"
                                   id="new_password"
                                   name="new_password"
                                   placeholder="Min 6 karakter">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="new_password_confirmation" class="form-label" style="font-size:0.78rem; font-weight:600; color:#64748b;">
                                Ulangi Password Baru
                            </label>
                            <input type="password"
                                   class="filter-select"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   placeholder="Ulangi password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-filter" style="width: auto; padding: 10px 24px;">
                    <i class="bi bi-person-check me-2"></i> Perbarui Akun Admin
                </button>
            </form>
        </div>
    </div>

    {{-- Kolom Kanan: Pemeliharaan Sistem & Integrasi --}}
    <div class="col-12 col-lg-5">
        {{-- Card: KIP Kuliah API Status --}}
        <div class="panel-card mb-4">
            <div class="panel-header mb-3">
                <div>
                    <h2><i class="bi bi-cloud-check me-2 text-success"></i>Integrasi KIP Kuliah</h2>
                    <p>Status integrasi endpoint prodijson kementerian</p>
                </div>
            </div>

            <div class="p-3 mb-3" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="width:10px; height:10px; border-radius:50%; background:#10b981; display:inline-block;"></span>
                    <strong style="color:#166534; font-size:0.88rem;">Terkoneksi & Siap Digunakan</strong>
                </div>
                <div style="font-size:0.78rem; color:#15803d; line-height:1.4;">
                    Endpoint <code>https://kip-kuliah.kemdiktisaintek.go.id/prodijson</code> aktif dengan sesi otomatis & caching responsif.
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.sync-kip') }}">
                @csrf
                <button type="submit" class="btn-export-outline w-100 justify-content-center py-2" onclick="return confirm('Sinkronkan ulang koneksi dan cache KIP Kuliah?');">
                    <i class="bi bi-arrow-repeat"></i> Sinkronkan Ulang KIP Kuliah
                </button>
            </form>
        </div>

        {{-- Card: Cache Aplikasi --}}
        <div class="panel-card mb-4">
            <div class="panel-header mb-3">
                <div>
                    <h2><i class="bi bi-lightning-charge me-2 text-warning"></i>Cache & Optimasi Sistem</h2>
                    <p>Bersihkan file cache sementara dan template view yang tersimpan</p>
                </div>
            </div>

            <div style="font-size:0.82rem; color:#64748b; margin-bottom:14px; line-height:1.5;">
                Jika Anda melakukan perubahan konfigurasi atau ingin menyegarkan seluruh tampilan di sistem, gunakan tombol di bawah:
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.clear-cache') }}">
                @csrf
                <button type="submit" class="btn-export-outline w-100 justify-content-center py-2">
                    <i class="bi bi-trash3"></i> Bersihkan Cache Sistem
                </button>
            </form>
        </div>

        {{-- Card: Impor Ulang Excel --}}
        <div class="panel-card">
            <div class="panel-header mb-3">
                <div>
                    <h2><i class="bi bi-file-earmark-spreadsheet me-2 text-primary"></i>Sumber Data Siswa</h2>
                    <p>Sinkronisasi data master dari file Excel</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 p-3 mb-3" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                <div style="width:40px; height:40px; border-radius:8px; background:#dbeafe; color:#1e40af; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                    <i class="bi bi-filetype-xlsx"></i>
                </div>
                <div>
                    <div style="font-weight:700; color:#0f172a; font-size:0.86rem;">KELAS 12.xlsx</div>
                    <div style="font-size:0.75rem; color:#64748b;">Lokasi: Root folder project</div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.reimport-excel') }}" onsubmit="return confirm('Apakah Anda yakin ingin mengimpor ulang data dari KELAS 12.xlsx? Akun siswa akan diperbarui.');">
                @csrf
                <button type="submit" class="btn-export-outline w-100 justify-content-center py-2 text-primary">
                    <i class="bi bi-cloud-arrow-up"></i> Impor Ulang dari KELAS 12.xlsx
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
