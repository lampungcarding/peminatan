@extends('layouts.app')

@section('title', 'Konfirmasi Pilihan Rencana')

@section('content')
<div class="row justify-content-center py-2">
    <div class="col-12 col-md-8 col-lg-6">
        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('siswa.rencana') }}" class="btn-brand-outline p-2 d-inline-flex" style="min-height:36px; width:36px; border-radius:50%;" title="Kembali ke Form">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div style="font-size:0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.04em;">
                    Langkah 2 dari 2
                </div>
                <h1 style="font-size:1.35rem; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.02em;">
                    Konfirmasi Pilihan Rencana
                </h1>
            </div>
        </div>

        <div class="card-pro shadow-elevated mb-4">
            <div class="card-pro-header" style="background:#f8fafc;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-primary fs-5"></i>
                    <span style="font-size:0.92rem; font-weight:800; color:#0f172a;">Ringkasan Data Rencana</span>
                </div>
                <div>
                    @if($data['rencana'] === 'kuliah')
                        <span class="badge-pill-soft badge-kuliah-soft">
                            <i class="bi bi-mortarboard-fill"></i> Kuliah
                        </span>
                    @elseif($data['rencana'] === 'bekerja')
                        <span class="badge-pill-soft badge-bekerja-soft">
                            <i class="bi bi-briefcase-fill"></i> Bekerja
                        </span>
                    @else
                        <span class="badge-pill-soft badge-wirausaha-soft">
                            <i class="bi bi-shop"></i> Berwirausaha
                        </span>
                    @endif
                </div>
            </div>

            <div class="card-pro-body">
                {{-- Siswa Info --}}
                <div class="row g-2 pb-3 mb-3" style="border-bottom:1px solid #f1f5f9;">
                    <div class="col-12">
                        <div style="font-size:0.72rem; font-weight:600; color:#64748b; text-transform:uppercase;">Nama Lengkap Siswa</div>
                        <div style="font-size:1rem; font-weight:800; color:#0f172a; margin-top:2px;">{{ $user->name }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:0.72rem; font-weight:600; color:#64748b; text-transform:uppercase;">NISN</div>
                        <div class="font-monospace fw-bold text-dark" style="font-size:0.88rem;">{{ $user->nisn ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:0.72rem; font-weight:600; color:#64748b; text-transform:uppercase;">Kelas</div>
                        <div class="fw-bold text-dark" style="font-size:0.88rem;">{{ $user->kelas ?? '-' }}</div>
                    </div>
                </div>

                {{-- Detail Pilihan --}}
                @if($data['rencana'] === 'kuliah')
                    <div class="p-3 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:var(--radius-md);">
                        <div style="font-size:0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase;">Perguruan Tinggi</div>
                        <div style="font-size:1.05rem; font-weight:800; color:#1e3a8a; margin-top:2px; line-height:1.25;">
                            {{ $data['nama_perguruan_tinggi'] }}
                        </div>

                        <div class="mt-3 pt-2" style="border-top:1px solid #dbeafe;">
                            <div style="font-size:0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase;">Program Studi</div>
                            <div style="font-size:0.98rem; font-weight:700; color:#1e40af; margin-top:2px;">
                                {{ $data['nama_program_studi'] }}
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-2">
                            <div style="font-size:0.78rem;">
                                <span class="text-muted">Jenjang:</span> <strong>{{ $data['jenjang'] }}</strong>
                            </div>
                            <div style="font-size:0.78rem;">
                                <span class="text-muted">Akreditasi:</span> <strong class="text-primary">{{ $data['akreditasi'] }} ⭐</strong>
                            </div>
                        </div>
                    </div>
                @elseif($data['rencana'] === 'bekerja')
                    <div class="p-3 mb-3" style="background:#fffbeb; border:1px solid #fde68a; border-radius:var(--radius-md);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-briefcase-fill text-warning fs-5"></i>
                            <div>
                                <strong style="color:#78350f; font-size:0.95rem;">Jalur Bekerja (Dunia Industri)</strong>
                                <div style="font-size:0.78rem; color:#92400e;">
                                    Kamu memilih untuk langsung berkarier di perusahaan/industri setelah kelulusan.
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5 rounded mt-2" style="background:#ffffff; border:1px solid #fef3c7;">
                            <div style="font-size:0.72rem; font-weight:700; color:#b45309; text-transform:uppercase;">Bidang Pekerjaan / Industri</div>
                            <div style="font-size:0.95rem; font-weight:800; color:#78350f; margin-top:2px;">
                                {{ $data['bidang_pekerjaan'] ?? 'Umum / Lainnya' }}
                            </div>
                            @if(!empty($data['keterangan_pekerjaan']))
                                <div class="mt-2 pt-2" style="border-top:1px dashed #fef3c7;">
                                    <div style="font-size:0.72rem; font-weight:600; color:#92400e;">Rencana / Keterangan Tambahan:</div>
                                    <div style="font-size:0.84rem; color:#451a03; font-style:italic; margin-top:2px;">
                                        "{{ $data['keterangan_pekerjaan'] }}"
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-3 mb-3" style="background:#faf5ff; border:1px solid #ddd6fe; border-radius:var(--radius-md);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-shop text-purple fs-5"></i>
                            <div>
                                <strong style="color:#4c1d95; font-size:0.95rem;">Jalur Berwirausaha (Mandiri)</strong>
                                <div style="font-size:0.78rem; color:#6d28d9;">
                                    Kamu memilih untuk merintis atau mengembangkan bisnis mandiri setelah kelulusan.
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5 rounded mt-2" style="background:#ffffff; border:1px solid #ede9fe;">
                            <div style="font-size:0.72rem; font-weight:700; color:#6d28d9; text-transform:uppercase;">Bidang Usaha / Bisnis</div>
                            <div style="font-size:0.95rem; font-weight:800; color:#4c1d95; margin-top:2px;">
                                {{ $data['bidang_usaha'] ?? 'Umum / Lainnya' }}
                            </div>
                            @if(!empty($data['keterangan_usaha']))
                                <div class="mt-2 pt-2" style="border-top:1px dashed #ede9fe;">
                                    <div style="font-size:0.72rem; font-weight:600; color:#6d28d9;">Rencana / Keterangan Usaha:</div>
                                    <div style="font-size:0.84rem; color:#2e1065; font-style:italic; margin-top:2px;">
                                        "{{ $data['keterangan_usaha'] }}"
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Pernyataan Siswa --}}
                <div class="form-check p-3 rounded border mb-2" style="background:#f8fafc;">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="checkPernyataan" required checked style="cursor:pointer;">
                    <label class="form-check-label text-dark" for="checkPernyataan" style="font-size:0.8rem; font-weight:600; line-height:1.45; cursor:pointer;">
                        Saya menyatakan pilihan ini telah dipertimbangkan dengan matang dan siap disimpan secara resmi ke sistem sekolah.
                    </label>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="d-flex gap-2">
            <a href="{{ route('siswa.rencana') }}" class="btn-brand-outline flex-fill py-3" style="font-size:0.88rem;">
                <i class="bi bi-pencil-square me-1"></i> Ubah Pilihan
            </a>

            <form method="POST" action="{{ route('siswa.submit') }}" class="flex-fill m-0" id="finalSubmitForm">
                @csrf
                <button type="submit"
                        class="btn-brand-primary w-100 py-3"
                        id="btnFinalSubmit"
                        onclick="this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Menyimpan...'; document.getElementById('finalSubmitForm').submit();">
                    <span>Kirim Pilihan Saya</span>
                    <i class="bi bi-send-check ms-1"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
