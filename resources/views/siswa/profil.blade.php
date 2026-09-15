@extends('layouts.app')

@section('title', 'Biodata & Profil Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">

        {{-- Success / Warning Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm p-3 mb-4" role="alert" style="background-color: #ECFDF5; border-left: 4px solid #10B981 !important; border-radius: 8px; color: #065F46;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm p-3 mb-4" role="alert" style="background-color: #FEF2F2; border-left: 4px solid #DC2626 !important; border-radius: 8px; color: #991B1B;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <strong>Mohon periksa kembali isian formulir:</strong>
                </div>
                <ul class="mb-0 ps-4 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Profile Header Card --}}
        <div class="card-pro p-4 mb-4" style="background: linear-gradient(135deg, #1F355F 0%, #2A487D 100%); color: #ffffff; border-radius: 12px;">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="student-avatar" style="width: 64px; height: 64px; font-size: 1.75rem; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); font-size: 0.76rem; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                Kelas {{ $user->kelas ?? '-' }}
                            </span>
                            <span class="badge font-monospace" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); font-size: 0.76rem; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                NISN: {{ $user->nisn ?? '-' }}
                            </span>
                            @if($user->jk)
                                <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); font-size: 0.76rem; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                    {{ $user->jenis_kelamin_text }}
                                </span>
                            @endif
                        </div>
                        <h1 style="font-size: 1.45rem; font-weight: 800; margin: 0; line-height: 1.2;">
                            {{ $user->name }}
                        </h1>
                        <div style="font-size: 0.82rem; color: #E2E8F0; margin-top: 4px;">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-sm btn-light fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="bi bi-house me-1"></i> Beranda
                    </a>
                    <a href="{{ route('siswa.pilihan-saya') }}" class="btn btn-sm btn-outline-light fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="bi bi-file-earmark-person me-1"></i> Bukti Pilihan
                    </a>
                </div>
            </div>
        </div>

        {{-- Notice Banner --}}
        @if(!$user->is_biodata_confirmed)
            <div class="p-3 mb-4 rounded-3 d-flex align-items-start gap-3" style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B !important;">
                <i class="bi bi-shield-exclamation fs-4 text-warning mt-1"></i>
                <div style="font-size: 0.85rem; color: #92400E; line-height: 1.55;">
                    <strong class="d-block mb-1 fs-6">Tahap Wajib 1: Konfirmasi Kebenaran Biodata Diri</strong>
                    Silakan teliti dan perbaiki data dirimu (NIK, TTL, No HP/WA, dan Alamat Domisili). Setelah sesuai, klik tombol <strong>"Simpan & Konfirmasi Biodata Diri"</strong> di bawah untuk membuka akses ke <strong>Tes Minat RIASEC</strong> dan <strong>Rencana Kelulusan</strong>.
                    @if($careerResult || $pilihan)
                        <div class="mt-2 pt-2 border-top border-warning-subtle text-dark" style="font-size: 0.81rem; background: rgba(255,255,255,0.7); padding: 8px 12px; border-radius: 6px;">
                            <i class="bi bi-shield-check text-success fs-6 me-1"></i>
                            <strong>Jaminan Keamanan Data:</strong> Kamu sudah memiliki data pengisian tes atau rencana sebelumnya. <strong>Konfirmasi biodata ini 100% aman dan tidak akan menghapus atau mengubah hasil tes maupun pilihan rencanamu.</strong>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-3 mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #ECFDF5; border: 1px solid #A7F3D0; border-left: 4px solid #10B981 !important;">
                <div class="d-flex align-items-center gap-2" style="font-size: 0.85rem; color: #065F46;">
                    <i class="bi bi-shield-check fs-4 text-success"></i>
                    <div>
                        <strong>Biodata Terkonfirmasi:</strong> Data dirimu telah dikonfirmasi pada <strong>{{ $user->biodata_confirmed_at ? $user->biodata_confirmed_at->format('d M Y H:i') : '-' }} WIB</strong>. Kamu tetap dapat melakukan perubahan dan menyimpan data jika ada pembaruan.
                    </div>
                </div>
                <span class="badge bg-success px-3 py-2" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i> Terverifikasi</span>
            </div>
        @endif

        {{-- Form Edit Biodata Siswa --}}
        <div class="card-pro p-4 mb-4" style="background: #ffffff; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: #0F172A; margin: 0;">
                        <i class="bi bi-person-vcard text-primary me-2"></i>Formulir Data Diri Siswa
                    </h2>
                    <p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 0 0;">
                        Pastikan seluruh kolom terisi dengan data yang benar dan sah
                    </p>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="font-size: 0.78rem; border-radius: 8px;">
                    <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi Otomatis ke Admin
                </span>
            </div>

            <form action="{{ route('siswa.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Section 1: Identitas Pokok --}}
                <div class="mb-4">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                        1. Identitas Pokok Siswa
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">NISN (Resmi Sekolah)</label>
                            <input type="text" class="form-control bg-light font-monospace" value="{{ $user->nisn }}" readonly disabled style="border-radius: 8px; font-size: 0.88rem;">
                            <div class="form-text text-muted" style="font-size: 0.72rem;">Nomor NISN dikunci oleh pihak sekolah.</div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">Kelas Siswa</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->kelas }}" readonly disabled style="border-radius: 8px; font-size: 0.88rem;">
                            <div class="form-text text-muted" style="font-size: 0.72rem;">Kelas binaan terdaftar.</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jk" class="form-select" required style="border-radius: 8px; font-size: 0.88rem;">
                                <option value="" disabled {{ empty($user->jk) ? 'selected' : '' }}>-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jk', $user->jk) === 'L' ? 'selected' : '' }}>L (Laki-laki)</option>
                                <option value="P" {{ old('jk', $user->jk) === 'P' ? 'selected' : '' }}>P (Perempuan)</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">NIK (Nomor Induk Kependudukan / KTP / KK)</label>
                            <input type="text" name="nik" class="form-control font-monospace" value="{{ old('nik', $user->nik) }}" placeholder="Contoh: 1871150706090001" maxlength="20" style="border-radius: 8px; font-size: 0.88rem;">
                            <div class="form-text text-muted" style="font-size: 0.72rem;">16 digit NIK pada KTP atau Kartu Keluarga.</div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">NIPD (Nomor Induk Peserta Didik)</label>
                            <input type="text" name="nipd" class="form-control font-monospace" value="{{ old('nipd', $user->nipd) }}" placeholder="Contoh: 15667" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" placeholder="Contoh: Bandar Lampung" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                    </div>
                </div>

                <hr style="border-color: #E2E8F0;">

                {{-- Section 2: Kontak & Domisili --}}
                <div class="mb-4">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                        2. Kontak & Alamat Tempat Tinggal
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">
                                <i class="bi bi-whatsapp text-success me-1"></i>No HP / WhatsApp Aktif
                            </label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 085788078487" style="border-radius: 8px; font-size: 0.88rem;">
                            <div class="form-text text-muted" style="font-size: 0.72rem;">Pastikan nomor aktif untuk keperluan informasi kelulusan & BK.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Email Siswa</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->email }}" readonly disabled style="border-radius: 8px; font-size: 0.88rem;">
                            <div class="form-text text-muted" style="font-size: 0.72rem;">Email login bawaan sekolah.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">Alamat Tempat Tinggal (Jalan / Gang / No. Rumah)</label>
                            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $user->alamat) }}" placeholder="Contoh: Jl. Perintis Kemerdekaan LK I No. 25" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">RT</label>
                            <input type="text" name="rt" class="form-control" value="{{ old('rt', $user->rt) }}" placeholder="Contoh: 06" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">RW</label>
                            <input type="text" name="rw" class="form-control" value="{{ old('rw', $user->rw) }}" placeholder="Contoh: 01" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">Dusun / Lingkungan</label>
                            <input type="text" name="dusun" class="form-control" value="{{ old('dusun', $user->dusun) }}" placeholder="Nama Dusun / LK" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small text-dark mb-1">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $user->kelurahan) }}" placeholder="Contoh: Tanjung Raya" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $user->kecamatan) }}" placeholder="Contoh: Kec. Kedamaian" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota', $user->kabupaten_kota ?: 'Kota Bandar Lampung') }}" placeholder="Contoh: Kota Bandar Lampung" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small text-dark mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control font-monospace" value="{{ old('kode_pos', $user->kode_pos) }}" placeholder="Contoh: 35127" style="border-radius: 8px; font-size: 0.88rem;">
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div class="small" style="color: #64748B;">
                        @if(!$user->is_biodata_confirmed)
                            <span class="text-warning fw-semibold"><i class="bi bi-shield-lock-fill me-1"></i> Klik simpan konfirmasi di samping untuk membuka modul berikutnya.</span>
                        @else
                            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Data terkonfirmasi dan tersinkronisasi ke laporan sekolah.</span>
                        @endif
                    </div>
                    <button type="submit" class="btn {{ $user->is_biodata_confirmed ? 'btn-primary' : 'btn-warning' }} px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; font-size: 0.92rem; {{ !$user->is_biodata_confirmed ? 'background-color: #F59E0B !important; border-color: #F59E0B !important; color: #1E293B !important;' : 'background-color: #2563eb; border-color: #2563eb;' }} box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                        @if(!$user->is_biodata_confirmed)
                            <i class="bi bi-shield-check fs-5"></i> Simpan & Konfirmasi Biodata Diri
                        @else
                            <i class="bi bi-check2-circle fs-5"></i> Simpan & Perbarui Biodata
                        @endif
                    </button>
                </div>
            </form>
        </div>

        {{-- Status Tes & Rencana Kelulusan Cards --}}
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="card-pro p-3 h-100" style="background:#ffffff; border:1px solid #E2E8F0; border-radius:12px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span style="font-size:0.75rem; font-weight:700; color:#64748B; text-transform:uppercase;">Status Tes Minat RIASEC</span>
                        @if($careerResult)
                            <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="font-size:0.75rem;">Selesai</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1" style="font-size:0.75rem;">Belum Tes</span>
                        @endif
                    </div>
                    @if($careerResult)
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge font-monospace bg-primary text-white px-2 py-1" style="font-size:0.85rem;">{{ $careerResult->holland_code }}</span>
                            <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $careerResult->dominant_type }}</span>
                        </div>
                        <a href="{{ route('tes.hasil') }}" class="btn btn-outline-primary btn-sm mt-2" style="border-radius:6px; font-size:0.78rem;">
                            <i class="bi bi-bar-chart-fill me-1"></i> Lihat Hasil Tes
                        </a>
                    @else
                        <p style="font-size:0.82rem; color:#64748B; margin:4px 0 8px 0;">Ikuti tes 72 pertanyaan minat karier dan studi.</p>
                        @if($user->is_biodata_confirmed)
                            <a href="{{ route('tes.mulai') }}" class="btn btn-primary btn-sm" style="border-radius:6px; font-size:0.78rem;">
                                <i class="bi bi-lightning-charge me-1"></i> Mulai Tes
                            </a>
                        @else
                            <button type="button" onclick="window.scrollTo({top: 150, behavior:'smooth'})" class="btn btn-warning btn-sm fw-bold" style="border-radius:6px; font-size:0.78rem;">
                                <i class="bi bi-lock-fill me-1"></i> Konfirmasi Biodata Dulu
                            </button>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card-pro p-3 h-100" style="background:#ffffff; border:1px solid #E2E8F0; border-radius:12px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span style="font-size:0.75rem; font-weight:700; color:#64748B; text-transform:uppercase;">Status Rencana Kelulusan</span>
                        @if($pilihan)
                            <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="font-size:0.75rem;">Selesai ({{ ucfirst($pilihan->rencana) }})</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1" style="font-size:0.75rem;">Belum Memilih</span>
                        @endif
                    </div>
                    @if($pilihan)
                        <div class="fw-bold text-dark" style="font-size:0.9rem;">
                            @if($pilihan->rencana === 'kuliah')
                                {{ $pilihan->nama_perguruan_tinggi }} - {{ $pilihan->nama_program_studi }}
                            @elseif($pilihan->rencana === 'bekerja')
                                {{ $pilihan->bidang_pekerjaan }} ({{ $pilihan->keterangan_pekerjaan ?: 'Industri' }})
                            @else
                                {{ $pilihan->bidang_usaha }} ({{ $pilihan->keterangan_usaha ?: 'Wirausaha' }})
                            @endif
                        </div>
                        <a href="{{ route('siswa.pilihan-saya') }}" class="btn btn-outline-primary btn-sm mt-2" style="border-radius:6px; font-size:0.78rem;">
                            <i class="bi bi-file-earmark-person me-1"></i> Bukti Pilihan Digital
                        </a>
                    @else
                        <p style="font-size:0.82rem; color:#64748B; margin:4px 0 8px 0;">Tentukan rencana kuliah, bekerja, atau berwirausaha setelah tes selesai.</p>
                        @if(!$user->is_biodata_confirmed)
                            <button type="button" onclick="window.scrollTo({top: 150, behavior:'smooth'})" class="btn btn-warning btn-sm fw-bold" style="border-radius:6px; font-size:0.78rem;">
                                <i class="bi bi-lock-fill me-1"></i> Konfirmasi Biodata Dulu
                            </button>
                        @elseif(!$careerResult)
                            <a href="{{ route('tes.index') }}" class="btn btn-secondary btn-sm" style="border-radius:6px; font-size:0.78rem;">
                                <i class="bi bi-lock-fill me-1"></i> Selesaikan Tes Minat Dulu
                            </a>
                        @else
                            <a href="{{ route('siswa.rencana') }}" class="btn btn-primary btn-sm" style="border-radius:6px; font-size:0.78rem;">
                                <i class="bi bi-compass me-1"></i> Pilih Rencana
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
