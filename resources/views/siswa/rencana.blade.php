@extends('layouts.app')

@section('title', 'Pilih Rencana Setelah Lulus')

@section('content')
{{-- Step Indicator --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('siswa.dashboard') }}" class="btn-brand-outline p-2 d-inline-flex" style="min-height:36px; width:36px; border-radius:50%;" title="Kembali ke Beranda">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <div style="font-size:0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.04em;">
                Langkah 1 dari 2
            </div>
            <h1 style="font-size:1.35rem; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.02em;">
                Pilih Arah Rencanamu
            </h1>
        </div>
    </div>
    <div class="d-none d-sm-block">
        <span class="badge bg-light text-secondary border px-3 py-2" style="font-size:0.78rem; border-radius:var(--radius-full);">
            Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2024/2025') }}
        </span>
    </div>
</div>

{{-- School Announcement Banner if any --}}
@if(!empty($pengumuman))
    <div class="alert alert-info alert-custom mb-4 border-0 shadow-sm" role="alert" style="background:#eff6ff; border-left:4px solid #3b82f6 !important; border-radius:var(--radius-md);">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-info-circle-fill text-primary mt-1 fs-5"></i>
            <div>
                <strong class="d-block" style="font-size:0.84rem; color:#1e40af;">Pengumuman Bimbingan Karier:</strong>
                <div style="font-size:0.82rem; color:#1e3a8a; line-height:1.45;">{{ $pengumuman }}</div>
            </div>
        </div>
    </div>
@endif

