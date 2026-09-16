@extends('layouts.admin')

@section('title', 'Data Kampus & Prodi (Tracer Kemendikdasmen)')

@section('content')
<div class="page-title-row">
    <div>
        <h1>Data Kampus & Program Studi</h1>
        <p>Integrasi pangkalan data resmi Tracer Vokasi Kemendikdasmen (4.800+ Perguruan Tinggi)</p>
    </div>
    <div class="date-badge">
        <i class="bi bi-hdd-network-fill" style="color: #2563eb;"></i>
        <span>Status Basis Data: 4.800+ Kampus Aktif</span>
    </div>
</div>

<div class="panel-card mb-4">
    <div class="panel-header">
        <div>
            <h2>Pencarian Perguruan Tinggi</h2>
            <p>Cari universitas, institut, atau politeknik untuk melihat daftar jurusan resmi</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.kampus-prodi') }}" class="d-flex gap-2 mb-4">
        <input type="text"
               name="q"
               class="filter-select flex-grow-1"
               placeholder="Ketik nama kampus (contoh: lampung, indonesia, brawijaya, gajah mada, itb)..."
               value="{{ $keyword }}"
               required>
        <button type="submit" class="btn-filter" style="min-width: 140px;">
            <i class="bi bi-search"></i> Cari Kampus
        </button>
    </form>

    <div class="row g-3">
        @forelse($kampusResults as $item)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card-pro p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:32px; height:32px; border-radius:8px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1rem;">
                                <i class="bi bi-building"></i>
                            </div>
                            <span class="badge bg-light text-dark border" style="font-size:0.72rem;">ID: {{ $item['id'] }}</span>
                        </div>
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                            {{ $item['nama'] }}
                        </h3>
                    </div>

                    <div class="mt-3 pt-2 border-top">
                        <button type="button"
                                class="btn btn-sm btn-outline-primary w-100 fw-bold"
                                onclick="fetchProdiAdmin('{{ addslashes($item['nama']) }}')"
                                style="font-size: 0.78rem;">
                            <i class="bi bi-mortarboard me-1"></i> Tinjau Daftar Prodi
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-search fs-2 d-block mb-2 text-secondary"></i>
                Tidak menemukan perguruan tinggi dengan kata kunci "{{ $keyword }}". Coba gunakan kata kunci umum seperti "negeri", "universitas", atau "lampung".
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Preview Prodi --}}
<div class="modal fade" id="modalProdiAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modalKampusTitle" style="font-size: 1.05rem;">Daftar Program Studi</h5>
                    <div style="font-size: 0.75rem; color: #64748b;" id="modalProdiCount">Memuat data dari KIP Kuliah...</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Program Studi</th>
                                <th>Jenjang</th>
                                <th>Akreditasi</th>
                            </tr>
                        </thead>
                        <tbody id="modalProdiBody">
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat program studi...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';

    function fetchProdiAdmin(ptNama) {
        document.getElementById('modalKampusTitle').textContent = ptNama;
        document.getElementById('modalProdiCount').textContent = 'Memuat data resmi dari BAN-PT...';
        document.getElementById('modalProdiBody').innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div> Mengambil data program studi & akreditasi BAN-PT...
                </td>
            </tr>`;

        const modal = new bootstrap.Modal(document.getElementById('modalProdiAdmin'));
        modal.show();

        fetch('{{ route("admin.cari-prodi") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ pt_id: ptNama })
        })
        .then(res => res.json())
        .then(data => {
            let prodis = [];
            let campus = null;

            if (Array.isArray(data)) {
                prodis = data;
            } else {
                prodis = data.prodi || [];
                campus = data.campus || null;
            }

            const akredText = campus && campus.akreditasi_pt && campus.akreditasi_pt !== '-' ? ` · Akreditasi Institusi: ${campus.akreditasi_pt}` : '';
            document.getElementById('modalProdiCount').textContent = `Ditemukan ${prodis.length} Program Studi D3/D4/S1${akredText}`;

            let html = '';
            if (prodis.length === 0) {
                html = '<tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada program studi ditemukan untuk kampus ini.</td></tr>';
            } else {
                prodis.forEach((p, index) => {
                    const badgeClass = p.akreditasi === 'Unggul' || p.akreditasi === 'A' 
                        ? 'bg-success-subtle text-success-emphasis' 
                        : (p.akreditasi === 'Baik Sekali' || p.akreditasi === 'B' 
                            ? 'bg-primary-subtle text-primary-emphasis' 
                            : 'bg-warning-subtle text-warning-emphasis');

                    html += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td class="fw-bold">${p.nama}</td>
                            <td><span class="badge bg-light text-dark border">${p.jenjang || '-'}</span></td>
                            <td><span class="badge ${badgeClass}">${p.akreditasi || '-'}</span></td>
                        </tr>`;
                });
            }
            document.getElementById('modalProdiBody').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('modalProdiBody').innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Gagal memuat data dari pangkalan data.</td></tr>';
        });
    }
</script>
@endpush
