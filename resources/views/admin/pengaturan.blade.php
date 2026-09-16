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

            <form method="POST" action="{{ route('admin.pengaturan.umum') }}" enctype="multipart/form-data">
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

                {{-- SECTION KHUSUS: UPLOAD GAMBAR KOP SURAT RESMI --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">
                                <i class="bi bi-image text-primary me-2"></i>Gambar KOP Surat Resmi Sekolah
                            </h3>
                            <p style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">
                                Unggah file gambar KOP surat (banner resmi sekolah). Gambar ini langsung dipasang di bagian atas seluruh lembar cetak dokumen & laporan
                            </p>
                        </div>
                    </div>

                    {{-- Status KOP Saat Ini --}}
                    @if(!empty($settings['kop_gambar']) && file_exists(public_path($settings['kop_gambar'])))
                        <div class="card mb-3 p-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.74rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Gambar KOP Aktif Terpasang
                                </span>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        style="font-size: 0.75rem; padding: 3px 10px; border-radius: 6px;"
                                        onclick="if(confirm('Apakah Anda yakin ingin menghapus gambar KOP surat sekolah ini?')) document.getElementById('formHapusKop').submit();">
                                    <i class="bi bi-trash me-1"></i> Hapus KOP
                                </button>
                            </div>
                            <div style="background: #ffffff; padding: 12px; border-radius: 6px; border: 1px solid #cbd5e1; text-align: center;">
                                <img src="{{ asset($settings['kop_gambar']) }}"
                                     alt="KOP Surat Resmi"
                                     style="width: 100%; max-height: 120px; object-fit: contain; display: block; margin: 0 auto;">
                            </div>
                            <small class="text-muted mt-2 d-block" style="font-size: 0.72rem;">
                                <i class="bi bi-info-circle me-1"></i> Gambar di atas saat ini aktif digunakan pada cetak Rekapitulasi Siswa, Lembar Pilihan, dan Lembar Tes RIASEC.
                            </small>
                        </div>
                    @else
                        <div class="p-3 mb-3 d-flex align-items-center gap-3" style="background: #f1f5f9; border: 1px dashed #94a3b8; border-radius: 8px;">
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #64748b;">
                                <i class="bi bi-image"></i>
                            </div>
                            <div style="font-size: 0.8rem; color: #475569;">
                                <strong>Belum ada gambar KOP surat yang diunggah.</strong><br>
                                Dokumen cetak saat ini menggunakan kop nama sekolah teks standar. Silakan unggah gambar KOP sekolah di bawah ini.
                            </div>
                        </div>
                    @endif

                    {{-- Input Unggah File Baru --}}
                    <div class="mb-3">
                        <label for="kop_gambar" class="form-label" style="font-weight:600; font-size:0.84rem; color:#475569;">
                            {{ !empty($settings['kop_gambar']) ? 'Ganti / Unggah Gambar KOP Baru' : 'Pilih File Gambar KOP Surat' }}
                        </label>
                        <input type="file"
                               class="filter-select @error('kop_gambar') is-invalid @enderror"
                               id="kop_gambar"
                               name="kop_gambar"
                               accept="image/png, image/jpeg, image/jpg, image/webp"
                               onchange="previewSelectedKop(this)">
                        <div class="form-text" style="font-size:0.75rem; color:#64748b; margin-top: 4px;">
                            <i class="bi bi-info-circle text-primary me-1"></i>
                            Format file: <strong>PNG, JPG, JPEG, WEBP</strong> (Maks. 3 MB). Disarankan menggunakan banner memanjang/horizontal (rasio ~5:1 s/d 8:1) dengan resolusi tajam agar hasil cetak PDF / print rapi.
                        </div>
                        @error('kop_gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Live Preview Sebelum Simpan --}}
                    <div id="new_kop_preview_container" class="p-3 mb-4 d-none" style="background: #f0fdf4; border: 1.5px dashed #22c55e; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-success" style="font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase;">
                                <i class="bi bi-eye me-1"></i> Pratinjau File Baru Yang Dipilih
                            </span>
                            <small class="text-success fw-semibold" id="new_kop_filename" style="font-size: 0.74rem;"></small>
                        </div>
                        <div style="background: #ffffff; padding: 12px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); text-align: center;">
                            <img id="new_kop_preview_img" src="" alt="Pratinjau KOP Baru" style="width: 100%; max-height: 120px; object-fit: contain; display: block; margin: 0 auto;">
                        </div>
                        <small class="text-muted mt-2 d-block text-center" style="font-size: 0.72rem;">
                            Klik tombol <strong>"Simpan Pengaturan & KOP Sekolah"</strong> di bawah untuk menerapkan perubahan ini.
                        </small>
                    </div>
                </div>

                <button type="submit" class="btn-filter" style="width: auto; padding: 10px 24px;">
                    <i class="bi bi-save me-2"></i> Simpan Pengaturan & KOP Sekolah
                </button>
            </form>

            {{-- Form tersembunyi untuk menghapus KOP --}}
            <form id="formHapusKop" action="{{ route('admin.pengaturan.kop.hapus') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <script>
            function previewSelectedKop(input) {
                const container = document.getElementById('new_kop_preview_container');
                const img = document.getElementById('new_kop_preview_img');
                const filenameLabel = document.getElementById('new_kop_filename');

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    // Validasi ukuran sisi klien (maks 3MB)
                    if (file.size > 3 * 1024 * 1024) {
                        alert('Ukuran file gambar melebihi batas 3 MB. Silakan pilih file dengan ukuran lebih kecil.');
                        input.value = '';
                        container.classList.add('d-none');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        filenameLabel.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                        container.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    container.classList.add('d-none');
                    img.src = '';
                    filenameLabel.textContent = '';
                }
            }
        </script>

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
                    <h2><i class="bi bi-cloud-check me-2 text-success"></i>Tracer Vokasi Kemendikdasmen</h2>
                    <p>Status pangkalan data 4.800+ kampus & API getProdi</p>
                </div>
            </div>

            <div class="p-3 mb-3" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="width:10px; height:10px; border-radius:50%; background:#10b981; display:inline-block;"></span>
                    <strong style="color:#166534; font-size:0.88rem;">Terkoneksi & Siap Digunakan</strong>
                </div>
                <div style="font-size:0.78rem; color:#15803d; line-height:1.4;">
                    Basis data <strong>4.800+ Perguruan Tinggi</strong> aktif dengan autentikasi otomatis NISN & endpoint <code>api/getProdi</code> Kemendikdasmen.
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.sync-kip') }}">
                @csrf
                <button type="submit" class="btn-export-outline w-100 justify-content-center py-2" onclick="return confirm('Sinkronkan ulang koneksi dan cache Tracer Vokasi?');">
                    <i class="bi bi-arrow-repeat"></i> Sinkronkan Ulang Basis Data Kampus
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
