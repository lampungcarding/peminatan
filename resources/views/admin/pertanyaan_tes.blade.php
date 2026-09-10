@extends('layouts.admin')

@section('title', 'Kelola Pertanyaan Tes Minat & Motivasi Kerja')

@section('content')
<div class="page-title-row">
    <div>
        <h1>Bank Pertanyaan Tes Minat & Motivasi Kerja</h1>
        <p>Kelola butir pernyataan kuesioner RIASEC (Sesi 1) &amp; Career Anchors (Sesi 2)</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahSoal" style="border-radius: var(--radius-md);">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pertanyaan
        </button>
    </div>
</div>

<div class="panel-card mb-4">
    <div class="table-responsive-custom">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;" class="text-center">No</th>
                    <th style="width: 130px;">Sesi</th>
                    <th style="width: 140px;">Dimensi</th>
                    <th>Pernyataan Aktivitas</th>
                    <th style="width: 90px;" class="text-center">Status</th>
                    <th style="text-align: right; padding-right: 16px; width: 90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    <tr>
                        <td class="fw-bold text-center">{{ $q->order_num }}</td>
                        <td>
                            @if($q->section === 'career_anchor')
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">
                                    Sesi 2: Anchor
                                </span>
                            @else
                                <span class="badge bg-primary fw-bold px-2 py-1" style="font-size: 0.72rem;">
                                    Sesi 1: RIASEC
                                </span>
                            @endif
                        </td>
                        <td>
                            @php
                                $dimColor = match($q->section === 'career_anchor' ? $q->type_anchor : $q->type_riasec) {
                                    'R' => '#3b82f6',
                                    'I' => '#06b6d4',
                                    'A' => '#ec4899',
                                    'S' => '#10b981',
                                    'E' => '#f59e0b',
                                    'C' => '#8b5cf6',
                                    'TF' => '#1d4ed8',
                                    'GM' => '#b45309',
                                    'AU' => '#4c1d95',
                                    'SE' => '#047857',
                                    'EC' => '#c2410c',
                                    'SV' => '#0d9488',
                                    'CH' => '#be123c',
                                    'LS' => '#4338ca',
                                    default => '#64748b'
                                };
                            @endphp
                            <span class="badge text-white fw-bold px-2 py-1 font-monospace" style="background: {{ $dimColor }}; font-size:0.75rem;">
                                {{ $q->section === 'career_anchor' ? $q->type_anchor : $q->type_riasec }} ({{ $q->dimension_name }})
                            </span>
                        </td>
                        <td style="font-size: 0.88rem; font-weight: 600; color: #1e293b; line-height: 1.5;">
                            {{ $q->question }}
                        </td>
                        <td class="text-center">
                            @if($q->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:0.72rem;">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size:0.72rem;">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right; padding-right: 16px;">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditSoal_{{ $q->id }}"
                                    style="font-size: 0.75rem; padding: 4px 10px;">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Belum ada pertanyaan tes yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Render All Edit Modals Outside Table Structure --}}
@foreach($questions as $q)
    <div class="modal fade" id="modalEditSoal_{{ $q->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.pertanyaan-tes.update', $q->id) }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: var(--radius-lg);">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" style="font-size: 1rem;">Edit Pertanyaan #{{ $q->order_num }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Sesi Kuesioner</label>
                        <select name="section" class="form-select" id="edit_section_{{ $q->id }}" onchange="toggleEditSection({{ $q->id }})" required>
                            <option value="riasec" {{ $q->section !== 'career_anchor' ? 'selected' : '' }}>Sesi 1: Minat Karier (RIASEC)</option>
                            <option value="career_anchor" {{ $q->section === 'career_anchor' ? 'selected' : '' }}>Sesi 2: Motivasi Kerja (Career Anchors)</option>
                        </select>
                    </div>

                    {{-- RIASEC Dropdown --}}
                    <div class="mb-3 {{ $q->section === 'career_anchor' ? 'd-none' : '' }}" id="group_riasec_{{ $q->id }}">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Dimensi RIASEC</label>
                        <select name="type_riasec" class="form-select">
                            <option value="R" {{ $q->type_riasec === 'R' ? 'selected' : '' }}>R — Realistic (Praktis, Teknis, Alat)</option>
                            <option value="I" {{ $q->type_riasec === 'I' ? 'selected' : '' }}>I — Investigative (Analitis, Riset, Logika)</option>
                            <option value="A" {{ $q->type_riasec === 'A' ? 'selected' : '' }}>A — Artistic (Kreatif, Desain, Seni)</option>
                            <option value="S" {{ $q->type_riasec === 'S' ? 'selected' : '' }}>S — Social (Sosial, Mengajar, Empati)</option>
                            <option value="E" {{ $q->type_riasec === 'E' ? 'selected' : '' }}>E — Enterprising (Bisnis, Memimpin, Negosiasi)</option>
                            <option value="C" {{ $q->type_riasec === 'C' ? 'selected' : '' }}>C — Conventional (Terstruktur, Ketelitian Data)</option>
                        </select>
                    </div>

                    {{-- Career Anchor Dropdown --}}
                    <div class="mb-3 {{ $q->section !== 'career_anchor' ? 'd-none' : '' }}" id="group_anchor_{{ $q->id }}">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Tipe Career Anchor (Schein)</label>
                        <select name="type_anchor" class="form-select">
                            <option value="TF" {{ $q->type_anchor === 'TF' ? 'selected' : '' }}>TF — Technical/Functional Competence</option>
                            <option value="GM" {{ $q->type_anchor === 'GM' ? 'selected' : '' }}>GM — General Manager Competence</option>
                            <option value="AU" {{ $q->type_anchor === 'AU' ? 'selected' : '' }}>AU — Autonomy/Independence</option>
                            <option value="SE" {{ $q->type_anchor === 'SE' ? 'selected' : '' }}>SE — Security/Stability</option>
                            <option value="EC" {{ $q->type_anchor === 'EC' ? 'selected' : '' }}>EC — Entrepreneurial Creativity</option>
                            <option value="SV" {{ $q->type_anchor === 'SV' ? 'selected' : '' }}>SV — Service/Dedication to a Cause</option>
                            <option value="CH" {{ $q->type_anchor === 'CH' ? 'selected' : '' }}>CH — Pure Challenge</option>
                            <option value="LS" {{ $q->type_anchor === 'LS' ? 'selected' : '' }}>LS — Lifestyle Balance</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Teks Pertanyaan</label>
                        <textarea name="question" class="form-control" rows="3" required>{{ $q->question }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Status Pertanyaan</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ $q->status === 'active' ? 'selected' : '' }}>Aktif (Tampil pada tes)</option>
                            <option value="inactive" {{ $q->status === 'inactive' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- Modal Tambah Pertanyaan Baru --}}
