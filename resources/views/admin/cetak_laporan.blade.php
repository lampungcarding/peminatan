<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hasil Rekapitulasi Siswa - {{ $selectedKelas }} - {{ $tahunLulus }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style id="dynamicPaperStyle">
        @page {
            size: 330mm 215mm landscape;
            margin: 5mm 6mm;
        }
    </style>

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
            max-width: 1250px;
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

        /* Printable Paper Sheet Landscape: Standar F4 / Folio (330mm x 215mm) atau Legal (356mm x 216mm) */
        .paper-sheet {
            background: #ffffff;
            width: 330mm;
            max-width: 100%;
            min-height: 215mm;
            margin: 0 auto;
            padding: 6mm 7mm;
            box-sizing: border-box;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Kop Surat & Dokumen Header */
        .doc-meta-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: 4px;
            margin-bottom: 6px;
            font-size: 7.8pt;
            font-weight: 700;
            box-sizing: border-box;
        }

        .doc-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Table Design: Fixed layout agar pas persis pada garis tepi kertas dan tidak melebihi margin */
        table.report-table {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 6.8pt;
            box-sizing: border-box;
        }

        table.report-table th, table.report-table td {
            border: 1px solid #000;
            padding: 2.5px 3px;
            vertical-align: middle;
            box-sizing: border-box;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        table.report-table th {
            text-align: center;
            font-weight: 700;
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 6.5pt;
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
            width: 100%;
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            box-sizing: border-box;
        }

        /* Rekap L/P Table */
        table.rekap-box {
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 7pt;
            width: 90px;
        }

        table.rekap-box td {
            border: 1px solid #000;
            padding: 2px 5px;
            height: 16px;
        }

        /* Signature block */
        .signature-box {
            text-align: left;
            font-size: 7.5pt;
            line-height: 1.35;
            min-width: 220px;
        }

        .signature-space {
            height: 38px;
        }

        /* Print Media Queries */
        @media print {
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
                width: 100% !important;
                max-width: 100% !important;
                table-layout: fixed !important;
                font-size: 6.8pt !important;
                border: 1.5px solid #000 !important;
            }

            table.report-table th, table.report-table td {
                border: 1px solid #000 !important;
                padding: 2px 2.5px !important;
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
                <input type="text" name="tahun_lulus" value="{{ $tahunLulus }}" class="control-input" style="width: 120px;" onchange="document.getElementById('filterForm').submit();">
            </div>

            <div>
                <span class="control-label">Mode:</span>
                <select name="mode" class="control-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="isi" {{ $mode === 'isi' ? 'selected' : '' }}>Data Lengkap Siswa</option>
                    <option value="kosong" {{ $mode === 'kosong' ? 'selected' : '' }}>Blanko Kosong</option>
                </select>
            </div>

            {{-- Ukuran Kertas Selector: F4 Landscape vs Legal Landscape --}}
            <div>
                <span class="control-label"><i class="bi bi-file-earmark-ruled me-1"></i> Kertas:</span>
                <select id="paperSizeSelect" class="control-select" onchange="switchPaperSize(this.value)">
                    <option value="f4" selected>F4 / Folio (330 × 215 mm)</option>
                    <option value="legal">Legal (356 × 216 mm)</option>
                </select>
            </div>
        </form>

        <div class="control-group">
            <a href="{{ route('admin.laporan') }}" class="btn-action btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="button" class="btn-action btn-print" onclick="window.print();">
                <i class="bi bi-printer-fill"></i> Cetak Dokumen (Landscape)
            </button>
        </div>
    </div>

    {{-- Paper Document Sheet (Landscape) --}}
    <div class="paper-sheet" id="printSheet">
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

        {{-- Table matching exact column structure with fixed layout (100% width) --}}
        <table class="report-table">
            <colgroup>
                <col style="width: 2.6%;">  <!-- NO -->
                <col style="width: 7.4%;">  <!-- NISN -->
                <col style="width: 17.0%;"> <!-- NAMA SISWA -->
                <col style="width: 2.6%;">  <!-- L/P -->
                <col style="width: 9.0%;">  <!-- NIK -->
                <col style="width: 11.4%;"> <!-- TEMPAT, TGL LAHIR -->
                <col style="width: 7.5%;">  <!-- KAB/KOTA -->
                <col style="width: 8.5%;">  <!-- KECAMATAN -->
                <col style="width: 7.8%;">  <!-- NO HP/WA -->
                <col style="width: 2.8%;">  <!-- BEKERJA -->
                <col style="width: 2.8%;">  <!-- KULIAH -->
                <col style="width: 2.8%;">  <!-- WIRAUSAHA -->
                <col style="width: 17.8%;"> <!-- KETERANGAN / DETAIL -->
            </colgroup>
            <thead>
                <tr>
                    <th rowspan="2" class="nowrap">NO</th>
                    <th rowspan="2" class="nowrap">NISN</th>
                    <th rowspan="2">NAMA SISWA</th>
                    <th rowspan="2" class="nowrap">L/P</th>
                    <th rowspan="2" class="nowrap">NIK</th>
                    <th rowspan="2">TEMPAT, TGL LAHIR</th>
                    <th colspan="2">ALAMAT TINGGAL</th>
                    <th rowspan="2" class="nowrap">NO. HP/WA</th>
                    <th colspan="3">MINAT SETELAH LULUS</th>
                    <th rowspan="2">KETERANGAN / DETAIL PILIHAN SISWA</th>
                </tr>
                <tr>
                    <th class="nowrap">KAB/KOTA</th>
                    <th class="nowrap">KECAMATAN</th>
                    <th class="nowrap" style="font-size: 5.8pt;">BEKERJA</th>
                    <th class="nowrap" style="font-size: 5.8pt;">KULIAH</th>
                    <th class="nowrap" style="font-size: 5.8pt;">WIRAUSAHA</th>
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
                        <td class="text-left fw-bold" style="text-transform: uppercase; font-size: 6.8pt; line-height: 1.15; word-break: break-word;">{{ $s->name }}</td>
                        <td class="text-center fw-bold nowrap" style="font-size: 6.8pt;">{{ $s->jk ?? '-' }}</td>
                        <td class="text-center nowrap" style="font-family: monospace; font-size: 6.6pt;">{{ $s->nik ?? '-' }}</td>
                        <td class="text-left" style="font-size: 6.6pt; line-height: 1.15; word-break: break-word;">{{ $ttl }}</td>
                        <td class="text-left" style="font-size: 6.6pt; line-height: 1.15; word-break: break-word;">{{ $kab }}</td>
                        <td class="text-left" style="font-size: 6.6pt; line-height: 1.15; word-break: break-word;">{{ $kec }}</td>
                        <td class="text-center nowrap" style="font-family: monospace; font-size: 6.6pt;">{{ $s->no_hp ?? '-' }}</td>

                        {{-- Checklist Minat --}}
                        <td class="text-center fw-bold nowrap" style="font-size: 8pt;">{{ $isBekerja ? '✔' : '' }}</td>
                        <td class="text-center fw-bold nowrap" style="font-size: 8pt;">{{ $isKuliah ? '✔' : '' }}</td>
                        <td class="text-center fw-bold nowrap" style="font-size: 8pt;">{{ $isWirausaha ? '✔' : '' }}</td>

                        {{-- Kolom Keterangan / Detail Pilihan Siswa Lengkap --}}
                        <td class="text-left" style="padding: 2px 4px; word-break: break-word;">
                            @if($mode === 'isi' && $p)
                                @if($rencana === 'kuliah')
                                    <div style="font-weight: 700; color: #0f172a; font-size: 6.8pt; line-height: 1.15;">
                                        🏛️ {{ $p->nama_perguruan_tinggi }}
                                    </div>
                                    <div style="color: #1d4ed8; font-weight: 600; font-size: 6.4pt; line-height: 1.15; margin-top: 1px;">
                                        Prodi: {{ $p->nama_program_studi }}@if($p->jenjang) ({{ $p->jenjang }})@endif
                                    </div>
                                @elseif($rencana === 'bekerja')
                                    <div style="font-weight: 700; color: #0f172a; font-size: 6.8pt; line-height: 1.15;">
                                        💼 {{ $p->bidang_pekerjaan }}
                                    </div>
                                    <div style="color: #b45309; font-weight: 600; font-size: 6.4pt; line-height: 1.15; margin-top: 1px;">
                                        Posisi: {{ $p->keterangan_pekerjaan ?: '-' }}
                                    </div>
                                @elseif($rencana === 'berwirausaha')
                                    <div style="font-weight: 700; color: #0f172a; font-size: 6.8pt; line-height: 1.15;">
                                        🚀 {{ $p->bidang_usaha }}
                                    </div>
                                    <div style="color: #6d28d9; font-weight: 600; font-size: 6.4pt; line-height: 1.15; margin-top: 1px;">
                                        Usaha: {{ $p->keterangan_usaha ?: '-' }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-style: italic; font-size: 6.5pt;">(Belum menentukan rencana)</span>
                                @endif
                            @elseif($mode === 'isi')
                                <span style="color: #94a3b8; font-style: italic; font-size: 6.5pt;">(Belum mengisi rencana)</span>
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
            <div>
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
                        <td class="text-center fw-bold" style="font-size: 6.8pt;">JML</td>
                        <td class="text-center fw-bold">{{ $totalCount }}</td>
                    </tr>
                </table>
                <div style="font-size: 6.5pt; color: #64748b; margin-top: 6px;">
                    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB
                </div>
            </div>

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

    <script>
        function switchPaperSize(size) {
            const sheet = document.getElementById('printSheet');
            const styleTag = document.getElementById('dynamicPaperStyle');
            if (size === 'legal') {
                sheet.style.width = '356mm';
                styleTag.innerHTML = '@page { size: 356mm 216mm landscape; margin: 5mm 6mm; }';
            } else {
                sheet.style.width = '330mm';
                styleTag.innerHTML = '@page { size: 330mm 215mm landscape; margin: 5mm 6mm; }';
            }
        }
    </script>

</body>
</html>
