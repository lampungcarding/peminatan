@php
    $kopGambar = \App\Models\Setting::get('kop_gambar');
    $align = $align ?? 'center';
@endphp

<div class="official-kop-surat" style="width: 100%; text-align: {{ $align }}; margin-bottom: 6px; font-family: 'Roboto', Arial, Helvetica, sans-serif;">
    @if(!empty($kopGambar) && file_exists(public_path($kopGambar)))
        {{-- KOP Surat Berupa Gambar Resmi Sekolah Yang Diunggah --}}
        <div class="kop-image-wrapper" style="width: 100%; text-align: center; margin-bottom: 6px;">
            <img src="{{ asset($kopGambar) }}"
                 alt="KOP Surat Resmi Sekolah"
                 style="width: 100%; max-height: 125px; object-fit: contain; display: block; margin: 0 auto;">
        </div>
    @else
        {{-- Fallback jika belum mengunggah gambar KOP --}}
        <div style="text-align: center; padding: 4px 0 2px 0;">
            <div style="font-size: 11.5pt; font-weight: 900; text-transform: uppercase; letter-spacing: -0.01em; line-height: 1.3; color: #000000;">
                {{ \App\Models\Setting::get('nama_sekolah', 'SMK NEGERI 4 BANDAR LAMPUNG') }}
            </div>
            <div style="font-size: 7.5pt; color: #475569; margin-top: 1px;">
                {{ \App\Models\Setting::get('nama_aplikasi', 'Portal Rencana Setelah Lulus & Asesmen Peminatan') }} • Tahun Ajaran {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
            </div>
            {{-- Garis Ganda Standar --}}
            <div style="border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 3.5px; margin-top: 5px; margin-bottom: 8px;"></div>
        </div>
    @endif

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