<div class="modal fade" id="modalTambahSoal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.pertanyaan-tes.simpan') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: var(--radius-lg);">
            @csrf
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" style="font-size: 1rem;">Tambah Pertanyaan Tes Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Sesi Kuesioner <span class="text-danger">*</span></label>
                    <select name="section" class="form-select" id="add_section" onchange="toggleAddSection()" required>
                        <option value="riasec">Sesi 1: Minat Karier (RIASEC)</option>
                        <option value="career_anchor">Sesi 2: Motivasi Kerja (Career Anchors)</option>
                    </select>
                </div>

                {{-- RIASEC Dropdown --}}
                <div class="mb-3" id="add_group_riasec">
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Dimensi RIASEC <span class="text-danger">*</span></label>
                    <select name="type_riasec" class="form-select">
                        <option value="R">R — Realistic (Praktis, Teknis, Alat)</option>
                        <option value="I">I — Investigative (Analitis, Riset, Logika)</option>
                        <option value="A">A — Artistic (Kreatif, Desain, Seni)</option>
                        <option value="S">S — Social (Sosial, Mengajar, Empati)</option>
                        <option value="E">E — Enterprising (Bisnis, Memimpin, Negosiasi)</option>
                        <option value="C">C — Conventional (Terstruktur, Ketelitian Data)</option>
                    </select>
                </div>

                {{-- Career Anchor Dropdown --}}
                <div class="mb-3 d-none" id="add_group_anchor">
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Tipe Career Anchor (Schein) <span class="text-danger">*</span></label>
                    <select name="type_anchor" class="form-select">
                        <option value="TF">TF — Technical/Functional Competence</option>
                        <option value="GM">GM — General Manager Competence</option>
                        <option value="AU">AU — Autonomy/Independence</option>
                        <option value="SE">SE — Security/Stability</option>
                        <option value="EC">EC — Entrepreneurial Creativity</option>
                        <option value="SV">SV — Service/Dedication to a Cause</option>
                        <option value="CH">CH — Pure Challenge</option>
                        <option value="LS">LS — Lifestyle Balance</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Teks Pertanyaan <span class="text-danger">*</span></label>
                    <textarea name="question" class="form-control" rows="3" placeholder="Tuliskan pernyataan aktivitas..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Nomor Urut (Opsional)</label>
                    <input type="number" name="order_num" class="form-control" placeholder="Biarkan kosong untuk urutan otomatis">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary fw-bold">Tambah Pertanyaan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleEditSection(qId) {
        const sec = document.getElementById('edit_section_' + qId).value;
        const gRiasec = document.getElementById('group_riasec_' + qId);
        const gAnchor = document.getElementById('group_anchor_' + qId);

        if (sec === 'career_anchor') {
            gRiasec.classList.add('d-none');
            gAnchor.classList.remove('d-none');
        } else {
            gRiasec.classList.remove('d-none');
            gAnchor.classList.add('d-none');
        }
    }

    function toggleAddSection() {
        const sec = document.getElementById('add_section').value;
        const gRiasec = document.getElementById('add_group_riasec');
        const gAnchor = document.getElementById('add_group_anchor');

        if (sec === 'career_anchor') {
            gRiasec.classList.add('d-none');
            gAnchor.classList.remove('d-none');
        } else {
            gRiasec.classList.remove('d-none');
            gAnchor.classList.add('d-none');
        }
    }
</script>
@endpush
@endsection
