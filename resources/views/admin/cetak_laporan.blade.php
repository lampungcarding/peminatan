<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hasil Rekapitulasi Siswa - {{ $selectedKelas }} - {{ $tahunLulus }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
            font-size: 8.5pt;
        }

        /* Screen Control Bar (Hidden on Print) */
        .control-bar {
            max-width: 1100px;
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

        /* Printable Paper Sheet */
        .paper-sheet {
            background: #ffffff;
            width: 297mm; /* Standard Landscape A4 width */
            min-height: 210mm;
            margin: 0 auto;
            padding: 12mm 14mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Header Form */
        .report-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 8px;
        }

        .header-meta {
            text-align: left;
            font-size: 8.5pt;
            font-weight: 700;
            line-height: 1.4;
        }

        .header-meta-row {
            display: flex;
        }

        .header-meta-label {
            width: 140px;
        }

        .header-meta-sep {
            width: 15px;
        }

        /* Table Design matching exact photo */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 8pt;
        }

        table.report-table th, table.report-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            vertical-align: middle;
        }

        table.report-table th {
            text-align: center;
            font-weight: 700;
            background-color: #ffffff;
            text-transform: uppercase;
            font-size: 7.5pt;
            line-height: 1.2;
        }

        table.report-table td {
            line-height: 1.2;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: 700; }

        /* Footer Section */
        .report-footer {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Rekap L/P Table */
        table.rekap-box {
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 8pt;
            width: 90px;
        }

        table.rekap-box td {
            border: 1px solid #000;
            padding: 2px 6px;
            height: 18px;
        }

        /* Signature block */
        .signature-box {
            text-align: left;
            font-size: 8.5pt;
            line-height: 1.4;
            min-width: 220px;
        }

        .signature-space {
            height: 50px;
        }

        /* Print Media Queries */
        @media print {
            @page {
                size: landscape;
                margin: 8mm 8mm 8mm 8mm;
            }

            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 7.5pt !important;
            }

            .no-print {
                display: none !important;
            }

            .paper-sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            table.report-table {
                font-size: 7.5pt !important;
                border: 1.5px solid #000 !important;
            }

            table.report-table th, table.report-table td {
                border: 1px solid #000 !important;
                padding: 2.5px 3px !important;
            }

            table.rekap-box {
                border: 1.5px solid #000 !important;
            }

            table.rekap-box td {
                border: 1px solid #000 !important;
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
                    <option value="isi" {{ $mode === 'isi' ? 'selected' : '' }}>Data Siswa & Pilihan Terisi</option>
                    <option value="kosong" {{ $mode === 'kosong' ? 'selected' : '' }}>Blanko Kosong (Sesuai Gambar)</option>
                </select>
            </div>
        </form>

        <div class="control-group">
            <a href="{{ route('admin.laporan') }}" class="btn-action btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Menu Laporan
            </a>
            <button type="button" class="btn-action btn-print" onclick="window.print();">
                <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Paper Document Sheet --}}
    <div class="paper-sheet">
        {{-- Header matching sample photo --}}
        <div class="report-header">
            <div class="header-meta">
                <div class="header-meta-row">
                    <div class="header-meta-label">TAHUN LULUS</div>
                    <div class="header-meta-sep">:</div>
                    <div>{{ $tahunLulus }}</div>
                </div>
                <div class="header-meta-row">
                    <div class="header-meta-label">KELAS / KOMPETENSI</div>
                    <div class="header-meta-sep">:</div>
                    <div>{{ $selectedKelas !== 'Semua' ? $selectedKelas : 'SELURUH KELAS 12' }}</div>
                </div>
            </div>
        </div>

        {{-- Table matching exact column structure from user's image --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 25px;">NO</th>
                    <th rowspan="2" style="width: 75px;">NISN</th>
                    <th rowspan="2" style="min-width: 170px;">NAMA</th>
                    <th rowspan="2" style="width: 30px;">L/P</th>
                    <th rowspan="2" style="width: 115px;">NIK</th>
                    <th rowspan="2" style="width: 130px;">TEMPAT, TGL LAHIR</th>
                    <th colspan="2" style="width: 170px;">ALAMAT</th>
                    <th rowspan="2" style="width: 85px;">HP/WA</th>
                    <th colspan="3" style="width: 175px;">MINAT SETELAH LULUS</th>
                    <th rowspan="2" style="width: 90px;">KET</th>
                </tr>
                <tr>
                    <th style="width: 85px;">KAB/KOTA</th>
                    <th style="width: 85px;">KEC.</th>
                    <th style="width: 55px;">BEKERJA</th>
                    <th style="width: 65px;">MELANJUTKAN</th>
                    <th style="width: 55px;">WIRAUSAHA</th>
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

                        // Keterangan pilihan
                        $ket = '';
                        if ($mode === 'isi' && $p) {
                            if ($rencana === 'kuliah') {
                                $ket = $p->nama_perguruan_tinggi ? $p->nama_perguruan_tinggi : 'Kuliah';
                            } elseif ($rencana === 'bekerja') {
                                $ket = $p->bidang_pekerjaan ? $p->bidang_pekerjaan : 'Bekerja';
                            } elseif ($rencana === 'berwirausaha') {
                                $ket = $p->bidang_usaha ? $p->bidang_usaha : 'Wirausaha';
                            }
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center" style="font-family: monospace; font-size: 7.5pt;">{{ $s->nisn ?? '-' }}</td>
                        <td class="text-left fw-bold" style="text-transform: uppercase;">{{ $s->name }}</td>
                        <td class="text-center">{{ $s->jk ?? '-' }}</td>
                        <td class="text-center" style="font-family: monospace; font-size: 7.5pt;">{{ $s->nik ?? '-' }}</td>
                        <td class="text-left" style="font-size: 7.5pt;">{{ $ttl }}</td>
                        <td class="text-left" style="font-size: 7.5pt;">{{ $kab }}</td>
                        <td class="text-left" style="font-size: 7.5pt;">{{ $kec }}</td>
                        <td class="text-center" style="font-family: monospace; font-size: 7.5pt;">{{ $s->no_hp ?? '-' }}</td>
                        <td class="text-center fw-bold">{{ $isBekerja ? '✓' : '' }}</td>
                        <td class="text-center fw-bold">{{ $isKuliah ? '✓' : '' }}</td>
                        <td class="text-center fw-bold">{{ $isWirausaha ? '✓' : '' }}</td>
                        <td class="text-left" style="font-size: 7.5pt;">{{ $ket }}</td>
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
                    <td class="text-center fw-bold" style="width: 30px;">L</td>
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
                <div style="margin-top: 3px;">Guru Bimbingan Konseling / Wali Kelas,</div>
                <div class="signature-space"></div>
                <div class="fw-bold" style="text-decoration: underline;">{{ $namaGuruBk }}</div>
                <div>NIP. ....................................................</div>
            </div>
        </div>
    </div>

</body>
</html>
