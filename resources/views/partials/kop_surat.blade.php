@php
    $kopInstansiAtas = \App\Models\Setting::get('kop_instansi_atas', 'PEMERINTAH PROVINSI LAMPUNG');
    $kopInstansiTengah = \App\Models\Setting::get('kop_instansi_tengah', 'DINAS PENDIDIKAN DAN KEBUDAYAAN');
    $kopNamaSekolah = \App\Models\Setting::get('kop_nama_sekolah', \App\Models\Setting::get('nama_sekolah', 'SMK NEGERI 4 BANDAR LAMPUNG'));
    $kopAlamat = \App\Models\Setting::get('kop_alamat', 'Jl. Hos Cokroaminoto No. 102, Enggal, Kota Bandar Lampung');
    $kopKodePos = \App\Models\Setting::get('kop_kode_pos', '35118');
    $kopKontak = \App\Models\Setting::get('kop_kontak', 'Telp: (0721) 261450 • Website: www.smkn4bandarlampung.sch.id • Email: smkn4bl@gmail.com');
    $align = $align ?? 'center'; // default center
@endphp

<div class="official-kop-surat" style="width: 100%; text-align: {{ $align }}; color: #000; font-family: 'Roboto', Arial, Helvetica, sans-serif;">
    <div style="font-size: 8pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.25; color: #1e293b;">
        {{ $kopInstansiAtas }}
    </div>
    <div style="font-size: 8.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; line-height: 1.25; color: #0f172a;">
        {{ $kopInstansiTengah }}
    </div>
    <div style="font-size: 11.5pt; font-weight: 900; text-transform: uppercase; letter-spacing: -0.01em; line-height: 1.3; margin: 1px 0; color: #000000;">
        {{ $kopNamaSekolah }}
    </div>
    <div style="font-size: 7.2pt; line-height: 1.35; color: #334155;">
        {{ $kopAlamat }} @if(!empty($kopKodePos)) Kodepos: {{ $kopKodePos }} @endif
    </div>
    <div style="font-size: 6.8pt; line-height: 1.35; color: #475569;">
        {{ $kopKontak }}
    </div>

    {{-- Garis Ganda Standar Surat Resmi Dinas --}}
    <div style="border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 3.5px; margin-top: 5px; margin-bottom: 8px;"></div>

    @if(!empty($judulDokumen))
        <div style="text-align: center; margin-top: 4px; margin-bottom: 8px;">
            <div style="font-size: 9.5pt; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em; color: #000; line-height: 1.3;">
                {{ $judulDokumen }}
            </div>
            @if(!empty($subJudulDokumen))
                <div style="font-size: 7.5pt; font-weight: 600; color: #475569; margin-top: 1px;">
                    {{ $subJudulDokumen }}
                </div>
            @endif
        </div>
    @endif
</div>
