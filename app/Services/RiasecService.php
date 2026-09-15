<?php

namespace App\Services;

use App\Models\CareerAnswer;
use App\Models\CareerQuestion;
use App\Models\CareerRecommendation;
use App\Models\CareerResult;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RiasecService
{
    /**
     * Nama dimensi lengkap berdasarkan kodenya.
     */
    public const DIMENSION_QUESTION_COUNTS = [
        'R' => 10,
        'I' => 5,
        'A' => 8,
        'S' => 8,
        'E' => 8,
        'C' => 9,
    ];

    public const DIMENSION_MAX_SCORES = [
        'R' => 50,
        'I' => 25,
        'A' => 40,
        'S' => 40,
        'E' => 40,
        'C' => 45,
    ];

    public const DIMENSIONS = [
        'R' => 'Realistic',
        'I' => 'Investigative',
        'A' => 'Artistic',
        'S' => 'Social',
        'E' => 'Enterprising',
        'C' => 'Conventional',
    ];

    public const DIMENSION_LABELS = [
        'R' => 'sistem teknologi praktis & keterampilan teknis (R)',
        'I' => 'analisis data mendalam & pemecahan masalah (I)',
        'A' => 'kreativitas visual, estetika, & inovasi ide (A)',
        'S' => 'pelayanan prima, komunikasi, & hubungan sosial (S)',
        'E' => 'kepemimpinan bisnis, negosiasi, & strategi pasar (E)',
        'C' => 'ketelitian administrasi, manajemen dokumen, & keteraturan sistem (C)',
    ];

    /**
     * Pemetaan singkatan kelas siswa SMK ke nama jurusan standar.
     */
    public const MAJOR_ABBREVIATIONS = [
        'RPL'  => 'PPLG',
        'PPLG' => 'PPLG',
        'TKJ'  => 'TJKT',
        'TJKT' => 'TJKT',
        'BR'   => 'Pemasaran',
        'PM'   => 'Pemasaran',
        'MPLB' => 'Manajemen Perkantoran',
        'MP'   => 'Manajemen Perkantoran',
        'AKL'  => 'Akuntansi Keuangan Lembaga',
        'AK'   => 'Akuntansi Keuangan Lembaga',
        'ULW'  => 'Usaha Layanan Wisata',
        'UPW'  => 'Usaha Layanan Wisata',
        'PHT'  => 'Perhotelan',
        'PH'   => 'Perhotelan',
        'KUL'  => 'Kuliner',
        'TB'   => 'Kuliner',
        'DKV'  => 'Desain Komunikasi Visual',
        'DPB'  => 'Busana',
        'TBUS' => 'Busana',
        'BS'   => 'Busana',
    ];

    /**
     * Matriks Pengetahuan Kurasi 10 Jurusan SMK (Linier & Lintas Minat).
     */
    public const VOCATIONAL_MATRIX = [
        'PPLG' => [
            'name' => 'Pengembangan Perangkat Lunak dan Gim',
            'short_name' => 'PPLG (RPL)',
            'code' => 'RPL',
            'core_dimensions' => ['I', 'R', 'C'],
            'kuliah' => [
                ['name' => 'S1 Informatika / Rekayasa Perangkat Lunak', 'code' => 'I - R', 'desc' => 'Memperdalam rekayasa arsitektur perangkat lunak, algoritma pemrograman tingkat lanjut, dan pengembangan aplikasi cloud.'],
                ['name' => 'D4 Teknologi Rekayasa Perangkat Lunak Aplikasi', 'code' => 'I - C', 'desc' => 'Fokus vokasi praktis pada pembuatan aplikasi enterprise, pengujian kode sistematis, dan pemeliharaan basis data.'],
                ['name' => 'S1 Sains Data & Kecerdasan Buatan (Data Science & AI)', 'code' => 'I - C', 'desc' => 'Menggabungkan keahlian coding logika dengan pengolahan data analitik, machine learning, dan model prediksi bisnis.'],
                ['name' => 'S1 Sistem Informasi Bisnis', 'code' => 'I - E', 'desc' => 'Mempelajari perancangan sistem informasi digital perusahaan, ERP, dan integrasi teknologi dengan operasional bisnis.'],
                ['name' => 'D4 Teknologi Rekayasa Multimedia & Game', 'code' => 'A - I', 'desc' => 'Pendidikan vokasi pembuatan aset grafis 3D/2D, game engine (Unity/Unreal), dan perancangan interaktif.'],
                ['name' => 'S1 Rekayasa Keamanan Siber (Cybersecurity)', 'code' => 'I - R', 'desc' => 'Fokus pada mitigasi celah kerentanan kode, penetration testing aplikasi web, dan proteksi server dari serangan siber.'],
            ],
            'kerja' => [
                ['name' => 'Junior Web Backend & Frontend Developer', 'code' => 'I - R', 'desc' => 'Menulis dan merapikan kode program aplikasi web menggunakan framework modern (Laravel, React, atau Vue.js).'],
                ['name' => 'Junior Mobile Application Developer (Android/iOS)', 'code' => 'I - R', 'desc' => 'Mengembangkan aplikasi smartphone menggunakan Flutter, Kotlin, atau React Native di bawah supervisi senior.'],
                ['name' => 'Software QA Tester Pemula (Bug Hunter & Automation)', 'code' => 'I - C', 'desc' => 'Mencoba fitur aplikasi, mendokumentasikan error/bug, dan menguji integrasi API berdasarkan SOP pengujian.'],
                ['name' => 'Staf Technical IT Support & Helpdesk Software', 'code' => 'C - I', 'desc' => 'Membantu karyawan memecahkan masalah sistem operasi, instalasi aplikasi kantor, dan konfigurasi database internal.'],
                ['name' => 'Junior Database Administrator & Data Entry IT', 'code' => 'C - I', 'desc' => 'Melakukan backup basis data SQL, optimasi query sederhana, dan menjaga integritas data transaksi perusahaan.'],
                ['name' => 'Junior UI/UX Designer & Prototyper Aplikasi', 'code' => 'A - I', 'desc' => 'Merancang wireframe, user flow interaktif, dan purwarupa tampilan aplikasi di software Figma sebelum dicoding.'],
            ],
            'wirausaha' => [
                ['name' => 'Mendirikan Software House Mandiri (Jasa Web, Apps, & POS)', 'code' => 'I - E', 'desc' => 'Menyediakan layanan pembuatan website profil bisnis, toko online terintegrasi, dan software kasir UMKM.'],
                ['name' => 'Membangun Studio Pembuat Game Independen Skala Mikro', 'code' => 'A - E', 'desc' => 'Memproduksi dan memonetisasi gim orisinal untuk pasar mobile (Google Play) dan platform gim PC.'],
                ['name' => 'Jasa Pembuatan Landing Page & Website Portofolio Kilat', 'code' => 'E - I', 'desc' => 'Membantu praktisi profesional dan pemilik brand membangun situs profil resmi yang cepat dan responsif.'],
                ['name' => 'Layanan Otomasi Chatbot WhatsApp & Integrasi Sistem Toko Online', 'code' => 'I - C', 'desc' => 'Membangun sistem balasan otomatis cerdas untuk toko online guna mempercepat layanan pelanggan 24 jam.'],
            ],
        ],

        'TJKT' => [
            'name' => 'Teknik Jaringan Komputer & Telekomunikasi',
            'short_name' => 'TJKT (TKJ)',
            'code' => 'TKJ',
            'core_dimensions' => ['R', 'I', 'C'],
            'kuliah' => [
                ['name' => 'S1 Teknik Komputer', 'code' => 'R - I', 'desc' => 'Mempelajari rekayasa perangkat keras, telekomunikasi modern, arsitektur server, dan telematika.'],
                ['name' => 'D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'code' => 'R - C', 'desc' => 'Pendidikan vokasi pada perancangan jaringan fiber optik, transmisi seluler, dan routing switching enterprise.'],
                ['name' => 'S1 Keamanan Siber (Cybersecurity)', 'code' => 'I - R', 'desc' => 'Mengembangkan benteng pertahanan digital, investigasi penetrasi jaringan, dan proteksi server dari ancaman siber.'],
                ['name' => 'D4 Teknologi Rekayasa Komputer Jaringan & Cloud', 'code' => 'R - C', 'desc' => 'Fokus vokasi infrastruktur komputasi awan (AWS/GCP), virtualisasi server, dan automasi jaringan data center.'],
                ['name' => 'S1 Teknik Telekomunikasi & Nirkabel', 'code' => 'I - R', 'desc' => 'Mempelajari propagasi gelombang radio, arsitektur 5G/6G, komunikasi satelit, dan internet broadband.'],
                ['name' => 'D3 Teknik Komputer & Sistem Pemeliharaan IT', 'code' => 'R - I', 'desc' => 'Pendidikan terapan perbaikan perangkat keras komputer, setting periferal kantor, dan jaringan komputer lokal.'],
            ],
            'kerja' => [
                ['name' => 'Teknisi Lapangan / Field Engineer ISP & Fiber Optik', 'code' => 'R - I', 'desc' => 'Pekerjaan taktis memasang kabel jaringan internet fiber optik, splicing FO, dan setting router Wi-Fi pelanggan.'],
                ['name' => 'Junior Network Administrator Perusahaan', 'code' => 'R - C', 'desc' => 'Mengawasi stabilitas server, konfigurasi mikrotik router, bandwidth management, dan jaringan kantor.'],
                ['name' => 'Teknisi Instalasi & Pemeliharaan CCTV / Smart Home IoT', 'code' => 'R - I', 'desc' => 'Memasang dan mengonfigurasi kamera IP CAM keamanan, DVR/NVR, serta sensor otomatisasi pintar.'],
                ['name' => 'Staf IT Helpdesk Perangkat Keras & Jaringan Kantor', 'code' => 'R - S', 'desc' => 'Menangani keluhan teknis komputer pertama dari karyawan perusahaan dan troubleshoot konektivitas LAN.'],
                ['name' => 'NOC (Network Operation Center) Monitoring Operator', 'code' => 'C - R', 'desc' => 'Memantau indikator status server dan link transmisi jaringan 24/7 untuk mencegah terjadinya downtime.'],
                ['name' => 'Teknisi Servis Hardware Komputer & Laptop di Service Center', 'code' => 'R - C', 'desc' => 'Mendiagnosis kerusakan komponen motherboard, instalasi OS, dan penggantian suku cadang komputer.'],
            ],
            'wirausaha' => [
                ['name' => 'Penyedia Jasa Instalasi Jaringan Internet Kantor / RT-RW Net', 'code' => 'R - E', 'desc' => 'Menyediakan layanan instalasi WiFi terkelola, penarikan kabel fiber optik, dan pemeliharaan jaringan desa.'],
                ['name' => 'Toko Retail & Grosir Perangkat Keras Jaringan (Mikrotik & WiFi)', 'code' => 'C - E', 'desc' => 'Menjual perlengkapan networking, access point, switch, kabel UTP, dan komponen pendukung IT.'],
                ['name' => 'Bengkel Servis Komputer, Laptop & Rakit PC Gaming Spesialis', 'code' => 'R - I', 'desc' => 'Membuka gerai perbaikan komputer kilat, upgrade SSD/RAM, dan perakitan PC spesifikasi gaming/desain.'],
                ['name' => 'Jasa Pemasangan & Paket Pengawasan CCTV untuk Toko & Perumahan', 'code' => 'R - E', 'desc' => 'Menyediakan paket pemasangan kamera CCTV lengkap dengan monitoring streaming via aplikasi smartphone.'],
            ],
        ],

        'Pemasaran' => [
            'name' => 'Pemasaran',
            'short_name' => 'Pemasaran (Bisnis Ritel / BR)',
            'code' => 'BR',
            'core_dimensions' => ['E', 'A', 'S'],
            'kuliah' => [
                ['name' => 'S1 Bisnis Digital & E-Commerce', 'code' => 'E - I', 'desc' => 'Memadukan strategi pemasaran modern dengan ekosistem teknologi internet, marketplace daring, dan analisis data transaksi.'],
                ['name' => 'S1 Manajemen Pemasaran & Periklanan', 'code' => 'E - A', 'desc' => 'Mendalami seni promosi persuasif, perilaku konsumen, riset tren pasar global, dan manajemen merek korporat.'],
                ['name' => 'D4 Pemasaran Digital & Manajemen Ritel Vokasi', 'code' => 'E - S', 'desc' => 'Pendidikan vokasi pada praktik live-stream commerce, manajemen promosi media sosial, dan negosiasi ritel.'],
                ['name' => 'S1 Manajemen Ritel & Rantai Pasok Konsumen', 'code' => 'E - C', 'desc' => 'Mempelajari tata kelola toko ritel modern, visual merchandising, manajemen persediaan, dan kepuasan pelanggan.'],
                ['name' => 'S1 Ilmu Komunikasi Pemasaran & Hubungan Masyarakat', 'code' => 'S - E', 'desc' => 'Mempelajari komunikasi strategis publik, pembentukan reputasi produk, kampanye viral, dan media relations.'],
                ['name' => 'D3 Manajemen Perdagangan & Distribusi Produk', 'code' => 'E - C', 'desc' => 'Pendidikan vokasi pada tata niaga barang dagangan, saluran distribusi produk konsumen, dan salesmanship.'],
            ],
            'kerja' => [
                ['name' => 'Social Media Admin & Content Care Toko Online', 'code' => 'E - I', 'desc' => 'Mengelola pesan masuk, membalas komentar, dan posting konten harian interaktif di akun media sosial toko/bisnis.'],
                ['name' => 'Live Streamer Jualan Kreatif (TikTok / Shopee Live)', 'code' => 'E - A', 'desc' => 'Menjadi pembawa acara siaran langsung interaktif untuk mempromosikan dan menjual produk secara persuasif.'],
                ['name' => 'Junior Sales / Merchant Acquisition Specialist Lapangan', 'code' => 'I - E', 'desc' => 'Membantu mengenalkan dan mendaftarkan mitra pedagang baru ke dalam ekosistem aplikasi/platform perbankan.'],
                ['name' => 'Digital Marketing Specialist & Pengelola Iklan Ads', 'code' => 'E - I', 'desc' => 'Merancang dan memantau performa kampanye iklan digital berbayar di platform Meta Ads, Google Ads, dan TikTok Ads.'],
                ['name' => 'Store Supervisor / Asisten Kepala Toko Ritel Modern', 'code' => 'E - S', 'desc' => 'Memimpin tim kasir dan pramuniaga toko, memantau pencapaian target omzet harian, dan menjaga standar display barang.'],
                ['name' => 'Staf Visual Merchandising (VM) & Penataan Display Ritel', 'code' => 'A - E', 'desc' => 'Mengatur tata letak produk di rak toko, pencahayaan pajangan, dan tema display berkala untuk menarik minat beli.'],
            ],
            'wirausaha' => [
                ['name' => 'Owner Bisnis E-Commerce & Dropshipper Brand Sendiri', 'code' => 'E - S', 'desc' => 'Membangun merek produk konsumen sendiri dengan pemasaran langsung ke pengguna melalui marketplace.'],
                ['name' => 'Perintis Digital Marketing & Content Agency Skala UMKM', 'code' => 'E - A', 'desc' => 'Menyediakan jasa foto produk profesional, pengelolaan akun media sosial, dan penayangan iklan bagi pedagang lokal.'],
                ['name' => 'Minimarket Lingkungan Mandiri dengan Kasir Digital QRIS', 'code' => 'E - C', 'desc' => 'Membuka toko kelontong modern yang menjual sembako lengkap dengan barcode scanner dan pembayaran non-tunai.'],
                ['name' => 'Distributor / Agen Pemasok Perlengkapan Kemasan & Produk Konsumen', 'code' => 'E - S', 'desc' => 'Menjadi pemasok botol kemasan, kardus packing, dan perlengkapan e-commerce bagi ratusan seller online sekitar.'],
            ],
        ],

        'Manajemen Perkantoran' => [
            'name' => 'Manajemen Perkantoran & Layanan Bisnis',
            'short_name' => 'Manajemen Perkantoran (MPLB)',
            'code' => 'MPLB',
            'core_dimensions' => ['C', 'R', 'E'],
            'kuliah' => [
                ['name' => 'S1 Administrasi Bisnis Digital', 'code' => 'C - E', 'desc' => 'Menyiapkan tata kelola operasional kantor modern berbasis sistem komputasi terpadu dan efisiensi kerja korporat.'],
                ['name' => 'S1 Manajemen Sumber Daya Manusia (SDM & HRD)', 'code' => 'S - E', 'desc' => 'Mempelajari pengelolaan rekrutmen karyawan, pembinaan hubungan industrial, kompensasi, dan budaya kerja.'],
                ['name' => 'D4 Administrasi Perkantoran & Sekretaris Eksekutif', 'code' => 'C - I', 'desc' => 'Vokasi pengelolaan basis data arsip korporat, dokumentasi legalitas, otomasi korespondensi, dan protokoler rapat.'],
                ['name' => 'S1 Manajemen Logistik & Tata Kelola Pengadaan (Procurement)', 'code' => 'C - R', 'desc' => 'Mempelajari administrasi pencatatan inventaris gudang, alur dokumen tender pengadaan barang, dan rantai pasok.'],
                ['name' => 'D4 Manajemen Informasi Kesehatan / Rekam Medis Digital', 'code' => 'C - I', 'desc' => 'Vokasi pengelolaan kerahasiaan data rekam medis pasien rumah sakit, kodifikasi penyakit, dan arsip digital klinik.'],
                ['name' => 'D3 Kesekretariatan & Humas Perkantoran', 'code' => 'C - S', 'desc' => 'Pendidikan vokasi tata naskah dinas, penataan agenda pimpinan, dan pelayanan komunikasi tamu dinas resmi.'],
            ],
            'kerja' => [
                ['name' => 'Staf Admin Kantor / Data Entry Clerk Teknis', 'code' => 'C - S', 'desc' => 'Mengetik dokumen resmi, memasukkan data transaksi pelanggan, dan mengarsipkan berkas digital secara teratur.'],
                ['name' => 'Resepsionis & Front Office Staff Perusahaan', 'code' => 'S - C', 'desc' => 'Menyambut tamu kantor dengan etika profesional, menerima telepon masuk, dan mengurus surat masuk/keluar.'],
                ['name' => 'Junior Customer Service Admin Media Sosial & Chat', 'code' => 'C - R', 'desc' => 'Menjawab pesan keluhan dari pelanggan lewat sistem ticketing atau WhatsApp Bisnis dengan responsif dan solutif.'],
                ['name' => 'Staf Administrasi HRD & Penggajian Karyawan (Payroll Assistant)', 'code' => 'C - E', 'desc' => 'Merekapitulasi absensi finger scan, menghitung uang lembur, dan menyiapkan dokumen kontrak kerja karyawan.'],
                ['name' => 'Staf Administrasi Pergudangan & Stock Opname (Inventory Clerk)', 'code' => 'C - R', 'desc' => 'Mencatat mutasi keluar masuk barang di gudang dan mencocokkan jumlah stok fisik dengan sistem komputer.'],
                ['name' => 'Sekretaris Junior / Personal Assistant Pimpinan Cabang', 'code' => 'C - E', 'desc' => 'Mengatur jadwal rapat pimpinan, membuat notula pertemuan resmi, dan menyiapkan tiket perjalanan dinas.'],
            ],
            'wirausaha' => [
                ['name' => 'Agensi Penyedia Jasa Virtual Assistant Mandiri', 'code' => 'C - E', 'desc' => 'Membuka agensi asisten administrasi online yang melayani pebisnis daring dan pengusaha luar negeri.'],
                ['name' => 'Jasa Pengelolaan Administrasi & Ketikan Outsource Khusus UMKM', 'code' => 'C - S', 'desc' => 'Membantu pelaku usaha mikro mengelola surat perizinan, laporan operasional, dan arsip digital secara profesional.'],
                ['name' => 'Biro Jasa Pengurusan Perizinan Usaha, NIB, & NPWP Online', 'code' => 'C - E', 'desc' => 'Membantu pengusaha pemula membuat Nomor Induk Berusaha lewat sistem OSS, pendaftaran BPJS, dan sertifikat usaha.'],
                ['name' => 'Usaha Percetakan Nota Kontan, Blanko Surat, & Stempel Kilat', 'code' => 'C - R', 'desc' => 'Memproduksi buku nota kontan berporporasi, kop surat instansi, kwitansi pembayaran, dan stempel dinas.'],
            ],
        ],

        'Akuntansi Keuangan Lembaga' => [
            'name' => 'Akuntansi Keuangan Lembaga',
            'short_name' => 'Akuntansi Keuangan Lembaga (AKL)',
            'code' => 'AKL',
            'core_dimensions' => ['C', 'I', 'E'],
            'kuliah' => [
                ['name' => 'S1 Akuntansi Bisnis Digital & FinTech', 'code' => 'C - I', 'desc' => 'Mendalami penyusunan laporan keuangan digital, analisis investasi, pelaporan IFRS, dan teknologi finansial modern.'],
                ['name' => 'D4 Akuntansi Perpajakan Sektor Publik & Korporat', 'code' => 'C - E', 'desc' => 'Pendidikan vokasi spesialis perancangan laporan pajak perusahaan, pemeriksaan fiskal, dan audit perpajakan terapan.'],
                ['name' => 'S1 Sistem Informasi Akuntansi & Audit Komputer', 'code' => 'C - R', 'desc' => 'Mempelajari perancangan sistem software akuntansi, audit forensik berbasis sistem IT, dan pengolahan database keuangan.'],
                ['name' => 'D4 Perbankan Syariah & Keuangan Terapan', 'code' => 'C - S', 'desc' => 'Pendidikan vokasi operasional perbankan syariah, manajemen pembiayaan nasabah, dan kepatuhan regulasi OJK.'],
                ['name' => 'S1 Manajemen Keuangan & Pasar Modal', 'code' => 'C - E', 'desc' => 'Mempelajari analisis portofolio saham, valuasi aset investasi, manajemen permodalan, dan perencanaan keuangan korporat.'],
                ['name' => 'D3 Komputerisasi Akuntansi & Software Bisnis', 'code' => 'C - I', 'desc' => 'Pendidikan praktis pengoperasian software akuntansi Accurate, MYOB, SAP ERP, dan spreadsheet database.'],
            ],
            'kerja' => [
                ['name' => 'Junior Bookkeeper / Staf Pembukuan Keuangan Harian', 'code' => 'C - I', 'desc' => 'Mencatat transaksi keuangan kas kecil, mencocokkan rekening koran bank, dan menyusun jurnal umum harian.'],
                ['name' => 'Staf Administrasi Pajak Pemula di Kantor Konsultan Pajak', 'code' => 'C - E', 'desc' => 'Membantu merapikan nota, validasi e-Faktur PPN, penyiapan bukti potong PPh 21/23, dan e-SPT tahunan.'],
                ['name' => 'Teller & Frontliner Perbankan (Program Magang Khusus SMK)', 'code' => 'C - S', 'desc' => 'Melayani setoran uang tunai, penarikan tabungan nasabah, dan transfer perbankan resmi (BCA/Mandiri/BRI).'],
                ['name' => 'Asisten Auditor Junior di Kantor Akuntan Publik (KAP)', 'code' => 'C - I', 'desc' => 'Melakukan sampling bukti fisik transaksi, pengecekan vouching kuitansi, dan verifikasi saldo kas perusahaan klien.'],
                ['name' => 'Staf Penagihan & Piutang Usaha (Account Receivable / AR Clerk)', 'code' => 'C - E', 'desc' => 'Membuat dan mengirimkan invoice penagihan ke pelanggan serta memantau jadwal jatuh tempo pembayaran.'],
                ['name' => 'Staf Kasir Perusahaan & Pengelola Kas Kecil (Petty Cashier)', 'code' => 'C - R', 'desc' => 'Mengelola uang kas kecil untuk kebutuhan belanja operasional harian kantor dan membuat laporan pertanggungjawaban.'],
            ],
            'wirausaha' => [
                ['name' => 'Kantor Jasa Pembukuan & Akuntansi Berbasis Cloud untuk Toko Retail', 'code' => 'C - E', 'desc' => 'Mendirikan usaha layanan pencatatan kas harian dan penyusunan laporan laba rugi bulanan untuk pedagang UMKM.'],
                ['name' => 'Agen Mandiri Layanan Perbankan & Transaksi Digital Keuangan (BRILink / Agen Mandiri)', 'code' => 'C - S', 'desc' => 'Mengelola loket resmi transaksi transfer uang, tarik tunai tanpa kartu, pembayaran tagihan listrik, dan cicilan.'],
                ['name' => 'Jasa Konsultasi Perhitungan & Pelaporan SPT Tahunan Pajak UMKM', 'code' => 'C - I', 'desc' => 'Membantu pemilik toko dan wajib pajak orang pribadi menyusun laporan omzet dan pelaporan SPT tahunan secara sah.'],
                ['name' => 'Toko Perlengkapan Alat Tulis Kantor, Kertas Kasir, & Perlengkapan Pembukuan', 'code' => 'C - E', 'desc' => 'Menjual kertas struk thermal kasir, kalkulator, ordner arsip, dan perlengkapan administrasi toko ritel.'],
            ],
        ],

        'Usaha Layanan Wisata' => [
            'name' => 'Usaha Layanan Wisata',
            'short_name' => 'Usaha Layanan Wisata (ULW)',
            'code' => 'ULW',
            'core_dimensions' => ['S', 'E', 'A'],
            'kuliah' => [
                ['name' => 'S1 Destinasi Pariwisata Digital & Ekowisata', 'code' => 'S - E', 'desc' => 'Mempelajari tata kelola daya tarik wisata alam/budaya, promosi pariwisata digital, dan konservasi destinasi.'],
                ['name' => 'D4 Manajemen Bisnis Perjalanan Wisata (MICE & Corporate Event)', 'code' => 'S - C', 'desc' => 'Vokasi perencanaan tur penerbangan dunia, negosiasi rekanan maskapai/hotel, dan konvensi pameran wisata.'],
                ['name' => 'S1 Hubungan Masyarakat Pariwisata & Komunikasi Budaya', 'code' => 'S - A', 'desc' => 'Mendalami etika keprotokoleran, kehumasan destinasi wisata, media relations, dan public speaking kepariwisataan.'],
                ['name' => 'D4 Pengelolaan Konvensi & Perhelatan Acara (Event Management)', 'code' => 'E - S', 'desc' => 'Pendidikan vokasi perencanaan festival seni, pameran expo industri, dan seminar korporat bertaraf internasional.'],
                ['name' => 'S1 Manajemen Perjalanan Wisata Bahari & Maritim', 'code' => 'R - S', 'desc' => 'Mempelajari tata kelola wisata pesisir pantai, tur kepulauan, watersport, dan konservasi bahari.'],
                ['name' => 'D3 Usaha Perjalanan Wisata & Tour Operations', 'code' => 'S - C', 'desc' => 'Pendidikan praktis pemesanan tiket penerbangan GDS, penentuan rute wisata efisien, dan kepemanduan grup.'],
            ],
            'kerja' => [
                ['name' => 'Junior Tour Guide Lokal & Pemandu Budaya Berlisensi', 'code' => 'S - E', 'desc' => 'Memandu wisatawan di objek wisata sejarah/alam daerah dan menjelaskan nilai kearifan lokal secara menarik.'],
                ['name' => 'Staf Ticketing & Reservasi Sistem Global (Amadeus/Sabre)', 'code' => 'C - S', 'desc' => 'Membantu memesankan tiket penerbangan dan voucher kamar hotel lewat sistem reservasi online terpadu.'],
                ['name' => 'Tour Leader Pendamping Wisata Rombongan Studi Tur & Instansi', 'code' => 'S - E', 'desc' => 'Mendampingi kelompok wisatawan sepanjang perjalanan dari bandara asal hingga kembali dengan aman dan ceria.'],
                ['name' => 'Airport Representative / Staf Penyambut Wisatawan Bandara', 'code' => 'S - C', 'desc' => 'Menyambut kedatangan tamu turis di bandara, mengatur bagasi bawaan, dan mengoordinasikan penjemputan armada bus.'],
                ['name' => 'Staf Event Organizer Outbound & Teambuilding Perusahaan', 'code' => 'R - S', 'desc' => 'Mengoperasikan peralatan games outbound, memimpin yel-yel kebersamaan, dan mengurus logistik acara wisata.'],
                ['name' => 'Staf Customer Service & Penjualan Paket Wisata Biro Travel', 'code' => 'S - E', 'desc' => 'Menjelaskan jadwal itinerary dan rincian harga paket liburan kepada calon konsumen yang berkunjung ke kantor.'],
            ],
            'wirausaha' => [
                ['name' => 'Perintis Biro Open Trip Online Wisata Alternatif & Glamping', 'code' => 'E - S', 'desc' => 'Membuka usaha perjalanan wisata kelompok kecil ke destinasi alam tersembunyi, camping, dan glamping privat.'],
                ['name' => 'Agensi Konten Dokumentasi Video Kreatif Promosi Wisata & Travel Vlogger', 'code' => 'A - E', 'desc' => 'Menyediakan layanan pembuatan video promosi destinasi wisata dinas daerah dan dokumentasi perjalanan turis.'],
                ['name' => 'Jasa Pemandu Jelajah Alam & Sewa Perlengkapan Camping Lengkap', 'code' => 'R - S', 'desc' => 'Menyewakan tenda dome tahan badai, matras, kompor portabel, dan pemandu hiking rute gunung lokal.'],
                ['name' => 'Rental Mobil Wisata & Armada Shuttle Elf Wisatawan Liburan', 'code' => 'E - R', 'desc' => 'Menyediakan jasa sewa kendaraan pariwisata bersih beserta driver profesional berpengalaman ke destinasi favorit.'],
            ],
        ],

        'Perhotelan' => [
            'name' => 'Perhotelan',
            'short_name' => 'Perhotelan (PH)',
            'code' => 'PH',
            'core_dimensions' => ['S', 'R', 'E'],
            'kuliah' => [
                ['name' => 'D4 Manajemen Perhotelan & Pengelolaan Resor', 'code' => 'S - R', 'desc' => 'Pendidikan vokasi pengelolaan divisi kamar, operasional front office, dan sanitasi komersial hotel berbintang.'],
                ['name' => 'S1 Bisnis Perhotelan & Manajemen Properti Investasi', 'code' => 'S - E', 'desc' => 'Mempelajari tata kelola resort mewah, manajemen pendapatan kamar (revenue management), dan ekspansi jaringan hotel.'],
                ['name' => 'D4 Manajemen Operasional Divisi Kamar (Rooms Division)', 'code' => 'R - S', 'desc' => 'Vokasi spesialis tata graha hotel, laundry komersial, public area, dan pemeliharaan kenyamanan interior kamar.'],
                ['name' => 'D4 Manajemen Tata Hidang & Seni Minuman (Food & Beverage Service)', 'code' => 'S - A', 'desc' => 'Mempelajari operasional restoran hotel, seni mixology mocktail/cocktail, perjamuan banquet, dan table manner.'],
                ['name' => 'S1 Pariwisata Hospitaliti & Manajemen Pelayanan Prima', 'code' => 'S - E', 'desc' => 'Mendalami strategi kepuasan tamu premium, loyalitas pelanggan hotel internasional, dan standarisasi bintang 5.'],
                ['name' => 'D3 Perhotelan Terapan & Pengelolaan Akomodasi', 'code' => 'R - S', 'desc' => 'Pendidikan praktis kesiapan kerja cepat pada departemen front desk, concierge, dan tata graha hotel.'],
            ],
            'kerja' => [
                ['name' => 'Room Attendant / Housekeeping Staff Hotel Berbintang', 'code' => 'R - S', 'desc' => 'Membersihkan, menata ranjang make-up room, dan merawat kelayakan sarana kamar hotel sesuai standar bintang 5.'],
                ['name' => 'Front Desk Agent Trainee / Resepsionis Hotel', 'code' => 'S - C', 'desc' => 'Melayani proses check-in/out tamu di meja lobi, membuat kunci kartu kamar elektronik, dan pembayaran tagihan.'],
                ['name' => 'Bellboy & Concierge Penyambut Tamu Hotel', 'code' => 'S - R', 'desc' => 'Menyambut tamu hotel di pintu masuk, membawakan koper bawaan ke kamar, dan membantu pemesanan taksi.'],
                ['name' => 'Pramusaji Restoran Hotel (Food & Beverage Service Staff)', 'code' => 'S - R', 'desc' => 'Menyajikan hidangan sarapan buffet, melayani pesanan room service ke kamar tamu, dan membersihkan meja makan.'],
                ['name' => 'Banquet Service Coordinator untuk Acara Pesta & Rapat di Ballroom', 'code' => 'S - E', 'desc' => 'Mempersiapkan penataan meja rapat, sound system, dan jamuan makan prasmanan acara pertemuan di ballroom hotel.'],
                ['name' => 'Telephone Operator & Guest Communication Center Hotel', 'code' => 'C - S', 'desc' => 'Menjawab panggilan telepon masuk dari luar maupun dari kamar tamu hotel dan meneruskannya ke bagian terkait.'],
            ],
            'wirausaha' => [
                ['name' => 'Pengelola Bisnis Penginapan Homestay & Kost Eksklusif via AirBnB', 'code' => 'E - S', 'desc' => 'Mengelola unit rumah/kamar homestay ramah turis dengan pelayanan hangat khas lokal dan review bintang 5.'],
                ['name' => 'Jasa Layanan Kebersihan Properti (Cleaning Service) Panggilan Kantor & Rumah', 'code' => 'R - E', 'desc' => 'Menyediakan jasa pembersihan kamar kos, cuci sofa tungau, dan pembersihan rumah pasca pesta atau renovasi.'],
                ['name' => 'Usaha Laundry Kiloan & Dry Clean Berstandar Hotelier', 'code' => 'R - C', 'desc' => 'Membuka layanan cuci setrika uap, pencucian jas formal, dan cuci sprei bedcover wangi higienis.'],
                ['name' => 'Penyedia Jasa Tenaga Layanan Resepsionis & Waiter Acara Pesta Pernikahan', 'code' => 'S - E', 'desc' => 'Menyediakan tim pramusaji dan penerima tamu profesional terlatih untuk perhelatan jamuan resepsi pernikahan.'],
            ],
        ],

        'Kuliner' => [
            'name' => 'Kuliner',
            'short_name' => 'Kuliner (Tata Boga / KL)',
            'code' => 'KL',
            'core_dimensions' => ['R', 'A', 'E'],
            'kuliah' => [
                ['name' => 'D4 Manajemen Kuliner / Seni Pengolahan Masakan', 'code' => 'R - A', 'desc' => 'Vokasi teknik pengolahan masakan barat/nusantara, higiene sanitasi, HACCP, dan operasional dapur restoran.'],
                ['name' => 'S1 Seni Kuliner & Gastronomi Terapan', 'code' => 'A - R', 'desc' => 'Mendalami estetika penyajian hidangan (food plating), kreasi resep orisinal modern, dan eksplorasi rasa gastronomi.'],
                ['name' => 'S1 Teknologi Pangan Kuliner & Pengendalian Mutu', 'code' => 'I - R', 'desc' => 'Mempelajari pengawetan makanan higienis, uji gizi laboratorium, umur simpan produk, dan sertifikasi Halal.'],
                ['name' => 'D4 Seni Roti, Pastry, & Bakery Komersial', 'code' => 'A - R', 'desc' => 'Pendidikan vokasi teknik pembuatan aneka roti artisan Eropa, laminated dough croissant, dan kue modern.'],
                ['name' => 'S1 Manajemen Bisnis Kuliner & Restoran Waralaba', 'code' => 'E - R', 'desc' => 'Mempelajari kelayakan finansial usaha makanan, standardisasi resep waralaba, dan ekspansi cabang gerai kuliner.'],
                ['name' => 'D3 Tata Boga & Operasional Restoran', 'code' => 'R - C', 'desc' => 'Pendidikan praktis pengolahan hidangan cepat saji, pemotongan daging/sayur, dan sistem pergudangan bahan dapur.'],
            ],
            'kerja' => [
                ['name' => 'Commis Chef / Cook Helper (Asisten Juru Masak Dapur Profesional)', 'code' => 'R - A', 'desc' => 'Membantu menyiapkan bahan kuliner mentah, memotong sayur/daging, dan memasak masakan standar di kitchen line.'],
                ['name' => 'Pastry Cook & Baker Pembuat Roti di Toko Bakery Modern', 'code' => 'A - R', 'desc' => 'Membantu proses pengadukan adonan, fermentasi roti, dan pemanggangan pastry berstandar toko bakery.'],
                ['name' => 'Barista Kopi Spesialis & Peracik Minuman Kafe Modern', 'code' => 'S - R', 'desc' => 'Mengoperasikan mesin espresso, membuat seni latte art, dan meracik minuman kopi serta mocktail kekinian.'],
                ['name' => 'Food Stylist & Penata Hidangan Pemotretan Komersial', 'code' => 'A - R', 'desc' => 'Menata tampilan makanan di atas piring agar terlihat menggugah selera untuk kebutuhan foto menu dan iklan.'],
                ['name' => 'Pramusaji / Waiter Restoran Fine Dining & Jamuan Resmi', 'code' => 'S - R', 'desc' => 'Melayani pencatatan pesanan menu, mengantarkan pesanan dengan ramah, dan menjaga kebersihan area makan tamu.'],
                ['name' => 'Staf Pengendalian Kualitas Pangan (QC Dapur & Bahan Baku)', 'code' => 'C - R', 'desc' => 'Memeriksa kesegaran sayuran, suhu penyimpanan daging beku di chiller, dan kepatuhan standar sanitasi dapur.'],
            ],
            'wirausaha' => [
                ['name' => 'Mendirikan Bisnis Kuliner Cloud Kitchen (Fokus Pesanan Online GoFood/GrabFood)', 'code' => 'E - R', 'desc' => 'Membuka gerai makanan lezat yang beroperasi tanpa ruang makan fisik dan berfokus pada pengantaran online.'],
                ['name' => 'Usaha Katering Harian Box Sehat & Bento Kantoran via Instagram', 'code' => 'R - E', 'desc' => 'Menyediakan katering kalori terhitung, menu bento higienis, dan katering pesta dengan kemasan ramah lingkungan.'],
                ['name' => 'Toko Roti Artisan, Donat Kentang Premium, & Kue Ulang Tahun Kustom', 'code' => 'A - E', 'desc' => 'Memproduksi donat empuk aneka topping menarik dan kue tart hias ulang tahun pesanan online rumahan.'],
                ['name' => 'Kedai Minuman Kopi Susu Gula Aren & Teh Buah Segar Estetik', 'code' => 'E - S', 'desc' => 'Membuka stan minuman segar kekinian di lokasi strategis dekat sekolah atau perkantoran dengan harga ramah kantong.'],
            ],
        ],

        'Desain Komunikasi Visual' => [
            'name' => 'Desain Komunikasi Visual',
            'short_name' => 'Desain Komunikasi Visual (DKV)',
            'code' => 'DKV',
            'core_dimensions' => ['A', 'R', 'S'],
            'kuliah' => [
                ['name' => 'S1 Desain Komunikasi Visual (DKV)', 'code' => 'A - R', 'desc' => 'Mempelajari komunikasi grafis, tipografi ekspresif, perancangan identitas visual brand, dan ilustrasi digital.'],
                ['name' => 'S1 Animasi, Film, & Efek Visual Digital (VFX)', 'code' => 'A - I', 'desc' => 'Mendalami perancangan karakter 2D/3D, sinematografi, visual effect, dan proses pasca produksi video.'],
                ['name' => 'D4 Desain Grafis & Teknologi Cetak Komersial', 'code' => 'A - C', 'desc' => 'Vokasi perancangan tata letak kemasan produk, separasi warna cetak offset, dan periklanan visual.'],
                ['name' => 'S1 Desain Media Interaktif, Game, & UI/UX Digital', 'code' => 'A - I', 'desc' => 'Mempelajari interaksi manusia dan komputer, arsitektur antarmuka aplikasi digital, dan visual game.'],
                ['name' => 'S1 Fotografi Komersial & Sinematografi Digital', 'code' => 'A - R', 'desc' => 'Mempelajari tata pencahayaan studio foto, pengoperasian kamera sinema, dan penyutradaraan video komersial.'],
                ['name' => 'D4 Teknologi Rekayasa Multimedia & Konten Kreatif', 'code' => 'I - A', 'desc' => 'Pendidikan vokasi integrasi grafis komputer, motion graphics, audio visual, dan broadcasting digital.'],
            ],
            'kerja' => [
                ['name' => 'Junior Graphic Designer Konten Media Sosial & Toko Online', 'code' => 'A - E', 'desc' => 'Mendesain materi promosi harian seperti feed Instagram, banner marketplace, pamflet, dan katalog digital.'],
                ['name' => 'Junior Video Editor Konten Pendek Reels/TikTok/YouTube', 'code' => 'A - R', 'desc' => 'Memotong footage video mentah, memilih background music dinamis, dan menambahkan transisi serta teks menarik.'],
                ['name' => 'Operator Pra-Cetak di Studio Digital Printing & Offset', 'code' => 'A - I', 'desc' => 'Membantu memeriksa kecocokan warna CMYK, layout imposing plat cetak, dan mengoperasikan mesin digital print.'],
                ['name' => 'Junior UI/UX Designer Tampilan Aplikasi Mobile & Website', 'code' => 'A - I', 'desc' => 'Mendesain antarmuka aplikasi di Figma yang mudah digunakan pengguna dan enak dipandang secara visual.'],
                ['name' => 'Fotografer Produk & Retoucher Foto Studio', 'code' => 'A - R', 'desc' => 'Mengambil foto produk UMKM di studio dan melakukan editing retouching warna menggunakan Adobe Photoshop.'],
                ['name' => 'Asisten Animator 2D & Motion Graphic Designer', 'code' => 'A - I', 'desc' => 'Membuat animasi logo bergerak, teks grafis dinamis, dan elemen animasi kartun untuk video explainer.'],
            ],
            'wirausaha' => [
                ['name' => 'Studio Desain Visual Kreatif / Jasa Branding & Logo Brand', 'code' => 'A - E', 'desc' => 'Membuka studio jasa perancangan identitas visual merek, maskot, kemasan makanan, dan kampanye digital.'],
                ['name' => 'Kreator Penjual Aset Grafis Digital di Pasar Dunia (Freepik, Shutterstock)', 'code' => 'A - C', 'desc' => 'Menjual karya ilustrasi vektor, template sosial media, dan aset visual 3D di platform pasar kreatif dunia berpenghasilan dolar.'],
                ['name' => 'Usaha Percetakan Sablon DTF Kaos Satuan & Merchandise Custom', 'code' => 'R - A', 'desc' => 'Menyediakan sablon kaos print satuan tanpa minimal order, cetak tote bag kanvas, mug, gantungan kunci, dan pin.'],
                ['name' => 'Jasa Dokumentasi Foto & Video Sinematik Pernikahan / Acara Khusus', 'code' => 'A - S', 'desc' => 'Menyediakan paket dokumentasi wisuda, pernikahan intimate, dan liputan acara korporat dengan visual sinematik.'],
            ],
        ],

        'Busana' => [
            'name' => 'Busana',
            'short_name' => 'Busana (Tata Busana / DPB)',
            'code' => 'DPB',
            'core_dimensions' => ['A', 'R', 'E'],
            'kuliah' => [
                ['name' => 'S1 Desain Mode & Tata Busana (Fashion Design)', 'code' => 'A - R', 'desc' => 'Mendalami perancangan koleksi mode adibusana, peramalan tren gaya dunia, dan inovasi siluet busana.'],
                ['name' => 'S1 Bisnis & Manajemen Retail Mode Busana', 'code' => 'E - A', 'desc' => 'Mempelajari strategi pemasaran merek pakaian, rantai pasok garmen industri, dan merchandising butik mode.'],
                ['name' => 'D4 Desain Busana Kustom & Teknologi Garmen', 'code' => 'R - A', 'desc' => 'Pendidikan vokasi pada pembuatan pola busana draping, rekayasa tekstil kain, dan teknik jahit butik halus.'],
                ['name' => 'S1 Kriya Tekstil & Desain Aksesoris Mode', 'code' => 'A - R', 'desc' => 'Mempelajari seni batik kontemporer, tenun kriya modern, pembuatan tas kulit, dan aksesoris perhiasan mode.'],
                ['name' => 'D4 Manajemen Produksi Busana Industri Garmen', 'code' => 'C - R', 'desc' => 'Vokasi pengelolaan efisiensi lini jahit pabrik garmen, standardisasi cutting kain, dan audit mutu ekspor pakaian.'],
                ['name' => 'D3 Tata Busana & Pembuatan Pola Pakaian', 'code' => 'R - A', 'desc' => 'Pendidikan praktis menjahit pakaian wanita, teknik obras rapi, dan pembuatan busana kerja sehari-hari.'],
            ],
            'kerja' => [
                ['name' => 'Asisten Pembuat Pola Busana Digital & Manual (Pattern Maker)', 'code' => 'A - R', 'desc' => 'Menerjemahkan gambar sketsa busana menjadi pola potongan kain fisik/digital yang presisi dan pas di badan.'],
                ['name' => 'Operator Jahit Sampel Busana (Sample Sewer) di Butik/Garmen', 'code' => 'R - I', 'desc' => 'Menjahit sampel busana baru dengan kerapian tusuk jarum tinggi sebagai acuan standar produksi massal.'],
                ['name' => 'Junior Wardrobe Assistant & Kru Penata Busana Pemotretan Studio', 'code' => 'A - E', 'desc' => 'Membantu menyiapkan setelan pakaian model, menyetrika uap busana, dan mengoordinasikan busana di studio.'],
                ['name' => 'Staf Pengendali Mutu Jahitan Pakaian (Quality Control Garmen)', 'code' => 'C - R', 'desc' => 'Memeriksa kesesuaian ukuran baju dengan spesifikasi size chart, memotong sisa benang jahit, dan cek resleting.'],
                ['name' => 'Asisten Perancang Busana (Fashion Designer Assistant)', 'code' => 'A - E', 'desc' => 'Membantu desainer senior membuat moodboard inspirasi warna, memilih sampel kain, dan membuat sketsa teknis.'],
                ['name' => 'Visual Merchandiser Toko Busana / Fashion Brand Associate', 'code' => 'A - S', 'desc' => 'Mendandani patung manekin di etalase toko pakaian agar tampak modis dan menarik minat pengunjung mall.'],
            ],
            'wirausaha' => [
                ['name' => 'Merintis Brand Pakaian Ready-to-Wear / Baju Muslimah Sendiri via E-Commerce', 'code' => 'E - A', 'desc' => 'Memproduksi pakaian gamis modern, tunik kasual, atau streetwear dengan merek sendiri dan dipasarkan online.'],
                ['name' => 'Rumah Jahit Kustom Busana Pesta, Kebaya Wisuda, & Gaun Pengantin', 'code' => 'R - E', 'desc' => 'Menerima pesanan jahit baju kebaya wisuda pas badan, payet mutiara estetik, dan gaun formal berstandar butik.'],
                ['name' => 'Jasa Permak Pakaian Kilat & Modifikasi Busana Modern di Ruko', 'code' => 'R - C', 'desc' => 'Menyediakan layanan potong celana jeans rapi, ganti resleting jaket, dan kecilkan baju pas badan dengan cepat.'],
                ['name' => 'Usaha Aksesoris Fashion Kustom (Hijab Printing Voal & Scrunchie Cantik)', 'code' => 'A - E', 'desc' => 'Memproduksi jilbab bermotif unik eksklusif, tas kanvas tote bag, dan aksesori rambut yang digemari remaja.'],
            ],
        ],
    ];

    /**
     * Constant label Jangkar Karier Edgar Schein.
     */
    public const ANCHOR_NAMES = [
        'TF' => 'Technical/Functional Competence (Keahlian Spesialis)',
        'GM' => 'General Manager Competence (Kepemimpinan Manajerial)',
        'AU' => 'Autonomy/Independence (Kemandirian & Kebebasan)',
        'SE' => 'Security/Stability (Kestabilan Gaji & Rasa Aman)',
        'EC' => 'Entrepreneurial Creativity (Kreativitas Bisnis Wirausaha)',
        'SV' => 'Service/Dedication to a Cause (Pengabdian & Dampak Sosial)',
        'CH' => 'Pure Challenge (Tantangan Murni & Riset Inovasi)',
        'LS' => 'Lifestyle (Keseimbangan Gaya Hidup & Work-Life Balance)',
    ];

    /**
     * Proses penyimpanan jawaban dan kalkulasi skor RIASEC + Career Anchors.
     *
     * @param User $user
     * @param array<int, int> $answers [question_id => score (1-5)]
     * @return CareerResult
     */
    public function processAndSaveAnswers(User $user, array $answers): CareerResult
    {
        return DB::transaction(function () use ($user, $answers) {
            // Ambil semua pertanyaan yang relevan
            $questions = CareerQuestion::whereIn('id', array_keys($answers))->get()->keyBy('id');

            $scores = [
                'R' => 0,
                'I' => 0,
                'A' => 0,
                'S' => 0,
                'E' => 0,
                'C' => 0,
            ];

            $counts = [
                'R' => 0,
                'I' => 0,
                'A' => 0,
                'S' => 0,
                'E' => 0,
                'C' => 0,
            ];

            $anchorScores = [
                'TF' => 0,
                'GM' => 0,
                'AU' => 0,
                'SE' => 0,
                'EC' => 0,
                'SV' => 0,
                'CH' => 0,
                'LS' => 0,
            ];

            // Simpan jawaban satu per satu dan jumlahkan skor per dimensi
            foreach ($answers as $qId => $score) {
                $score = max(1, min(5, (int) $score));
                CareerAnswer::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'question_id' => $qId,
                    ],
                    [
                        'score' => $score,
                    ]
                );

                if (isset($questions[$qId])) {
                    $q = $questions[$qId];
                    if ($q->section === 'career_anchor' && $q->type_anchor) {
                        $anchorKey = strtoupper($q->type_anchor);
                        if (isset($anchorScores[$anchorKey])) {
                            $anchorScores[$anchorKey] += $score;
                        }
                    } else {
                        $dim = strtoupper($q->type_riasec ?? 'R');
                        if (isset($scores[$dim])) {
                            $scores[$dim] += $score;
                            $counts[$dim]++;
                        }
                    }
                }
            }

            // Hitung Nilai Persentase untuk masing-masing dimensi RIASEC
            // R: /50, I: /25, A: /40, S: /40, E: /40, C: /45
            // Width (%) = (Skor Mentah / Skor Maksimal Dimensi) * 100
            $percentages = [];
            foreach ($scores as $dim => $sumScore) {
                $max = self::DIMENSION_MAX_SCORES[$dim] ?? 40;
                $percentages[$dim] = round(($sumScore / $max) * 100, 4);
            }

            // 1. Perhitungan RIASEC berbasis Nilai Persentase
            $riasecSorted = $percentages;
            arsort($riasecSorted);

            $topKeys = array_keys($riasecSorted);
            $primaryKey = $topKeys[0] ?? 'R';
            $secondaryKey = $topKeys[1] ?? 'I';
            $tertiaryKey = $topKeys[2] ?? 'A';

            $dominantType = self::DIMENSIONS[$primaryKey] ?? 'Realistic';
            $hollandCode = "{$primaryKey}-{$secondaryKey}-{$tertiaryKey}";
            $secondaryTypes = (self::DIMENSIONS[$secondaryKey] ?? '') . ', ' . (self::DIMENSIONS[$tertiaryKey] ?? '');

            // 2. Perhitungan Career Anchors (Schein)
            $anchorSorted = $anchorScores;
            arsort($anchorSorted);
            $anchorKeys = array_keys($anchorSorted);
            $dominantAnchor = $anchorKeys[0] ?? 'TF';
            $dominantAnchorName = self::ANCHOR_NAMES[$dominantAnchor] ?? 'Technical/Functional Competence';

            // 4. Deteksi Jurusan Asal Siswa & Sintesis Laporan Kolaborasi Matriks Gabungan (RIASEC + Career Anchors)
            $detectedMajor = $this->detectStudentMajor($user);
            $collaboration = $this->synthesizeCollaborationReport($user, $riasecSorted, $anchorSorted, $detectedMajor);
            $executionPath = $collaboration['execution_path'];

            // Simpan atau update ke tabel career_results
            return CareerResult::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'realistic_score' => $scores['R'],
                    'investigative_score' => $scores['I'],
                    'artistic_score' => $scores['A'],
                    'social_score' => $scores['S'],
                    'enterprising_score' => $scores['E'],
                    'conventional_score' => $scores['C'],
                    'dominant_type' => $dominantType,
                    'holland_code' => $hollandCode,
                    'secondary_types' => $secondaryTypes,

                    // Fields Career Anchors
                    'tf_score' => $anchorScores['TF'],
                    'gm_score' => $anchorScores['GM'],
                    'au_score' => $anchorScores['AU'],
                    'se_score' => $anchorScores['SE'],
                    'ec_score' => $anchorScores['EC'],
                    'sv_score' => $anchorScores['SV'],
                    'ch_score' => $anchorScores['CH'],
                    'ls_score' => $anchorScores['LS'],
                    'dominant_anchor' => $dominantAnchor,
                    'dominant_anchor_name' => $dominantAnchorName,
                    'recommended_execution_path' => $executionPath,
                    'anchor_recommendation_title' => $collaboration['anchor_recommendation_title'],
                    'collaboration_narrative' => json_encode($collaboration, JSON_UNESCAPED_UNICODE),
                    'completed_at' => Carbon::now(),
                ]
            );
        });
    }

    /**
     * Sintesis Laporan Kolaborasi (Matriks Poin Gabungan: RIASEC + Career Anchors Matrix)
     */
    public function synthesizeCollaborationReport(User $user, array $riasecScores, array $anchorScores, array $detectedMajor): array
    {
        $name = $user->name ?? 'Siswa';
        $cleanName = trim(preg_replace('/^(A\.|M\.|MOCH\.|MUH\.|MD\.)\s+/i', '', $name));
        $parts = explode(' ', $cleanName);
        $firstName = ucfirst(strtolower($parts[0] ?? $name));

        // Normalisasi skor RIASEC ke nilai persentase per dimensi: (Skor Mentah / Skor Maksimal) * 100
        $dimensionMaxScores = self::DIMENSION_MAX_SCORES;
        $riasecPercentages = [];
        foreach ($riasecScores as $dim => $scoreVal) {
            $max = $dimensionMaxScores[$dim] ?? 40;
            if ($scoreVal <= 5.0) {
                // Jika input berupa nilai rata-rata (skala 1-5)
                $riasecPercentages[$dim] = ($scoreVal / 5.0) * 100;
            } else {
                // Jika skor mentah (R max 50, I max 25, C max 45, dll)
                $riasecPercentages[$dim] = round(($scoreVal / $max) * 100, 4);
            }
        }

        // Ranking RIASEC & Anchors berbasis Nilai Persentase
        arsort($riasecPercentages);
        arsort($anchorScores);

        $riasecKeys = array_keys($riasecPercentages);
        $anchorKeys = array_keys($anchorScores);

        $pRiasec = $riasecKeys[0] ?? 'I';
        $sRiasec = $riasecKeys[1] ?? 'R';
        $tRiasec = $riasecKeys[2] ?? 'A';
        $hollandCode = "{$pRiasec}-{$sRiasec}-{$tRiasec}";
        $riasecName = self::DIMENSIONS[$pRiasec] ?? 'Investigative';

        $domAnchor = $anchorKeys[0] ?? 'AU';

        $majorCode = $detectedMajor['major_code'] ?? 'AKL';
        $majorName = $detectedMajor['major_name'] ?? 'Akuntansi Keuangan Lembaga';

        // ================= MATRIKS POIN GABUNGAN (CROSS DECISION MATRIX) =================
        // Rule 1: Investigative (I) -> KULIAH (Prioritas Utama, apapun anchor-nya)
        // Rule 2: Realistic (R) / Conventional (C) + Security (SE) / Lifestyle (LS) -> KERJA LANGSUNG
        // Rule 3: Enterprising (E) / Artistic (A) + Entrepreneurial (EC) / Autonomy (AU) -> BERWIRAUSAHA
        // Rule 4: Social (S) + Service (SV) / General Manager (GM) -> KULIAH / KERJA (Pelayanan/Edukasi)
        $executionPath = match (true) {
            $pRiasec === 'I' => 'kuliah',
            in_array($pRiasec, ['R', 'C']) && in_array($domAnchor, ['SE', 'LS']) => 'bekerja',
            in_array($pRiasec, ['E', 'A']) && in_array($domAnchor, ['EC', 'AU']) => 'berwirausaha',
            $pRiasec === 'S' && in_array($domAnchor, ['SV', 'GM']) => 'kuliah',
            in_array($domAnchor, ['TF', 'CH']) => 'kuliah',
            in_array($domAnchor, ['EC', 'AU']) => 'berwirausaha',
            in_array($domAnchor, ['SE', 'LS']) => 'bekerja',
            in_array($domAnchor, ['SV', 'GM']) => 'kuliah',
            default => 'kuliah',
        };

        $recommendationTitle = match (true) {
            $pRiasec === 'I' => '🎓 KULIAH (Prioritas Utama - Pengasahan Analisis & Riset)',
            in_array($pRiasec, ['R', 'C']) && in_array($domAnchor, ['SE', 'LS']) => '💼 KERJA LANGSUNG (Struktur Kerja & Kestabilan Kas)',
            in_array($pRiasec, ['E', 'A']) && in_array($domAnchor, ['EC', 'AU']) => '🚀 BERWIRAUSAHA (Kreativitas Bisnis & Kemandirian)',
            $pRiasec === 'S' && in_array($domAnchor, ['SV', 'GM']) => '🎓 KULIAH / 💼 KERJA (Sektor Pelayanan Publik & Edukasi)',
            default => match ($domAnchor) {
                'TF' => '🎓 KULIAH (Spesialisasi Akademik & Keahlian Pakar)',
                'GM' => '🚀 BERWIRAUSAHA / 💼 JALUR KARIR MANAJERIAL PEMULA',
                'AU' => '🚀 BERWIRAUSAHA DIGITAL / FREELANCER MANDIRI',
                'SE' => '💼 KERJA LANGSUNG (Perusahaan Bonafide / Mitra Kemitraan SMK)',
                'EC' => '🚀 BERWIRAUSAHA (Start-up / Bisnis Mandiri)',
                'SV' => '🎓 KULIAH / 💼 KERJA (Sektor Pelayanan & Edukasi)',
                'CH' => '🎓 KULIAH / 💼 KERJA (Riset, High-Tech, & Inovasi)',
                'LS' => '💼 KERJA (Remote Working / Fleksibel Digital)',
                default => '🎓 KULIAH / 💼 KERJA LANGSUNG',
            },
        };

        $reasonNarrative = match (true) {
            $pRiasec === 'I' =>
                "Karakter analisamu (Investigative / I) sangat kuat. Tipe kepribadian ini tidak bisa dipaksa langsung kerja teknis kasar; kamu membutuhkan pendidikan tinggi (Kuliah) untuk mengasah logika, metode penelitian, dan kemampuan pemecahan masalah kompleks sebelum terjun ke industri.",

            in_array($pRiasec, ['R', 'C']) && in_array($domAnchor, ['SE', 'LS']) =>
                "Karakter praktis dan terstruktur kamu ({$riasecName}) bertemu dengan motivasi mencari kestabilan & rasa aman (Jangkar " . (self::ANCHOR_NAMES[$domAnchor] ?? $domAnchor) . "). Jalur terbaikmu setelah lulus adalah KERJA LANGSUNG di perusahaan terpercaya.",

            in_array($pRiasec, ['E', 'A']) && in_array($domAnchor, ['EC', 'AU']) =>
                "Karakter bisnis dan kreatif kamu ({$riasecName}) bertemu dengan jiwa mandiri & inovatif (Jangkar " . (self::ANCHOR_NAMES[$domAnchor] ?? $domAnchor) . "). Jalur terbaikmu setelah lulus adalah merintis BERWIRAUSAHA mandiri.",

            $pRiasec === 'S' && in_array($domAnchor, ['SV', 'GM']) =>
                "Karakter sosial dan kepedulian kamu (Social) bertemu dengan motivasi pelayanan & manajerial (Jangkar " . (self::ANCHOR_NAMES[$domAnchor] ?? $domAnchor) . "). Jalur terbaikmu setelah lulus adalah KULIAH atau KERJA di sektor pelayanan publik, edukasi, atau customer success.",

            default =>
                "Rekomendasi disesuaikan dengan hasil kolaborasi poin gabungan minat RIASEC ({$hollandCode}) dan motivasi kerja utama kamu (" . (self::ANCHOR_NAMES[$domAnchor] ?? $domAnchor) . ")."
        };

        // Ide Wirausaha/Karier/Studi Terbaik (2-3 contoh ide konkret disesuaikan dengan jurusan & anchor)
        $ideas = $this->generateCollaborationIdeas($majorCode, $domAnchor, $pRiasec);

        return [
            'greeting' => "Halo {$firstName}! Karakter minatmu adalah {$riasecName} ({$hollandCode}) dan nilai jangkar kariermu adalah " . (self::ANCHOR_NAMES[$domAnchor] ?? $domAnchor) . ".",
            'execution_path' => $executionPath,
            'anchor_code' => $domAnchor,
            'anchor_name' => self::ANCHOR_NAMES[$domAnchor] ?? $domAnchor,
            'anchor_recommendation_title' => $recommendationTitle,
            'reason_narrative' => $reasonNarrative,
            'ideas' => $ideas,
        ];
    }

    /**
     * Ide Spesifik Hasil Kolaborasi RIASEC + Career Anchors per Jurusan
     */
    protected function generateCollaborationIdeas(string $majorCode, string $anchor, string $pRiasec): array
    {
        // Peta ide konkret berdasarkan jurusan SMK + Career Anchor
        $ideaCatalog = [
            'AKL' => [
                'AU' => [
                    ['title' => 'Junior Bookkeeper Freelance', 'desc' => 'Membuka jasa pencatatan keuangan harian kas kecil dan invoice untuk UMKM secara fleksibel.'],
                    ['title' => 'Staf Administrasi Pajak Pemula (Tax Clerk)', 'desc' => 'Membantu merapikan nota, faktur, dan arsip dokumen pajak di Kantor Konsultan Pajak lokal.'],
                ],
                'TF' => [
                    ['title' => 'Analisis Akuntansi Keuangan S1 / D4 Perbankan', 'desc' => 'Kuliah memperdalam spesialisasi audit finansial, perpajakan internasional, dan analisis riset keuangan.'],
                    ['title' => 'Sertifikasi Teknisi Akuntansi Muda', 'desc' => 'Mengambil sertifikasi profesi kompetensi akuntansi dasar untuk meningkatkan daya saing.'],
                ],
                'EC' => [
                    ['title' => 'Founder Jasa Pembukuan Digital UMKM', 'desc' => 'Mendirikan bisnis pembukuan dan pemasangan aplikasi kasir digital untuk toko lokal.'],
                    ['title' => 'Owner Startup Agen Pembayaran & PPOB', 'desc' => 'Membangun usaha transaksi keuangan digital dan pembayaran tagihan komunitas.'],
                ],
                'SE' => [
                    ['title' => 'Peserta Magang Resmi Frontliner Perbankan', 'desc' => 'Masuk lewat program kemitraan resmi khusus SMK (contoh: Magang Bakti BCA/Mandiri posisi Teller atau CS kontrak).'],
                    ['title' => 'Junior Bookkeeper / Staf Pembukuan Harian', 'desc' => 'Mencatat transaksi keuangan kas kecil ke program Excel atau aplikasi akuntansi digital.'],
                    ['title' => 'Staf Administrasi Pajak Pemula (Tax Clerk)', 'desc' => 'Membantu merapikan nota, faktur, dan arsip dokumen pajak di Kantor Konsultan Pajak lokal.'],
                ],
            ],
            'RPL' => [
                'AU' => [
                    ['title' => 'Junior Web / Mobile Developer Freelance', 'desc' => 'Menerima proyek pembuatan website dan perbaikan kode program sederhana secara independen.'],
                    ['title' => 'Software QA Tester Pemula', 'desc' => 'Mencoba aplikasi untuk mendeteksi error/bug berdasarkan SOP pengujian.'],
                ],
                'CH' => [
                    ['title' => 'Technical IT Support Internship', 'desc' => 'Staf teknis pembantu untuk pemeliharaan sistem internal kantor.'],
                ],
                'TF' => [
                    ['title' => 'Kuliah S1 Rekayasa Perangkat Lunak / Software Engineer', 'desc' => 'Memperdalam arsitektur sistem berskala besar dan algoritma kompleks di perguruan tinggi.'],
                ],
                'EC' => [
                    ['title' => 'Founder Software House & IT Support Studio', 'desc' => 'Mendirikan studio agensi pembuatan sistem informasi dan penyedia jasa perbaikan aplikasi UMKM.'],
                ],
                'SE' => [
                    ['title' => 'Junior Web / Mobile Developer', 'desc' => 'Menulis & merapikan kode program standar di bawah supervisi senior programmer.'],
                    ['title' => 'Software QA Tester Pemula', 'desc' => 'Mencoba aplikasi untuk mendeteksi error/bug berdasarkan SOP pengujian.'],
                    ['title' => 'Technical IT Support Internship', 'desc' => 'Staf teknis pembantu untuk pemeliharaan sistem internal kantor.'],
                ],
            ],
            'TKJ' => [
                'AU' => [
                    ['title' => 'Staf Helpdesk Hardware & Jaringan Freelance', 'desc' => 'Menangani keluhan teknis komputer dan setting Wi-Fi perorangan atau toko secara independen.'],
                ],
                'TF' => [
                    ['title' => 'Kuliah D4/S1 Teknik Jaringan Telekomunikasi', 'desc' => 'Menjadi pakar tersertifikasi Cisco/Mikrotik di industri telekomunikasi.'],
                ],
                'SE' => [
                    ['title' => 'Teknisi Lapangan / Field Engineer ISP', 'desc' => 'Pekerjaan taktis memasang kabel jaringan internet dan setting router Wi-Fi pelanggan.'],
                    ['title' => 'Junior Network Administrator', 'desc' => 'Mengawasi stabilitas server dan jaringan kantor skala kecil.'],
                    ['title' => 'Staf Helpdesk Hardware & Jaringan', 'desc' => 'Menangani keluhan teknis komputer pertama dari karyawan perusahaan.'],
                ],
            ],
            'DKV' => [
                'AU' => [
                    ['title' => 'Junior Graphic Designer Freelance', 'desc' => 'Mendesain materi promosi harian sederhana seperti brosur, pamflet, atau feed media sosial secara bebas.'],
                    ['title' => 'Junior Video Editor', 'desc' => 'Memotong materi video, menyelaraskan musik, dan menambahkan teks transisi untuk konten video pendek.'],
                ],
                'EC' => [
                    ['title' => 'Owner Studio Cetak & Sablon Digital', 'desc' => 'Membangun usaha cetak merchandise visual dan mengoperasikan mesin cetak digital.'],
                ],
                'SE' => [
                    ['title' => 'Operator Cetak & Pracetak Studio', 'desc' => 'Membantu memeriksa kecocokan warna dan mengoperasikan mesin cetak di digital printing.'],
                    ['title' => 'Junior Graphic Designer', 'desc' => 'Mendesain materi promosi harian sederhana seperti brosur, pamflet, atau feed media sosial.'],
                ],
            ],
            'Pemasaran' => [
                'EC' => [
                    ['title' => 'Live Streamer Jualan Pemula (Wirausaha)', 'desc' => 'Menjadi pembawa acara siaran langsung untuk menawarkan produk toko sendiri di TikTok/Shopee.'],
                ],
                'GM' => [
                    ['title' => 'Junior Sales / Merchant Acquisition', 'desc' => 'Membantu mengenalkan dan mendaftarkan mitra toko baru ke dalam sistem aplikasi/platform.'],
                ],
                'SE' => [
                    ['title' => 'Social Media Admin / Content Care', 'desc' => 'Mengelola pesan masuk, komentar, dan posting harian di akun media sosial toko/bisnis.'],
                    ['title' => 'Junior Sales / Merchant Acquisition', 'desc' => 'Membantu mengenalkan dan mendaftarkan mitra toko baru ke dalam sistem aplikasi/platform.'],
                ],
            ],
        ];

        // Default fallback jika tidak ada dalam katalog spesifik
        $defaultIdeas = [
            'AU' => [
                ['title' => "Freelancer / Staf Mandiri Pemula ({$majorCode})", 'desc' => "Membuka layanan jasa profesional pemula secara fleksibel berbasis keahlian kejuruan {$majorCode}."],
                ['title' => "Praktisi Jasa Mandiri", 'desc' => "Membangun usaha jasa mandiri yang melayani klien tanpa terikat aturan jam kerja kantor."],
            ],
            'TF' => [
                ['title' => "Kuliah Spesialisasi Keahlian ({$majorCode})", 'desc' => "Memperdalam riset akademik dan sertifikasi keahlian agar menjadi pakar di bidangnya."],
                ['title' => "Asisten Teknis Berkompetensi", 'desc' => "Menjadi praktisi pemula yang menyelesaikan tugas teknis kejuruan secara tekun."],
            ],
            'EC' => [
                ['title' => "Rintis Bisnis Pemula Mandiri ({$majorCode})", 'desc' => "Menciptakan brand atau produk bisnis baru sesuai kompetensi keahlian kejuruan."],
                ['title' => "Wirausaha Muda Digital", 'desc' => "Memanfaatkan peluang tren pasar terkini untuk membangun usaha mandiri."],
            ],
            'SE' => [
                ['title' => "Karyawan Entry-Level Perusahaan Terpercaya", 'desc' => "Menjadi staf operasional pemula dengan jaminan kerja, gaji terstruktur, dan lingkungan teratur."],
                ['title' => "Peserta Program Magang / Kemitraan Resmi SMK", 'desc' => "Masuk ke perusahaan bereputasi melalui jalur kemitraan resmi khusus lulusan SMK."],
            ],
            'GM' => [
                ['title' => "Staf Operasional Berpotensi Manajerial", 'desc' => "Bekerja di jalur profesional dengan potensi pengembangan tanggung jawab tim."],
                ['title' => "Junior Co-Coordinator Bisnis", 'desc' => "Mengoordinasikan fungsi kerja harian agar target tim tercapai."],
            ],
            'SV' => [
                ['title' => "Karier Sektor Pelayanan & Customer Care", 'desc' => "Bekerja di sektor pelayanan publik, edukasi, atau customer service."],
                ['title' => "Staf Layanan Pelanggan", 'desc' => "Menggunakan keahlian kejuruan untuk membantu pelanggan dan masyarakat."],
            ],
            'CH' => [
                ['title' => "Junior Technical Specialist", 'desc' => "Memecahkan masalah teknis harian di bidang teknologi dan sistem."],
                ['title' => "Kuliah Terapan & Inovasi Teknologi", 'desc' => "Melanjutkan studi di prodi riset terapan untuk memperdalam inovasi."],
            ],
            'LS' => [
                ['title' => "Staf Support / Admin Fleksibel", 'desc' => "Bekerja secara fleksibel yang memberikan kebebasan waktu pribadi."],
                ['title' => "Praktisi Karir Fleksibel", 'desc' => "Memilih posisi pekerjaan entry-level yang menghargai keseimbangan gaya hidup."],
            ],
        ];

        return $ideaCatalog[$majorCode][$anchor]
            ?? $defaultIdeas[$anchor]
            ?? $defaultIdeas['AU'];
    }

    /**
     * Deteksi jurusan SMK siswa dari teks kelas (misal "XII AKL 1" => AKL).
     */
    public function detectStudentMajor(?User $user): array
    {
        $kelas = $user ? $user->kelas : null;
        $upperKelas = strtoupper((string)$kelas);

        foreach (self::MAJOR_ABBREVIATIONS as $abbrev => $canonicalKey) {
            if (str_contains($upperKelas, $abbrev)) {
                return [
                    'major_key'  => $canonicalKey,
                    'major_code' => $abbrev,
                    'major_name' => self::VOCATIONAL_MATRIX[$canonicalKey]['name'] ?? $canonicalKey,
                    'short_name' => self::VOCATIONAL_MATRIX[$canonicalKey]['short_name'] ?? $canonicalKey,
                ];
            }
        }

        // Default jika tidak ada kelas
        return [
            'major_key'  => 'AKL',
            'major_code' => 'AKL',
            'major_name' => 'Akuntansi Keuangan Lembaga',
            'short_name' => 'Akuntansi Keuangan Lembaga (AKL)',
        ];
    }

    /**
     * Dapatkan daftar rekomendasi terpadu (Linier Top 3 & Eksplorasi Lintas Jurusan)
     * yang disesuaikan secara mendalam dengan Jurusan Asal Siswa + Hasil Tes RIASEC.
     *
     * @param string $hollandCode (misal: "I-R-A")
     * @param User|null $user
     * @return array
     */
    public function getRecommendationsForCode(string $hollandCode, ?User $user = null): array
    {
        $letters = explode('-', str_replace(' ', '', $hollandCode));
        $primaryCode = $letters[0] ?? 'R';
        $secondaryCode = $letters[1] ?? 'I';
        $tertiaryCode = $letters[2] ?? 'A';

        // 1. Deteksi Jurusan Asal Siswa
        $detectedMajor = $this->detectStudentMajor($user);
        $majorKey = $detectedMajor['major_key'];
        $majorData = self::VOCATIONAL_MATRIX[$majorKey] ?? self::VOCATIONAL_MATRIX['Akuntansi Keuangan Lembaga'];

        // 2. Persona Title & Psikologis
        $analysis = $this->generatePsychologicalAnalysis($user, $detectedMajor, $letters);

        // 3. Rekomendasi Utama & Linier (Top 3 Baris 1)
        $jurusanLinier = $this->formatLinierItems($majorData['kuliah'], 'jurusan', $letters, $detectedMajor['major_code']);
        $profesiLinier = $this->formatLinierItems($majorData['kerja'], 'profesi', $letters, $detectedMajor['major_code']);
        $usahaLinier   = $this->formatLinierItems($majorData['wirausaha'], 'usaha', $letters, $detectedMajor['major_code']);

        // 4. Rekomendasi Lintas Jurusan (Peluang Baru Baris 2)
        $crossMajorOptions = $this->generateCrossMajorRecommendations($majorKey, $letters);

        // 5. Query rekomendasi umum dari DB untuk backward compatibility jika diperlukan
        $dbRecommendations = CareerRecommendation::active()
            ->whereIn('riasec_code', $letters)
            ->get();

        $jurusanAll = $dbRecommendations->where('category', 'jurusan')->values();
        $profesiAll = $dbRecommendations->where('category', 'profesi')->values();
        $usahaAll   = $dbRecommendations->where('category', 'usaha')->values();

        return [
            // Backward compatibility
            'jurusan' => $jurusanAll,
            'profesi' => $profesiAll,
            'usaha'   => $usahaAll,
            'primary_code' => $primaryCode,
            'all_codes'    => $letters,

            // New curated 2-row architecture
            'student_major'      => $detectedMajor['major_name'],
            'student_major_code' => $detectedMajor['major_code'],
            'student_short_name' => $detectedMajor['short_name'],
            'psychological_analysis' => $analysis,

            // Baris 1: Linier
            'jurusan_linier' => $jurusanLinier,
            'profesi_linier' => $profesiLinier,
            'usaha_linier'   => $usahaLinier,

            // Baris 2: Lintas Jurusan
            'jurusan_lintas' => $crossMajorOptions['jurusan'],
            'profesi_lintas' => $crossMajorOptions['profesi'],
            'usaha_lintas'   => $crossMajorOptions['usaha'],
            'cross_hint'     => $crossMajorOptions['hint'],
        ];
    }

    /**
     * Format item linier agar memiliki badge, alasan psikologis, penomoran Top 3, dan aksi rencana.
     */
    protected function formatLinierItems(array $rawList, string $category, array $letters, string $majorCode): array
    {
        $actionMap = [
            'jurusan' => 'kuliah',
            'profesi' => 'bekerja',
            'usaha'   => 'berwirausaha',
        ];

        $results = [];
        foreach ($rawList as $idx => $item) {
            $rank = $idx + 1;
            $isTop3 = ($idx < 3);
            $results[] = [
                'name'           => $item['name'],
                'riasec_code'    => $item['code'] ?? ($letters[0] . ' - ' . ($letters[1] ?? 'C')),
                'category'       => $category,
                'category_label' => $category === 'jurusan' ? 'Program Studi' : ($category === 'profesi' ? 'Karier Industri' : 'Ide Bisnis'),
                'type'           => 'linier',
                'is_top3'        => $isTop3,
                'rank'           => $rank,
                'badge_label'    => $isTop3 ? "🌟 Rekomendasi Utama #{$rank} ({$majorCode})" : "✨ Pilihan Relevan #{$rank} ({$majorCode})",
                'description'    => $item['desc'] ?? "Selaras dengan latar belakang jurusan {$majorCode}.",
                'action_plan'    => $actionMap[$category] ?? 'kuliah',
            ];
        }

        return $results;
    }

    /**
     * Buat rekomendasi lintas jurusan berdasarkan dimensi sekunder dan tertier siswa.
     */
    protected function generateCrossMajorRecommendations(string $currentMajorKey, array $letters): array
    {
        // Temukan jurusan target lain yang memiliki dimensi sama dengan huruf ke-2 dan ke-3 siswa
        $secondaryDim = $letters[1] ?? 'I';
        $tertiaryDim  = $letters[2] ?? 'A';

        // Pilihan prodi lintas jurusan yang paling populer berdasarkan dimensi
        $crossCatalog = [
            'R' => [
                'jurusan' => [
                    'name' => 'Teknik Informatika / Rekayasa Perangkat Lunak (S1/D4)',
                    'code' => 'I - R',
                    'source' => 'PPLG / IT',
                    'desc' => 'Jalur IT murni. Cocok jika kamu ingin beralih total membangun perangkat lunak, aplikasi, atau logika gim digital memanfaatkan bakat teknis (R).',
                ],
                'profesi' => [
                    'name' => 'Fullstack Web & Mobile App Developer',
                    'code' => 'R - I',
                    'source' => 'PPLG / IT',
                    'desc' => 'Membangun arsitektur perangkat lunak komersial dengan keterampilan teknis praktis dan analisis logika.',
                ],
                'usaha' => [
                    'name' => 'Software House & Agensi Pembuatan Aplikasi',
                    'code' => 'I - E',
                    'source' => 'PPLG / IT',
                    'desc' => 'Membuka agensi jasa perancangan sistem dan software kasir/operasional untuk perusahaan UMKM.',
                ],
            ],
            'I' => [
                'jurusan' => [
                    'name' => 'Sains Data, AI, & Riset Analitik Bisnis (S1)',
                    'code' => 'I - C',
                    'source' => 'Data Science',
                    'desc' => 'Mengeksplorasi potensi investigasi (I) untuk menggali insight big data, tren pasar, dan sistem kecerdasan buatan.',
                ],
                'profesi' => [
                    'name' => 'Data Analyst & Market Research Specialist',
                    'code' => 'I - E',
                    'source' => 'Riset Pasar',
                    'desc' => 'Menganalisis anomali data, memproyeksikan tren pasar, dan memberikan rekomendasi strategis bagi manajemen.',
                ],
                'usaha' => [
                    'name' => 'Konsultan Audit Sistem Data & Keamanan Informasi',
                    'code' => 'I - C',
                    'source' => 'Keamanan IT',
                    'desc' => 'Menyediakan layanan audit kepatuhan perlindungan data dan evaluasi performa sistem korporat.',
                ],
            ],
            'A' => [
                'jurusan' => [
                    'name' => 'Desain Komunikasi Visual & Multimedia Kreatif (S1/D4)',
                    'code' => 'A - R',
                    'source' => 'DKV / Industri Kreatif',
                    'desc' => 'Mengembangkan potensi Artistic (A) kamu di bidang multimedia, visual branding, atau UI/UX design secara profesional.',
                ],
                'profesi' => [
                    'name' => 'UI/UX Designer & Creative Visual Specialist',
                    'code' => 'A - I',
                    'source' => 'Desain Digital',
                    'desc' => 'Merancang estetika grafis, interaksi antarmuka pengguna, dan visual brand yang modern dan memikat.',
                ],
                'usaha' => [
                    'name' => 'Studio Desain Kreatif & Digital Branding Agency',
                    'code' => 'A - E',
                    'source' => 'Industri Kreatif',
                    'desc' => 'Membuka agensi visual yang melayani desain logo, kampanye media sosial, dan materi promosi perusahaan.',
                ],
            ],
            'S' => [
                'jurusan' => [
                    'name' => 'Manajemen Pariwisata & Keramahan Internasional (D4/S1)',
                    'code' => 'S - E',
                    'source' => 'Perhotelan / Wisata',
                    'desc' => 'Memaksimalkan bakat Social (S) kamu dalam melayani orang lain, public relations, dan tata kelola pengalaman pelancong.',
                ],
                'profesi' => [
                    'name' => 'Public Relations & Guest Relations Specialist',
                    'code' => 'S - E',
                    'source' => 'Komunikasi & Humas',
                    'desc' => 'Menjalin kemitraan media, mengelola reputasi instansi, dan memberikan pelayanan VIP pada tamu korporat.',
                ],
                'usaha' => [
                    'name' => 'Penyedia Jasa Event Organizer & Edu-Tourism Gathering',
                    'code' => 'S - E',
                    'source' => 'Event Management',
                    'desc' => 'Mengorganisasi acara seminar korporat, pameran seni, dan kegiatan gathering wisata yang berkesan.',
                ],
            ],
            'E' => [
                'jurusan' => [
                    'name' => 'Bisnis Digital, E-Commerce, & Manajemen Kewirausahaan (S1)',
                    'code' => 'E - A',
                    'source' => 'Pemasaran / Bisnis',
                    'desc' => 'Menyalurkan bakat Enterprising (E) kamu untuk memimpin bisnis, merancang strategi pemasaran viral, dan bernegosiasi.',
                ],
                'profesi' => [
                    'name' => 'Growth Hacker & Digital Product Manager',
                    'code' => 'E - I',
                    'source' => 'Ekosistem Startup',
                    'desc' => 'Mengekselerasi pertumbuhan penjualan produk dan memimpin kolaborasi tim lintas divisi secara tangkas.',
                ],
                'usaha' => [
                    'name' => 'Owner Brand E-Commerce & Bisnis Produk D2C',
                    'code' => 'E - S',
                    'source' => 'Perdagangan Daring',
                    'desc' => 'Membangun brand produk sendiri dan memasarkannya secara langsung ke pasar nasional melalui media sosial.',
                ],
            ],
            'C' => [
                'jurusan' => [
                    'name' => 'Administrasi Perkantoran Modern & Sistem Informasi Bisnis (S1/D4)',
                    'code' => 'C - I',
                    'source' => 'Manajemen Kantor',
                    'desc' => 'Memanfaatkan ketelitian Conventional (C) kamu untuk tata kelola dokumen digital, kepatuhan SOP, dan keteraturan arsip.',
                ],
                'profesi' => [
                    'name' => 'Operations Project Coordinator & Document Specialist',
                    'code' => 'C - E',
                    'source' => 'Tata Kelola Korporat',
                    'desc' => 'Mengatur kelancaran arus dokumen operasional, monitoring jadwal kerja tim, dan kesiapan pelaporan berkala.',
                ],
                'usaha' => [
                    'name' => 'Agensi Asisten Virtual & Kesekretariatan Bisnis Online',
                    'code' => 'C - E',
                    'source' => 'Layanan Profesional',
                    'desc' => 'Menyediakan jasa pengelolaan jadwal, customer care, dan administrasi online untuk pebisnis sibuk.',
                ],
            ],
        ];

        // Jika siswa AKL dengan skor R & A tinggi (seperti wireframe Fatjeri)
        // Kita racik kombinasi 3 opsi lintas jurusan yang paling sesuai dengan secondary dan tertiary dimensions!
        $dimsToUse = [$secondaryDim, $tertiaryDim];
        if (!in_array('R', $dimsToUse) && in_array($currentMajorKey, ['Akuntansi Keuangan Lembaga', 'Pemasaran'])) {
            $dimsToUse[] = 'R';
        }
        if (!in_array('A', $dimsToUse) && in_array($currentMajorKey, ['Akuntansi Keuangan Lembaga', 'TJKT'])) {
            $dimsToUse[] = 'A';
        }

        $crossJurusan = [];
        $crossProfesi = [];
        $crossUsaha   = [];

        // Opsi 1: Dari dimensi kedua
        $dim1 = $secondaryDim;
        if (isset($crossCatalog[$dim1])) {
            $crossJurusan[] = $this->buildCrossItem($crossCatalog[$dim1]['jurusan'], 'jurusan', 'kuliah');
            $crossProfesi[] = $this->buildCrossItem($crossCatalog[$dim1]['profesi'], 'profesi', 'bekerja');
            $crossUsaha[]   = $this->buildCrossItem($crossCatalog[$dim1]['usaha'], 'usaha', 'berwirausaha');
        }

        // Opsi 2: Dari dimensi ketiga
        $dim2 = $tertiaryDim;
        if (isset($crossCatalog[$dim2]) && $dim2 !== $dim1) {
            $crossJurusan[] = $this->buildCrossItem($crossCatalog[$dim2]['jurusan'], 'jurusan', 'kuliah');
            $crossProfesi[] = $this->buildCrossItem($crossCatalog[$dim2]['profesi'], 'profesi', 'bekerja');
            $crossUsaha[]   = $this->buildCrossItem($crossCatalog[$dim2]['usaha'], 'usaha', 'berwirausaha');
        }

        // Opsi 3: Pilihan kreatif lintas ke industri kuliner/hospitality atau DKV jika skor R/A/E tinggi
        if (in_array('A', $letters) || in_array('R', $letters)) {
            $crossJurusan[] = [
                'name'           => 'Seni Kuliner & Pengolahan Makanan (D3/D4)',
                'riasec_code'    => 'R - A',
                'category'       => 'jurusan',
                'category_label' => 'Program Studi',
                'type'           => 'lintas',
                'badge_label'    => '🔄 Lintas Jurusan (Peluang Baru)',
                'source_major'   => 'Kuliner / Hospitality',
                'description'    => 'Mengembangkan keterampilan praktis (R) dan estetika penyajian (A) di industri hospitality dan kreatif kuliner bertaraf internasional.',
                'action_plan'    => 'kuliah',
            ];
            $crossProfesi[] = [
                'name'           => 'Food Stylist & Creative Kitchen Specialist',
                'riasec_code'    => 'A - R',
                'category'       => 'profesi',
                'category_label' => 'Karier Industri',
                'type'           => 'lintas',
                'badge_label'    => '🔄 Lintas Jurusan (Peluang Baru)',
                'source_major'   => 'Kuliner Kreatif',
                'description'    => 'Memadukan keahlian praktis memasak dengan cita rasa seni visual untuk pemotretan iklan komersial F&B.',
                'action_plan'    => 'bekerja',
            ];
            $crossUsaha[] = [
                'name'           => 'Bisnis Kafe Tematik & Creative Cloud Kitchen',
                'riasec_code'    => 'R - E',
                'category'       => 'usaha',
                'category_label' => 'Ide Bisnis',
                'type'           => 'lintas',
                'badge_label'    => '🔄 Lintas Jurusan (Peluang Baru)',
                'source_major'   => 'Wirausaha Kreatif',
                'description'    => 'Membangun usaha F&B unik berbasis visual instagramable dan pesanan pesan-antar daring.',
                'action_plan'    => 'berwirausaha',
            ];
        } else {
            // Default pelengkap
            $altDim = 'E';
            if (isset($crossCatalog[$altDim])) {
                $crossJurusan[] = $this->buildCrossItem($crossCatalog[$altDim]['jurusan'], 'jurusan', 'kuliah');
                $crossProfesi[] = $this->buildCrossItem($crossCatalog[$altDim]['profesi'], 'profesi', 'bekerja');
                $crossUsaha[]   = $this->buildCrossItem($crossCatalog[$altDim]['usaha'], 'usaha', 'berwirausaha');
            }
        }

        return [
            'jurusan' => array_slice($crossJurusan, 0, 4),
            'profesi' => array_slice($crossProfesi, 0, 4),
            'usaha'   => array_slice($crossUsaha, 0, 4),
            'hint'    => "Pilihan prodi/karier non-{$currentMajorKey} yang direkomendasikan karena skor dimensi {$secondaryDim} dan {$tertiaryDim} kamu menonjol.",
        ];
    }

    protected function buildCrossItem(array $catalogItem, string $category, string $actionPlan): array
    {
        return [
            'name'           => $catalogItem['name'],
            'riasec_code'    => $catalogItem['code'],
            'category'       => $category,
            'category_label' => $category === 'jurusan' ? 'Program Studi' : ($category === 'profesi' ? 'Karier Industri' : 'Ide Bisnis'),
            'type'           => 'lintas',
            'badge_label'    => '🔄 Lintas Jurusan (Peluang Baru)',
            'source_major'   => $catalogItem['source'] ?? 'Eksplorasi Baru',
            'description'    => $catalogItem['desc'],
            'action_plan'    => $actionPlan,
        ];
    }

    /**
     * Hasilkan narasi analisis psikologis karakter personal siswa.
     */
    protected function generatePsychologicalAnalysis(?User $user, array $detectedMajor, array $letters): array
    {
        $name = $user ? $user->name : 'Siswa';
        // Bersihkan nama depan siswa (hilangkan singkatan gelar awal seperti M., A., Moch.)
        $cleanName = trim(preg_replace('/^(A\.|M\.|MOCH\.|MUH\.|MD\.)\s+/i', '', $name));
        $parts = explode(' ', $cleanName);
        $firstName = ucfirst(strtolower($parts[0] ?? $name));

        $pCode = $letters[0] ?? 'R';
        $sCode = $letters[1] ?? 'I';
        $tCode = $letters[2] ?? 'A';

        $majorKey = $detectedMajor['major_key'];
        $majorCode = $detectedMajor['major_code'];
        $majorName = $detectedMajor['major_name'];

        // Persona Title generator berbasis Dominant RIASEC + Jurusan
        $personaTitles = [
            'I' => [
                'Akuntansi Keuangan Lembaga' => 'Investigative Financial Explorer',
                'Pengembangan Perangkat Lunak dan Gim' => 'Innovative Software Architect',
                'Teknik Jaringan Komputer & Telekomunikasi' => 'Cyber Systems Sentinel',
                'Desain Komunikasi Visual' => 'UX Research & Visual Visionary',
                'Pemasaran' => 'Data-Driven Market Strategist',
                'Kuliner' => 'Gastronomy Science Innovator',
                'Busana' => 'Technical Pattern & Mode Analyst',
            ],
            'R' => [
                'Teknik Jaringan Komputer & Telekomunikasi' => 'Practical Infrastructure Specialist',
                'Pengembangan Perangkat Lunak dan Gim' => 'Embedded Systems & IoT Engineer',
                'Kuliner' => 'Master Culinary Craftsman',
                'Busana' => 'Industrial Garment Artisan',
                'Perhotelan' => 'Hospitality Operations Specialist',
                'Akuntansi Keuangan Lembaga' => 'Practical Financial Systems Technician',
            ],
            'A' => [
                'Desain Komunikasi Visual' => 'Creative Visual Visionary',
                'Busana' => 'Avant-Garde Fashion Creator',
                'Kuliner' => 'Artistic Gastronomic Designer',
                'Pengembangan Perangkat Lunak dan Gim' => 'Creative Game & UI Designer',
                'Usaha Layanan Wisata' => 'Tourism Visual Storyteller',
                'Pemasaran' => 'Creative Campaign Strategist',
            ],
            'E' => [
                'Pemasaran' => 'Strategic Commerce & Brand Pioneer',
                'Usaha Layanan Wisata' => 'Tourism Venture Explorer',
                'Manajemen Perkantoran' => 'Dynamic Corporate Operations Leader',
                'Akuntansi Keuangan Lembaga' => 'FinTech & Capital Strategist',
                'Perhotelan' => 'Hospitality Business Pioneer',
                'Kuliner' => 'Culinary Venture Entrepreneur',
            ],
            'S' => [
                'Perhotelan' => 'Premier Guest Experience Ambassador',
                'Usaha Layanan Wisata' => 'Inspiring Voyage & Cultural Ambassador',
                'Manajemen Perkantoran' => 'Corporate Relations & HR Coordinator',
                'Pemasaran' => 'Customer Experience Champion',
            ],
            'C' => [
                'Akuntansi Keuangan Lembaga' => 'Precision Financial Controller & Auditor',
                'Manajemen Perkantoran' => 'Executive Systems & Document Controller',
                'Teknik Jaringan Komputer & Telekomunikasi' => 'Network Protocol & Compliance Specialist',
                'Pemasaran' => 'Retail Systems & Inventory Controller',
            ],
        ];

        $personaTitle = $personaTitles[$pCode][$majorName] 
            ?? $personaTitles[$pCode][$majorKey]
            ?? (self::DIMENSIONS[$pCode] . ' ' . $majorCode . ' Specialist');

        // Narasi Psikologis
        $d1Label = self::DIMENSION_LABELS[$pCode] ?? self::DIMENSIONS[$pCode];
        $d2Label = self::DIMENSION_LABELS[$sCode] ?? self::DIMENSIONS[$sCode];
        $d3Label = self::DIMENSION_LABELS[$tCode] ?? self::DIMENSIONS[$tCode];

        $narrative = "\"{$firstName}, kamu adalah seorang {$personaTitle}! Sebagai siswa {$majorName} ({$majorCode}), hasil tesmu menunjukkan kamu tidak hanya sekadar menguasai kejuruanmu, tetapi memiliki ketertarikan tinggi pada {$d1Label}, {$d2Label}, dan {$d3Label}. Kombinasi kepribadian ini membuktikan kamu sangat unggul jika diarahkan ke bidang linier keahlianmu, sekaligus memiliki potensi besar mengeksplorasi peluang jalur lintas jurusan yang membutuhkan keahlian terintegrasi.\"";

        return [
            'persona_title' => $personaTitle,
            'narrative'     => $narrative,
            'major_name'    => $majorName,
            'major_code'    => $majorCode,
            'student_name'  => $name,
            'first_name'    => $firstName,
            'holland_code'  => implode(' - ', $letters),
        ];
    }

    /**
     * Dapatkan ringkasan statistik distribusi RIASEC dominan untuk Dashboard Admin.
     */
    public function getRiasecDistribution(): array
    {
        $counts = [
            'Realistic'     => CareerResult::where('dominant_type', 'Realistic')->count(),
            'Investigative' => CareerResult::where('dominant_type', 'Investigative')->count(),
            'Artistic'      => CareerResult::where('dominant_type', 'Artistic')->count(),
            'Social'        => CareerResult::where('dominant_type', 'Social')->count(),
            'Enterprising'  => CareerResult::where('dominant_type', 'Enterprising')->count(),
            'Conventional'  => CareerResult::where('dominant_type', 'Conventional')->count(),
        ];

        return $counts;
    }

    /**
     * Dapatkan ringkasan statistik distribusi Career Anchors untuk Dashboard Admin.
     */
    public function getAnchorDistribution(): array
    {
        $anchors = ['TF', 'GM', 'AU', 'SE', 'EC', 'SV', 'CH', 'LS'];
        $counts = [];
        foreach ($anchors as $anc) {
            $counts[$anc] = [
                'name'  => self::ANCHOR_NAMES[$anc] ?? $anc,
                'code'  => $anc,
                'count' => CareerResult::where('dominant_anchor', $anc)->count(),
            ];
        }

        return $counts;
    }

    /**
     * Dapatkan ringkasan statistik distribusi Jalur Eksekusi Utama (Kuliah / Kerja / Wirausaha).
     */
    public function getExecutionPathDistribution(): array
    {
        return [
            'kuliah'       => CareerResult::where('recommended_execution_path', 'kuliah')->count(),
            'bekerja'      => CareerResult::where('recommended_execution_path', 'bekerja')->count(),
            'berwirausaha' => CareerResult::where('recommended_execution_path', 'berwirausaha')->count(),
        ];
    }
}
