@extends('layouts.app')

@section('title', 'Masuk ke Portal Siswa')

@section('content')
<div class="row justify-content-center align-items-center py-2 py-md-4">
    <div class="col-12 col-lg-10 col-xl-9">
        <div class="card-pro shadow-elevated" style="border-radius: var(--radius-xl); overflow: hidden;">
            <div class="row g-0">
                {{-- LEFT SIDE: Hero Visual / Branding (Visible on Desktop / Tablet) --}}
                <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5"
                     style="background: linear-gradient(145deg, #0b1329 0%, #172554 100%); color: #ffffff;">
                    <div>
                        {{-- School Header --}}
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #2563eb; display:flex; align-items:center; justify-content:center; font-size:1.35rem; color:#fff; box-shadow: 0 4px 12px rgba(37,99,235,0.4);">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.88rem; font-weight: 800; line-height: 1.2;">
                                    {{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 1 Bandar Lampung') }}
                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">Portal Rencana Siswa Kelas 12</div>
                            </div>
                        </div>

                        {{-- Main Catchphrase --}}
                        <div class="mt-4 mb-4">
                            <h2 style="font-size: 1.75rem; font-weight: 800; line-height: 1.25; letter-spacing: -0.02em;">
                                Rencanakan Masa Depanmu Hari Ini.
                            </h2>
                            <p style="font-size: 0.88rem; color: #cbd5e1; margin-top: 12px; line-height: 1.6;">
                                Tentukan jalur langkahmu setelah lulus: melanjutkan <strong>Kuliah</strong> di kampus impian, langsung <strong>Bekerja</strong> di industri, atau <strong>Berwirausaha</strong> mandiri.
                            </p>
                        </div>

                        {{-- Highlights --}}
                        <div class="d-flex flex-column gap-3 mt-4">
                            <div class="d-flex align-items-start gap-3">
                                <div style="width:28px; height:28px; border-radius:8px; background:rgba(37,99,235,0.2); color:#60a5fa; display:flex; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0;">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <div style="font-size: 0.82rem; color: #e2e8f0;">
                                    <strong>Terhubung API KIP Kuliah</strong>: Akses ribuan kampus & program studi resmi.
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3">
                                <div style="width:28px; height:28px; border-radius:8px; background:rgba(16,185,129,0.2); color:#34d399; display:flex; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0;">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <div style="font-size: 0.82rem; color: #e2e8f0;">
                                    <strong>Login Mudah dengan NISN</strong>: Cukup gunakan NISN untuk siswa kelas 12.
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3">
                                <div style="width:28px; height:28px; border-radius:8px; background:rgba(245,158,11,0.2); color:#fbbf24; display:flex; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0;">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <div style="font-size: 0.82rem; color: #e2e8f0;">
                                    <strong>Resmi & Terdata</strong>: Bukti digital pilihan tersimpan langsung di sistem sekolah.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 0.75rem; color: #64748b; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px;">
                        Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2024/2025') }} • Bimbingan Konseling & Karier
                    </div>
                </div>

                {{-- RIGHT SIDE: Login Form (Mobile First) --}}
                <div class="col-12 col-lg-7 p-4 p-sm-5 bg-white d-flex flex-column justify-content-center">
                    {{-- Mobile Branding Header --}}
                    <div class="d-lg-none text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center mb-2"
                             style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color:#fff; font-size:1.4rem; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div style="font-size: 0.88rem; font-weight: 800; color: #0f172a;">
                            {{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 1 Bandar Lampung') }}
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b;">
                            {{ \App\Models\Setting::get('nama_aplikasi', 'Portal Rencana Setelah Lulus') }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">
                            Masuk ke Akun
                        </h1>
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">
                            Masukkan NISN (untuk Siswa) atau Email (untuk Admin)
                        </p>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                        @csrf

                        {{-- Field: NISN / Email --}}
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label d-flex align-items-center justify-content-between"
                                   style="font-size: 0.82rem; font-weight: 700; color: #334155;">
                                <span><i class="bi bi-person-badge me-1 text-primary"></i> NISN atau Email</span>
                                <span class="text-muted fw-normal" style="font-size: 0.72rem;">Siswa gunakan NISN</span>
                            </label>
                            <div class="position-relative">
                                <input type="text"
                                       class="form-control-pro @error('email') is-invalid @enderror"
                                       id="inputEmail"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Contoh: 3094378011 atau email..."
                                       autocomplete="username"
                                       required
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger mt-1" style="font-size: 0.78rem; font-weight: 600;">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Field: Password with Toggle Eye --}}
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label d-flex align-items-center justify-content-between"
                                   style="font-size: 0.82rem; font-weight: 700; color: #334155;">
                                <span><i class="bi bi-lock me-1 text-primary"></i> Kata Sandi</span>
                                <span class="text-muted fw-normal" style="font-size: 0.72rem;">Default: password</span>
                            </label>
                            <div class="position-relative">
                                <input type="password"
                                       class="form-control-pro"
                                       id="inputPassword"
                                       name="password"
                                       placeholder="••••••••"
                                       autocomplete="current-password"
                                       style="padding-right: 48px;"
                                       required>
                                <button type="button"
                                        id="togglePasswordBtn"
                                        class="btn position-absolute top-50 end-0 translate-middle-y border-0 text-muted p-2 me-2"
                                        style="font-size: 1.1rem; line-height: 1;"
                                        title="Tampilkan / Sembunyikan Password">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" style="cursor: pointer;">
                                <label class="form-check-label" for="remember" style="font-size: 0.82rem; color: #475569; cursor: pointer;">
                                    Ingat saya di perangkat ini
                                </label>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn-brand-primary w-100 py-3" id="btnLogin">
                            <span>Masuk ke Portal</span>
                            <i class="bi bi-arrow-right fs-6"></i>
                        </button>
                    </form>

                    {{-- Help & Contact --}}
                    <div class="p-3 mt-4" style="background: #f8fafc; border-radius: var(--radius-md); border: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle text-primary mt-1" style="font-size: 0.95rem;"></i>
                            <div style="font-size: 0.76rem; color: #64748b; line-height: 1.45;">
                                Siswa kelas 12 dapat login langsung menggunakan <strong>NISN</strong> masing-masing. Jika kamu lupa NISN atau mengalami kendala, hubungi Guru BK / Wali Kelasmu.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle Password Visibility
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('inputPassword');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }
</script>
@endpush