<form id="formRencana" method="POST" action="{{ route('siswa.rencana.simpan') }}">
    @csrf

    {{-- 3 Main Pathway Cards --}}
    <div class="row g-3 mb-4">
        {{-- 1. Kuliah --}}
        <div class="col-12 col-md-4">
            <div class="choice-card-pro" id="cardKuliah" onclick="pilihRencana('kuliah')">
                <div class="check-indicator"><i class="bi bi-check-lg"></i></div>
                <div class="choice-card-icon" style="background:#dbeafe; color:#2563eb;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="choice-card-title">Kuliah</div>
                <div class="choice-card-desc">Melanjutkan pendidikan tinggi di PTN / PTS impian</div>
                <span class="choice-card-pill" style="background:#eff6ff; color:#1d4ed8;">
                    <i class="bi bi-database-check me-1"></i> Data KIP Kuliah
                </span>
            </div>
        </div>

        {{-- 2. Bekerja --}}
        <div class="col-12 col-md-4">
            <div class="choice-card-pro" id="cardBekerja" onclick="pilihRencana('bekerja')">
                <div class="check-indicator"><i class="bi bi-check-lg"></i></div>
                <div class="choice-card-icon" style="background:#fef3c7; color:#d97706;">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <div class="choice-card-title">Bekerja</div>
                <div class="choice-card-desc">Langsung meniti karier di dunia industri / perusahaan</div>
                <span class="choice-card-pill" style="background:#fffbeb; color:#b45309;">
                    <i class="bi bi-building-check me-1"></i> Dunia Industri
                </span>
            </div>
        </div>

        {{-- 3. Berwirausaha --}}
        <div class="col-12 col-md-4">
            <div class="choice-card-pro" id="cardBerwirausaha" onclick="pilihRencana('berwirausaha')">
                <div class="check-indicator"><i class="bi bi-check-lg"></i></div>
                <div class="choice-card-icon" style="background:#ede9fe; color:#7c3aed;">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="choice-card-title">Berwirausaha</div>
                <div class="choice-card-desc">Membangun rintisan bisnis atau usaha mandiri kreatif</div>
                <span class="choice-card-pill" style="background:#f5f3ff; color:#6d28d9;">
                    <i class="bi bi-lightning-charge me-1"></i> Bisnis Mandiri
                </span>
            </div>
        </div>
    </div>

    <input type="hidden" name="rencana" id="inputRencana" value="">

    {{-- DETAILED KULIAH FORM (Visible when Kuliah is active) --}}
    <div id="formKuliah" style="display:none;" class="mb-4">
        <div class="card-pro p-4 p-sm-5 shadow-card">
            <div class="d-flex align-items-center gap-2 pb-3 mb-4" style="border-bottom:1px solid #f1f5f9;">
                <div style="width:36px; height:36px; border-radius:10px; background:#dbeafe; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1.15rem;">
                    <i class="bi bi-search"></i>
                </div>
                <div>
                    <h2 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0;">
                        Cari Perguruan Tinggi & Program Studi
                    </h2>
                    <p style="font-size:0.78rem; color:#64748b; margin:2px 0 0;">
                        Data resmi sinkron dengan pangkalan data KIP Kuliah Kemdiktisaintek
                    </p>
                </div>
            </div>

            {{-- 1. Input Pencarian Kampus --}}
            <div class="mb-4">
                <label for="searchKampus" class="form-label" style="font-weight:700; font-size:0.84rem; color:#334155;">
                    1. Nama Perguruan Tinggi / Kampus
                </label>
                
                <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                        <i class="bi bi-search fs-6"></i>
                    </span>
                    <input type="text"
                           class="form-control-pro ps-5 pe-5"
                           id="searchKampus"
                           placeholder="Ketik nama kampus atau singkatan (contoh: Lampung, UNILA, ITB, UI, UGM)..."
                           autocomplete="off">
                    
                    {{-- Spinner Loading --}}
                    <div id="searchSpinner" class="position-absolute top-50 end-0 translate-middle-y pe-3" style="display:none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    </div>

                    {{-- Clear button --}}
                    <button type="button"
                            id="clearSearchBtn"
                            class="position-absolute top-50 end-0 translate-middle-y pe-3 btn border-0 text-muted p-0"
                            style="display:none; font-size:1.1rem;"
                            title="Hapus pencarian">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                {{-- Quick Suggestions / Chips --}}
                <div class="mt-2" id="quickChipsContainer">
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <span style="font-size:0.72rem; color:#94a3b8; font-weight:600; margin-right:4px;">Saran cepat:</span>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('UNILA')">🏛️ UNILA</button>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('ITB')">🏛️ ITB</button>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('UGM')">🏛️ UGM</button>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('UI')">🏛️ UI</button>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('POLINELA')">🏛️ POLINELA</button>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('Bandung')">📍 Bandung</button>
                        <button type="button" class="btn-quick-chip" onclick="triggerSearch('Yogyakarta')">📍 Yogyakarta</button>
                    </div>
                </div>

                {{-- Search Results Dropdown List --}}
                <div id="searchResults" class="search-results-pro shadow-elevated" style="display:none;"></div>

                {{-- Selected Kampus Card Display --}}
                <div id="selectedKampus" style="display:none;" class="mt-3">
                    <div class="p-3 d-flex align-items-center justify-content-between" style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:var(--radius-md);">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px; height:40px; border-radius:10px; background:#2563eb; color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <div style="font-size:0.72rem; font-weight:700; color:#3b82f6; text-transform:uppercase;">Kampus Terpilih</div>
                                <div id="selectedKampusNama" style="font-size:1.05rem; font-weight:800; color:#1e3a8a; line-height:1.2;"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger px-3 py-1" onclick="resetKampus()" style="border-radius:var(--radius-sm); font-size:0.78rem; font-weight:600;">
                            <i class="bi bi-arrow-repeat me-1"></i> Ganti
                        </button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="perguruan_tinggi_id" id="inputPtId">
            <input type="hidden" name="nama_perguruan_tinggi" id="inputPtNama">

            {{-- 2. Pilih Program Studi --}}
            <div id="prodiSection" style="display:none;" class="pt-2">
                <label for="selectProdi" class="form-label" style="font-weight:700; font-size:0.84rem; color:#334155;">
                    2. Program Studi (Jurusan)
                </label>

                <div id="prodiSpinner" style="display:none;" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                    <div style="font-size:0.82rem; color:#64748b;">Menghubungkan ke API KIP Kuliah untuk memuat jurusan...</div>
                </div>

                <select class="form-select form-control-pro" id="selectProdi" onchange="pilihProdi()" style="display:none; height:50px;">
                    <option value="">-- Pilih Program Studi --</option>
                </select>

                <input type="hidden" name="program_studi_id" id="inputProdiId">
                <input type="hidden" name="nama_program_studi" id="inputProdiNama">
                <input type="hidden" name="jenjang" id="inputJenjang">
                <input type="hidden" name="akreditasi" id="inputAkreditasi">

                {{-- Detail Prodi Selected --}}
                <div id="prodiDetail" style="display:none;" class="mt-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div style="font-size:0.72rem; font-weight:600; color:#64748b; text-transform:uppercase;">Jenjang Pendidikan</div>
                                <div id="detailJenjang" style="font-weight:800; color:#0f172a; font-size:0.95rem; margin-top:2px;"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div style="font-size:0.72rem; font-weight:600; color:#64748b; text-transform:uppercase;">Akreditasi Prodi</div>
                                <div id="detailAkreditasi" style="font-weight:800; color:#2563eb; font-size:0.95rem; margin-top:2px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM BEKERJA (PRD SECTION 15) --}}
    <div id="formBekerja" style="display:none;" class="card-pro p-4 p-sm-5 mb-4" style="background:#fffbeb; border-color:#fde68a;">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid #fef3c7;">
            <div style="width:48px; height:48px; border-radius:14px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div>
                <h3 style="font-size:1.15rem; font-weight:800; color:#78350f; margin:0;">
                    Detail Rencana Bekerja
                </h3>
                <p style="font-size:0.8rem; color:#92400e; margin:2px 0 0;">
                    Tentukan bidang pekerjaan industri yang kamu tuju setelah lulus
                </p>
            </div>
        </div>

        <div class="mb-4">
            <label for="bidangPekerjaan" class="form-label fw-bold" style="font-size:0.86rem; color:#451a03;">
                Bidang Pekerjaan <span class="text-danger">*</span>
            </label>
            <select name="bidang_pekerjaan" id="bidangPekerjaan" class="form-select form-control-pro" onchange="updateSubmitState()">
                <option value="">-- Pilih Bidang Pekerjaan --</option>
                <option value="Teknologi Informasi">Teknologi Informasi & Komunikasi</option>
                <option value="Administrasi">Administrasi, Tata Usaha & Kearsipan</option>
                <option value="Teknik & Manufaktur">Teknik, Otomotif, Mekanikal & Kelistrikan</option>
                <option value="Pendidikan">Pendidikan & Pengajaran</option>
                <option value="Kesehatan">Kesehatan, Medis & Farmasi</option>
                <option value="Marketing">Pemasaran, Sales & Public Relations</option>
                <option value="Keuangan">Keuangan, Akuntansi & Perbankan</option>
                <option value="Industri & Konstruksi">Industri Manufaktur & Konstruksi Fisik</option>
                <option value="Lainnya">Bidang Lainnya</option>
            </select>
        </div>

        <div>
            <label for="keteranganPekerjaan" class="form-label fw-bold" style="font-size:0.86rem; color:#451a03;">
                Keterangan Pekerjaan yang Diminati (Opsional)
            </label>
            <input type="text"
                   name="keterangan_pekerjaan"
                   id="keteranganPekerjaan"
                   class="form-control form-control-pro"
                   placeholder="Contoh: Ingin menjadi teknisi jaringan atau staff administrasi logistik">
            <div class="form-text" style="font-size:0.75rem; color:#92400e;">
                Tuliskan posisi, profesi, atau jenis industri yang kamu impikan.
            </div>
        </div>
    </div>

    {{-- FORM BERWIRAUSAHA (PRD SECTION 16) --}}
    <div id="formBerwirausaha" style="display:none;" class="card-pro p-4 p-sm-5 mb-4" style="background:#f5f3ff; border-color:#ddd6fe;">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid #ede9fe;">
            <div style="width:48px; height:48px; border-radius:14px; background:#ede9fe; color:#7c3aed; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <h3 style="font-size:1.15rem; font-weight:800; color:#4c1d95; margin:0;">
                    Detail Rencana Berwirausaha
                </h3>
                <p style="font-size:0.8rem; color:#6d28d9; margin:2px 0 0;">
                    Tentukan bidang usaha bisnis mandiri yang ingin kamu bangun
                </p>
            </div>
        </div>

        <div class="mb-4">
            <label for="bidangUsaha" class="form-label fw-bold" style="font-size:0.86rem; color:#2e1065;">
                Bidang Usaha <span class="text-danger">*</span>
            </label>
            <select name="bidang_usaha" id="bidangUsaha" class="form-select form-control-pro" onchange="updateSubmitState()">
                <option value="">-- Pilih Bidang Usaha --</option>
                <option value="Kuliner">Kuliner (Makanan, Minuman, Kafe & Katering)</option>
                <option value="Fashion">Fashion, Pakaian & Busana Kreatif</option>
                <option value="Teknologi">Teknologi, Jasa Software, Web & Gadget</option>
                <option value="Jasa">Jasa & Layanan (Servis, Laundry, Ekspedisi, dll.)</option>
                <option value="Perdagangan">Perdagangan Retail & Toko Online (E-Commerce)</option>
                <option value="Kreatif">Industri Kreatif, Studio Desain, Foto & Video</option>
                <option value="Pertanian">Pertanian Modern, Hidroponik & Perkebunan</option>
                <option value="Peternakan">Peternakan & Perikanan</option>
                <option value="Lainnya">Bidang Usaha Lainnya</option>
            </select>
        </div>

        <div>
            <label for="keteranganUsaha" class="form-label fw-bold" style="font-size:0.86rem; color:#2e1065;">
                Jenis Usaha yang Diminati (Opsional)
            </label>
            <input type="text"
                   name="keterangan_usaha"
                   id="keteranganUsaha"
                   class="form-control form-control-pro"
                   placeholder="Contoh: Membuka kedai kopi kekinian dan toko apparel custom">
            <div class="form-text" style="font-size:0.75rem; color:#6d28d9;">
                Tuliskan gambaran produk, layanan, atau konsep usaha rintisanmu.
            </div>
        </div>
    </div>

    {{-- DESKTOP SUBMIT BUTTON CONTAINER --}}
    <div id="desktopSubmitSection" style="display:none;" class="text-center mt-4">
        <button type="submit" class="btn-brand-primary px-5 py-3 fs-6" id="btnSubmitDesktop">
            <span>Lanjutkan ke Konfirmasi</span>
            <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>

    {{-- MOBILE STICKY FLOATING BOTTOM BAR --}}
    <div id="mobileStickyBar" class="mobile-sticky-action-bar" style="display:none;">
        <div class="container d-flex align-items-center justify-content-between gap-2">
            <div style="font-size:0.75rem; color:#64748b; line-height:1.2;">
                <div>Langkah 1/2</div>
                <strong id="stickyPlanLabel" class="text-dark">Pilih Rencana</strong>
            </div>
            <button type="submit" class="btn-brand-primary py-2 px-4" id="btnSubmitMobile" style="min-height:42px; font-size:0.88rem;">
                <span>Lanjut</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    /* PRO CHOICE CARDS */
    .choice-card-pro {
        background: #ffffff;
        border: 2px solid var(--border-subtle);
        border-radius: var(--radius-lg);
        padding: 24px 20px;
        cursor: pointer;
        position: relative;
        text-align: center;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
        box-shadow: var(--shadow-subtle);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .choice-card-pro:hover {
        border-color: #93c5fd;
        transform: translateY(-2px);
        box-shadow: var(--shadow-card);
    }

    .choice-card-pro:active {
        transform: scale(0.98);
    }

    .choice-card-pro.selected {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .choice-card-pro .check-indicator {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .choice-card-pro.selected .check-indicator {
        display: flex;
    }

    .choice-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 14px;
        transition: transform 0.2s;
    }

    .choice-card-pro:hover .choice-card-icon {
        transform: scale(1.08);
    }

    .choice-card-title {
        font-weight: 800;
        font-size: 1.15rem;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .choice-card-desc {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.45;
        margin-bottom: 14px;
        flex-grow: 1;
    }

    .choice-card-pill {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: var(--radius-full);
    }

    /* QUICK CHIP BUTTONS */
    .btn-quick-chip {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.74rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: var(--radius-full);
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-quick-chip:hover {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .btn-quick-chip:active {
        transform: scale(0.95);
    }

    /* SEARCH RESULTS DROPDOWN */
    .search-results-pro {
        max-height: 280px;
        overflow-y: auto;
        background: #ffffff;
        border: 1.5px solid var(--border-subtle);
        border-radius: var(--radius-md);
        margin-top: 6px;
        box-shadow: var(--shadow-elevated);
    }

    .search-result-item-pro {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color 0.15s;
    }

    .search-result-item-pro:last-child {
        border-bottom: none;
    }

    .search-result-item-pro:hover {
        background-color: #eff6ff;
    }

    .search-result-item-pro:active {
        background-color: #dbeafe;
    }

    /* STICKY BOTTOM ACTION BAR ON MOBILE */
    .mobile-sticky-action-bar {
        position: fixed;
        bottom: 68px; /* above the mobile bottom nav */
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-top: 1px solid var(--border-subtle);
        padding: 10px 0;
        z-index: 1040;
        box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06);
    }

    @media (min-width: 768px) {
        .mobile-sticky-action-bar {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchTimeout = null;
    let prodiData = [];

    // === Pilih Rencana Handler ===
    function pilihRencana(rencana) {
        document.getElementById('inputRencana').value = rencana;

        // Visual selection
        document.querySelectorAll('.choice-card-pro').forEach(card => card.classList.remove('selected'));
        if (rencana === 'kuliah') document.getElementById('cardKuliah').classList.add('selected');
        if (rencana === 'bekerja') document.getElementById('cardBekerja').classList.add('selected');
        if (rencana === 'berwirausaha') document.getElementById('cardBerwirausaha').classList.add('selected');

        // Form visibility
        document.getElementById('formKuliah').style.display = rencana === 'kuliah' ? 'block' : 'none';
        document.getElementById('formBekerja').style.display = rencana === 'bekerja' ? 'block' : 'none';
        document.getElementById('formBerwirausaha').style.display = rencana === 'berwirausaha' ? 'block' : 'none';

        // Sticky label
        const labelMap = { 'kuliah': 'Jalur Kuliah', 'bekerja': 'Jalur Bekerja', 'berwirausaha': 'Jalur Wirausaha' };
        document.getElementById('stickyPlanLabel').textContent = labelMap[rencana] || 'Pilih Rencana';

        // Show submit buttons
        document.getElementById('desktopSubmitSection').style.display = 'block';
        document.getElementById('mobileStickyBar').style.display = 'block';

        updateSubmitState();

        // Scroll into view on mobile if kuliah chosen
        if (rencana === 'kuliah') {
            setTimeout(() => {
                document.getElementById('formKuliah').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }

    // === Quick Chip Trigger ===
    function triggerSearch(keyword) {
        const input = document.getElementById('searchKampus');
        input.value = keyword;
        input.dispatchEvent(new Event('input'));
        input.focus();
    }

    // === Search Kampus Handler ===
    const searchInput = document.getElementById('searchKampus');
    const clearBtn = document.getElementById('clearSearchBtn');
    const spinner = document.getElementById('searchSpinner');

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const keyword = this.value.trim();

        clearBtn.style.display = keyword.length > 0 ? 'block' : 'none';

        if (keyword.length < 2) {
            document.getElementById('searchResults').style.display = 'none';
            return;
        }

        spinner.style.display = 'block';

        searchTimeout = setTimeout(() => {
            fetch('{{ route("siswa.cari-kampus") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ keyword: keyword })
            })
            .then(res => res.json())
            .then(data => {
                spinner.style.display = 'none';
                renderSearchResults(data);
            })
            .catch(err => {
                spinner.style.display = 'none';
                console.error('Search error:', err);
            });
        }, 350);
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    });

    function renderSearchResults(data) {
        const container = document.getElementById('searchResults');

        if (!data || data.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 px-3" style="color:#64748b; font-size:0.84rem;">
                    <i class="bi bi-search fs-5 d-block mb-1 text-muted"></i>
                    Tidak menemukan nama kampus yang cocok. Coba ketik singkatan lain (contoh: <code>UNILA</code>, <code>ITB</code>, dll).
                </div>`;
            container.style.display = 'block';
            return;
        }

        let html = '';
        data.forEach(item => {
            html += `
                <div class="search-result-item-pro" onclick="pilihKampus('${item.id.replace(/'/g, "\\'")}', '${item.nama.replace(/'/g, "\\'")}')">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:30px; height:30px; border-radius:6px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:0.9rem;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div style="font-weight:700; font-size:0.88rem; color:#0f172a;">${item.nama}</div>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:0.8rem;"></i>
                </div>`;
        });

        container.innerHTML = html;
        container.style.display = 'block';
    }

    // === Pilih Kampus ===
    function pilihKampus(id, nama) {
        document.getElementById('inputPtId').value = id;
        document.getElementById('inputPtNama').value = nama;
        document.getElementById('selectedKampusNama').textContent = nama;

        // UI states
        document.getElementById('searchKampus').style.display = 'none';
        document.getElementById('clearSearchBtn').style.display = 'none';
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('quickChipsContainer').style.display = 'none';
        document.getElementById('selectedKampus').style.display = 'block';

        // Load prodi
        loadProdi(nama);
    }

    function resetKampus() {
        document.getElementById('inputPtId').value = '';
        document.getElementById('inputPtNama').value = '';
        document.getElementById('searchKampus').value = '';
        document.getElementById('searchKampus').style.display = 'block';
        document.getElementById('clearSearchBtn').style.display = 'none';
        document.getElementById('selectedKampus').style.display = 'none';
        document.getElementById('quickChipsContainer').style.display = 'block';
        document.getElementById('prodiSection').style.display = 'none';
        document.getElementById('prodiDetail').style.display = 'none';
        document.getElementById('selectProdi').style.display = 'none';

        // Clear prodi
        document.getElementById('inputProdiId').value = '';
        document.getElementById('inputProdiNama').value = '';
        document.getElementById('inputJenjang').value = '';
        document.getElementById('inputAkreditasi').value = '';
        prodiData = [];

        updateSubmitState();
        document.getElementById('searchKampus').focus();
    }

    // === Load Prodi ===
    function loadProdi(ptIdOrName) {
        document.getElementById('prodiSection').style.display = 'block';
        document.getElementById('prodiSpinner').style.display = 'block';
        document.getElementById('selectProdi').style.display = 'none';

        fetch('{{ route("siswa.cari-prodi") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ pt_id: ptIdOrName })
        })
        .then(res => res.json())
        .then(data => {
            prodiData = data;
            document.getElementById('prodiSpinner').style.display = 'none';

            const select = document.getElementById('selectProdi');
            select.innerHTML = '<option value="">-- Pilih Program Studi (' + data.length + ' Jurusan Tersedia) --</option>';

            data.forEach((item, index) => {
                const option = document.createElement('option');
                option.value = index;
                const akred = item.akreditasi ? `[${item.akreditasi}]` : '';
                option.textContent = `${item.nama} (${item.jenjang}) ${akred}`;
                select.appendChild(option);
            });

            select.style.display = 'block';
        })
        .catch(err => {
            document.getElementById('prodiSpinner').style.display = 'none';
            console.error('Prodi load error:', err);
        });
    }

    // === Pilih Prodi ===
    function pilihProdi() {
        const select = document.getElementById('selectProdi');
        const index = select.value;

        if (index === '') {
            document.getElementById('prodiDetail').style.display = 'none';
            document.getElementById('inputProdiId').value = '';
            document.getElementById('inputProdiNama').value = '';
            document.getElementById('inputJenjang').value = '';
            document.getElementById('inputAkreditasi').value = '';
            updateSubmitState();
            return;
        }

        const prodi = prodiData[index];
        document.getElementById('inputProdiId').value = prodi.id;
        document.getElementById('inputProdiNama').value = prodi.nama;
        document.getElementById('inputJenjang').value = prodi.jenjang;
        document.getElementById('inputAkreditasi').value = prodi.akreditasi;

        document.getElementById('detailJenjang').textContent = prodi.jenjang || '-';
        document.getElementById('detailAkreditasi').textContent = (prodi.akreditasi || '-') + ' ⭐';
        document.getElementById('prodiDetail').style.display = 'block';

        updateSubmitState();
    }

    // === Update Submit State ===
    function updateSubmitState() {
        const rencana = document.getElementById('inputRencana').value;
        const btnDesktop = document.getElementById('btnSubmitDesktop');
        const btnMobile = document.getElementById('btnSubmitMobile');

        let isValid = false;
        if (rencana === 'bekerja') {
            const bidang = document.getElementById('bidangPekerjaan').value;
            isValid = Boolean(bidang);
        } else if (rencana === 'berwirausaha') {
            const bidang = document.getElementById('bidangUsaha').value;
            isValid = Boolean(bidang);
        } else if (rencana === 'kuliah') {
            const ptId = document.getElementById('inputPtId').value;
            const prodiId = document.getElementById('inputProdiId').value;
            isValid = Boolean(ptId && prodiId);
        }

        btnDesktop.disabled = !isValid;
        btnMobile.disabled = !isValid;
    }

    // === Auto Select via URL Query Param (misal dari halaman rekomendasi: ?rencana=kuliah) ===
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const planParam = urlParams.get('rencana');
        if (planParam && ['kuliah', 'bekerja', 'berwirausaha'].includes(planParam)) {
            pilihRencana(planParam);
        }
    });
</script>
@endpush
