<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hasil Rekapitulasi Siswa - {{ $selectedKelas }} - {{ $tahunLulus }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
            background-color: #f1f5f9;
            color: #000;
            margin: 0;
            padding: 20px 0;
            font-size: 8pt;
        }

        /* Screen Control Bar (Hidden on Print) */
        .control-bar {
            max-width: 1200px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .control-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .control-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
        }

        .control-select, .control-input {
            padding: 6px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.82rem;
            background: #fff;
            color: #0f172a;
            outline: none;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        /* Printable Paper Sheet Landscape */
        .paper-sheet {
            background: #ffffff;
            width: 297mm; /* Standard Landscape A4 width */
            min-height: 210mm;
            margin: 0 auto;
            padding: 7mm 8mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Kop Surat & Dokumen Header */
        .doc-meta-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
            margin-bottom: 7px;
            font-size: 7.8pt;
            font-weight: 700;
        }

        .doc-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Table Design: Biodata 1 Baris Rapih & Elegan */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 7pt;
        }

        table.report-table th, table.report-table td {
            border: 1px solid #000;
            padding: 2.5px 3.5px;
            vertical-align: middle;
        }

        table.report-table th {
            text-align: center;
            font-weight: 700;
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 6.8pt;
            line-height: 1.15;
            letter-spacing: 0.01em;
        }

        table.report-table td {
            line-height: 1.2;
        }

        .nowrap {
            white-space: nowrap !important;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: 700; }

        /* Footer Section */
        .report-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Rekap L/P Table */
        table.rekap-box {
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 7.2pt;
            width: 90px;
        }

        table.rekap-box td {
            border: 1px solid #000;
            padding: 2px 5px;
            height: 17px;
        }

        /* Signature block */
        .signature-box {
            text-align: left;
            font-size: 7.5pt;
            line-height: 1.35;
            min-width: 220px;
        }

        .signature-space {
            height: 42px;
        }

        /* Print Media Queries */
        @media print {
            @page {
                size: landscape;
                margin: 5mm 5mm 5mm 5mm;
            }

            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 6.8pt !important;
            }

            .no-print {
                display: none !important;
            }

            .paper-sheet {
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            table.report-table {
                font-size: 6.8pt !important;
                border: 1.5px solid #000 !important;
            }

            table.report-table th, table.report-table td {
                border: 1px solid #000 !important;
                padding: 2px 3px !important;
            }

            table.rekap-box {
                border: 1.5px solid #000 !important;
            }

            table.rekap-box td {
                border: 1px solid #000 !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- Screen Control Bar --}}
    <div class="control-bar no-print">
        <form method="GET" action="{{ route('admin.laporan.cetak') }}" class="control-group" id="filterForm">
            <div>
                <span class="control-label"><i class="bi bi-funnel me-1"></i> Kelas:</span>
                <select name="kelas" class="control-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="Semua">Semua Kelas ({{ $totalCount }} Siswa)</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k }}" {{ $selectedKelas === $k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <span class="control-label">Tahun:</span>
                <input type="text" name="tahun_lulus" value="{{ $tahunLulus }}" class="control-input" style="width: 130px;" onchange="document.getElementById('filterForm').submit();">
            </div>

            <div>
                <span class="control-label">Mode:</span>
                <select name="mode" class="control-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="isi" {{ $mode === 'isi' ? 'selected' : '' }}>Data Lengkap Siswa & Pilihan</option>
                    <option value="kosong" {{ $mode === 'kosong' ? 'selected' : '' }}>Blanko Kosong (Format Fisik)</option>
                </select>
            </div>
        </form>

        <div class="control-group">
            <a href="{{ route('admin.laporan') }}" class="btn-action btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Menu Laporan
            </a>
            <button type="button" class="btn-action btn-print" onclick="window.print();">
                <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF (Landscape)
            </button>
        </div>
    </div>

    {{-- Paper Document Sheet (Landscape) --}}
    <div class="paper-sheet">
        {{-- KOP Surat Resmi Sekolah Dinamis dari Pengaturan --}}
        @include('partials.kop_surat', [
            'judulDokumen' => 'REKAPITULASI HASIL ASESMEN PEMINATAN & RENCANA PILIHAN SISWA SETELAH LULUS',
            'subJudulDokumen' => null,
            'align' => 'center'
        ])

        {{-- Meta Dokumen (Tahun Lulus & Kelas) --}}
        <div class="doc-meta-banner">
            <div class="doc-meta-item">
                <span style="color: #475569;">KELAS / KOMPETENSI :</span>
                <span class="fw-bold text-dark" style="font-size: 8pt; text-transform: uppercase;">
                    {{ $selectedKelas !== 'Semua' ? $selectedKelas : 'SELURUH KELAS 12' }}
                </span>
            </div>
            <div class="doc-meta-item">
                <span style="color: #475569;">TAHUN LULUS :</span>
                <span class="fw-bold text-dark" style="font-size: 8pt;">{{ $tahunLulus }}</span>
            </div>
        </div>

        {{-- Table matching exact column structure with full detail per plan --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 22px;" class="nowrap">NO</th>
                    <th rowspan="2" style="width: 68px;" class="nowrap">NISN</th>
                    <th rowspan="2" style="width: 155px;" class="nowrap">NAMA SISWA</th>
                    <th rowspan="2" style="width: 22px;" class="nowrap">L/P</th>
                    <th rowspan="2" style="width: 95px;" class="nowrap">NIK</th>
                    <th rowspan="2" style="width: 115px;" class="nowrap">TEMPAT, TGL LAHIR</th>
                    <th colspan="2" style="width: 175px;">ALAMAT TINGGAL</th>
                    <th rowspan="2" style="width: 78px;" class="nowrap">NO. HP/WA</th>
                    <th colspan="3" style="width: 96px;">MINAT SETELAH LULUS</th>
                    <th rowspan="2" style="min-width: 170px;">KETERANGAN / DETAIL PILIHAN SISWA</th>
                </tr>
                <tr>
                    <th style="width: 80px;" class="nowrap">KAB/KOTA</th>
                    <th style="width: 95px;" class="nowrap">KECAMATAN</th>
                    <th style="width: 32px;" class="nowrap">BEKERJA</th>
                    <th style="width: 32px;" class="nowrap">KULIAH</th>
                    <th style="width: 32px;" class="nowrap">WIRAUSAHA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $s)
                    @php
                        $p = $s->pilihanSetelahLulus;
                        $rencana = $p ? $p->rencana : null;
                        $isBekerja = ($mode === 'isi' && $rencana === 'bekerja');
                        $isKuliah = ($mode === 'isi' && $rencana === 'kuliah');
                        $isWirausaha = ($mode === 'isi' && $rencana === 'berwirausaha');

                        // Tempat & Tanggal Lahir
                        $ttl = $s->ttl_ringkas;

                        // Kecamatan & Kab/Kota
                        $kec = $s->kecamatan ? str_ireplace('Kec. ', '', $s->kecamatan) : '-';
                        $kab = $s->kabupaten_kota ? str_ireplace(['Kota ', 'Kab. '], '', $s->kabupaten_kota) : 'Bandar Lampung';
                    @endphp
                    <tr>
                        <td class="text-center nowrap" style="font-size: 6.8pt;">{{ $idx + 1 }}</td>
                        <td class="text-center nowrap" style="font-family: monospace; font-size: 6.8pt;">{{ $s->nisn ?? '-' }}</td>
                        <td class="text-left fw-bold nowrap" style="text-transform: uppercase; font-size: 7pt; letter-spacing: -0.01em;">{{ $s->name }}</td>
                        <td class="text-center fw-bold nowrap" style="font-size: 7pt;">{{ $s->jk ?? '-' }}</td>
                        <td class="text-center nowrap" style="font-family: monospace; font-size: 6.8pt;">{{ $s->nik ?? '-' }}</td>
                        <td class="text-left nowrap" style="font-size: 6.8pt;">{{ $ttl }}</td>
                        <td class="text-left nowrap" style="font-size: 6.8pt;">{{ $kab }}</td>
                        <td class="text-left nowrap" style="font-size: 6.8pt;">{{ $kec }}</td>
                        <td class="text-center nowrap" style="font-family: monospace; font-size: 6.8pt;">{{ $s->no_hp ?? '-' }}</td>
                        
                        {{-- Checklist Minat --}}
                        <td class="text-center fw-bold nowrap" style="font-size: 8pt;">{{ $isBekerja ? '✔' : '' }}</td>
                        <td class="text-center fw-bold nowrap" style="font-size: 8pt;">{{ $isKuliah ? '✔' : '' }}</td>
                        <td class="text-center fw-bold nowrap" style="font-size: 8pt;">{{ $isWirausaha ? '✔' : '' }}</td>

                        {{-- Kolom Keterangan / Detail Pilihan Siswa Lengkap --}}
                        <td class="text-left" style="padding: 2.5px 5px;">
                            @if($mode === 'isi' && $p)
                                @if($rencana === 'kuliah')
                                    <div style="font-weight: 700; color: #0f172a; font-size: 7.2pt; line-height: 1.2;">
                                        🏛️ {{ $p->nama_perguruan_tinggi }}
                                    </div>
                                    <div style="color: #1d4ed8; font-weight: 600; font-size: 6.8pt; line-height: 1.2; margin-top: 1px;">
                                        Prodi: {{ $p->nama_program_studi }}@if($p->jenjang) ({{ $p->jenjang }})@endif
                                    </div>
                                @elseif($rencana === 'bekerja')
                                    <div style="font-weight: 700; color: #0f172a; font-size: 7.2pt; line-height: 1.2;">
                                        💼 {{ $p->bidang_pekerjaan }}
                                    </div>
                                    <div style="color: #b45309; font-weight: 600; font-size: 6.8pt; line-height: 1.2; margin-top: 1px;">
                                        Posisi: {{ $p->keterangan_pekerjaan ?: '-' }}
                                    </div>
                                @elseif($rencana === 'berwirausaha')
                                    <div style="font-weight: 700; color: #0f172a; font-size: 7.2pt; line-height: 1.2;">
                                        🚀 {{ $p->bidang_usaha }}
                                    </div>
                                    <div style="color: #6d28d9; font-weight: 600; font-size: 6.8pt; line-height: 1.2; margin-top: 1px;">
                                        Usaha: {{ $p->keterangan_usaha ?: '-' }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-style: italic; font-size: 6.8pt;">(Belum menentukan rencana)</span>
                                @endif
                            @elseif($mode === 'isi')
                                <span style="color: #94a3b8; font-style: italic; font-size: 6.8pt;">(Belum mengisi rencana)</span>
                            @else
                                {{-- Mode Blanko Kosong --}}
                                &nbsp;
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center" style="padding: 20px;">Tidak ada data siswa untuk kelas ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer Section with L/P summary on left and signature on right --}}
        <div class="report-footer">
            {{-- Box summary on left matching photo --}}
            <table class="rekap-box">
                <tr>
                    <td class="text-center fw-bold" style="width: 32px;">L</td>
                    <td class="text-center fw-bold">{{ $countL }}</td>
                </tr>
                <tr>
                    <td class="text-center fw-bold">P</td>
                    <td class="text-center fw-bold">{{ $countP }}</td>
                </tr>
                <tr>
                    <td class="text-center fw-bold" style="font-size: 7pt;">JML</td>
                    <td class="text-center fw-bold">{{ $totalCount }}</td>
                </tr>
            </table>

            {{-- Signature block on right matching photo --}}
            <div class="signature-box">
                <div>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div style="margin-top: 2px;">Guru Bimbingan Konseling / Wali Kelas,</div>
                <div class="signature-space"></div>
                <div class="fw-bold" style="text-decoration: underline;">{{ $namaGuruBk }}</div>
                <div>NIP. ....................................................</div>
            </div>
        </div>
    </div>

</body>
</html>
