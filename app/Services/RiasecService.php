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
                [
                    'name' => 'S1 Informatika / Rekayasa Perangkat Lunak',
                    'code' => 'I - R',
                    'desc' => 'Memperdalam rekayasa arsitektur perangkat lunak, algoritma pemrograman tingkat lanjut, dan pengembangan aplikasi.',
                ],
                [
                    'name' => 'D4 Teknologi Rekayasa Perangkat Lunak Aplikasi',
                    'code' => 'I - C',
                    'desc' => 'Fokus vokasi praktis pada pembuatan aplikasi enterprise, pengujian kode sistematis, dan pemeliharaan basis data.',
                ],
                [
                    'name' => 'S1 Sains Data / Bisnis Informasi',
                    'code' => 'I - C',
                    'desc' => 'Menggabungkan keahlian coding logika dengan pengolahan data analitik, analisis bisnis, dan sistem informasi.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Junior Web / Mobile Programmer',
                    'code' => 'I - R',
                    'desc' => 'Menulis & merapikan kode program standar di bawah supervisi senior programmer.',
                ],
                [
                    'name' => 'Software QA Tester Pemula (Pencari Bug)',
                    'code' => 'I - C',
                    'desc' => 'Mencoba aplikasi untuk mendeteksi error/bug berdasarkan SOP pengujian.',
                ],
                [
                    'name' => 'Staf Technical IT Support Magang',
                    'code' => 'C - I',
                    'desc' => 'Staf teknis pembantu untuk pemeliharaan sistem internal kantor.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Mendirikan Software House Mandiri (Jasa Web/Apps)',
                    'code' => 'I - E',
                    'desc' => 'Menyediakan layanan pembuatan aplikasi bisnis digital, point of sales (POS), dan software kasir untuk klien.',
                ],
                [
                    'name' => 'Membangun Studio Pembuat Game Independen Skala Mikro',
                    'code' => 'A - E',
                    'desc' => 'Memproduksi dan memonetisasi gim orisinal untuk pasar mobile (Google Play) dan PC.',
                ],
            ],
        ],

        'TJKT' => [
            'name' => 'Teknik Jaringan Komputer & Telekomunikasi',
            'short_name' => 'TJKT (TKJ)',
            'code' => 'TKJ',
            'core_dimensions' => ['R', 'I', 'C'],
            'kuliah' => [
                [
                    'name' => 'S1 Teknik Komputer',
                    'code' => 'R - I',
                    'desc' => 'Mempelajari rekayasa perangkat keras, telekomunikasi modern, arsitektur server, dan telematika.',
                ],
                [
                    'name' => 'D4 Teknologi Rekayasa Jaringan Telekomunikasi',
                    'code' => 'R - C',
                    'desc' => 'Pendidikan vokasi pada perancangan jaringan fiber optik, transmisi seluler, dan routing switching enterprise.',
                ],
                [
                    'name' => 'S1 Keamanan Siber (Cybersecurity)',
                    'code' => 'I - R',
                    'desc' => 'Mengembangkan benteng pertahanan digital, investigasi penetrasi jaringan, dan proteksi server dari ancaman siber.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Teknisi Lapangan / Field Engineer ISP Internet',
                    'code' => 'R - I',
                    'desc' => 'Pekerjaan taktis memasang kabel jaringan internet dan setting router Wi-Fi pelanggan.',
                ],
                [
                    'name' => 'Junior Network Administrator Perusahaan',
                    'code' => 'R - C',
                    'desc' => 'Mengawasi stabilitas server dan jaringan kantor skala kecil.',
                ],
                [
                    'name' => 'Staf Helpdesk Perangkat Keras & Jaringan Kantor',
                    'code' => 'R - S',
                    'desc' => 'Menangani keluhan teknis komputer pertama dari karyawan perusahaan.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Penyedia Jasa Instalasi Jaringan Internet Kantor/RT-RW Net lokal',
                    'code' => 'R - E',
                    'desc' => 'Menyediakan layanan instalasi WiFi terkelola, penarikan kabel fiber optik, dan pemeliharaan jaringan.',
                ],
                [
                    'name' => 'Toko Online Pengadaan Perangkat Keras Jaringan',
                    'code' => 'C - E',
                    'desc' => 'Menjual perlengkapan networking, mikrotik, access point, server mini, dan komponen pendukung IT.',
                ],
            ],
        ],

        'Pemasaran' => [
            'name' => 'Pemasaran',
            'short_name' => 'Pemasaran (Bisnis Ritel / BR)',
            'code' => 'BR',
            'core_dimensions' => ['E', 'A', 'S'],
            'kuliah' => [
                [
                    'name' => 'S1 Bisnis Digital',
                    'code' => 'E - I',
                    'desc' => 'Memadukan strategi pemasaran modern dengan ekosistem teknologi internet dan pasar daring.',
                ],
                [
                    'name' => 'S1 Manajemen Pemasaran',
                    'code' => 'E - A',
                    'desc' => 'Mendalami seni promosi persuasif, perilaku konsumen, riset tren pasar global, dan manajemen merek.',
                ],
                [
                    'name' => 'D4 Pemasaran Digital Vokasi',
                    'code' => 'E - S',
                    'desc' => 'Pendidikan vokasi pada praktik live-stream commerce, manajemen promosi media sosial, dan negosiasi ritel.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Social Media Admin / Content Care Toko',
                    'code' => 'E - I',
                    'desc' => 'Mengelola pesan masuk, komentar, dan posting harian di akun media sosial toko/bisnis.',
                ],
                [
                    'name' => 'Live Streamer Jualan Kreatif (TikTok/Shopee)',
                    'code' => 'E - A',
                    'desc' => 'Menjadi pembawa acara siaran langsung untuk menawarkan produk di TikTok/Shopee.',
                ],
                [
                    'name' => 'Junior Sales / Merchant Acquisition Lapangan',
                    'code' => 'I - E',
                    'desc' => 'Membantu mengenalkan dan mendaftarkan mitra toko baru ke dalam sistem aplikasi/platform.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Owner Bisnis E-Commerce / Dropshipper Brand Sendiri',
                    'code' => 'E - S',
                    'desc' => 'Membangun merek produk konsumen sendiri dengan pemasaran langsung ke pengguna melalui marketplace.',
                ],
                [
                    'name' => 'Perintis Digital Marketing & Content Agency Skala Kecil',
                    'code' => 'E - A',
                    'desc' => 'Menyediakan jasa pengelolaan konten promosi, periklanan, dan manajemen akun jualan bagi UMKM lokal.',
                ],
            ],
        ],

        'Manajemen Perkantoran' => [
            'name' => 'Manajemen Perkantoran & Layanan Bisnis',
            'short_name' => 'Manajemen Perkantoran (MPLB)',
            'code' => 'MPLB',
            'core_dimensions' => ['C', 'R', 'E'],
            'kuliah' => [
                [
                    'name' => 'S1 Administrasi Bisnis Digital',
                    'code' => 'C - E',
                    'desc' => 'Menyiapkan tata kelola operasional kantor modern berbasis sistem komputasi terpadu.',
                ],
                [
                    'name' => 'S1 Manajemen Sumber Daya Manusia (SDM)',
                    'code' => 'S - E',
                    'desc' => 'Mempelajari pengelolaan rekrutmen karyawan, pembinaan hubungan industrial, dan budaya kerja organisasi.',
                ],
                [
                    'name' => 'D4 Administrasi Perkantoran',
                    'code' => 'C - I',
                    'desc' => 'Vokasi pengelolaan basis data arsip korporat, dokumentasi legalitas, dan otomasi korespondensi resmi.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Staf Admin Kantor / Data Entry Clerk Teknis',
                    'code' => 'C - S',
                    'desc' => 'Mengetik, memasukkan data pelanggan, dan mengarsipkan dokumen digital secara teratur.',
                ],
                [
                    'name' => 'Resepsionis / Front Office Staff Perusahaan',
                    'code' => 'S - C',
                    'desc' => 'Menyambut tamu kantor dan mengurus administrasi surat masuk/keluar.',
                ],
                [
                    'name' => 'Junior Customer Service Admin Media Sosial',
                    'code' => 'C - R',
                    'desc' => 'Menjawab pesan keluhan dari pelanggan lewat sistem ticketing atau WhatsApp Bisnis.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Agensi Penyedia Jasa Virtual Assistant Mandiri',
                    'code' => 'C - E',
                    'desc' => 'Membuka agensi asisten sekretariat online yang melayani pengusaha luar negeri dan pebisnis daring.',
                ],
                [
                    'name' => 'Jasa Pengelolaan Administrasi & Ketikan Outsource Khusus UMKM',
                    'code' => 'C - S',
                    'desc' => 'Membantu pelaku usaha mikro mengelola surat perizinan, laporan operasional, dan arsip digital secara profesional.',
                ],
            ],
        ],

        'Akuntansi Keuangan Lembaga' => [
            'name' => 'Akuntansi Keuangan Lembaga',
            'short_name' => 'Akuntansi Keuangan Lembaga (AKL)',
            'code' => 'AKL',
            'core_dimensions' => ['C', 'I', 'E'],
            'kuliah' => [
                [
                    'name' => 'S1 Akuntansi Bisnis Digital / FinTech',
                    'code' => 'C - I',
                    'desc' => 'Mendalami penyusunan laporan keuangan digital, analisis investasi, dan teknologi finansial modern.',
                ],
                [
                    'name' => 'D4 Akuntansi Perpajakan Negara',
                    'code' => 'C - E',
                    'desc' => 'Pendidikan vokasi spesialis perancangan laporan pajak korporat dan audit perpajakan terapan.',
                ],
                [
                    'name' => 'S1 Sistem Informasi Akuntansi',
                    'code' => 'C - R',
                    'desc' => 'Mempelajari perancangan sistem software akuntansi dan pengolahan basis data finansial.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Junior Bookkeeper / Staf Pembukuan Keuangan Harian',
                    'code' => 'C - I',
                    'desc' => 'Mencatat transaksi keuangan kas kecil ke program Excel atau aplikasi akuntansi digital.',
                ],
                [
                    'name' => 'Staf Administrasi Pajak Pemula di Kantor Konsultan Pajak',
                    'code' => 'C - E',
                    'desc' => 'Membantu merapikan nota, faktur, dan arsip dokumen pajak di Kantor Konsultan Pajak lokal.',
                ],
                [
                    'name' => 'Peserta Magang Resmi Frontliner Perbankan (Jalur Khusus SMK Kontrak)',
                    'code' => 'C - S',
                    'desc' => 'Masuk lewat program kemitraan resmi khusus SMK (contoh: Magang Bakti BCA/Mandiri posisi Teller atau CS).',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Kantor Jasa Pembukuan & Akuntansi Berbasis Cloud untuk Toko Retail',
                    'code' => 'C - E',
                    'desc' => 'Mendirikan usaha layanan pencatatan kas harian dan penyusunan laporan keuangan bulanan untuk pedagang ritel.',
                ],
                [
                    'name' => 'Agen Mandiri Layanan Perbankan & Transaksi Digital Keuangan',
                    'code' => 'C - S',
                    'desc' => 'Mengelola titik transaksi pembayaran digital, transfer antarbank, dan layanan keagenan resmi.',
                ],
            ],
        ],

        'Usaha Layanan Wisata' => [
            'name' => 'Usaha Layanan Wisata',
            'short_name' => 'Usaha Layanan Wisata (ULW)',
            'code' => 'ULW',
            'core_dimensions' => ['S', 'E', 'A'],
            'kuliah' => [
                [
                    'name' => 'S1 Destinasi Pariwisata Digital',
                    'code' => 'S - E',
                    'desc' => 'Mempelajari tata kelola daya tarik wisata daerah, promosi pariwisata digital, dan pengembangan destinasi.',
                ],
                [
                    'name' => 'D4 Manajemen Bisnis Perjalanan Wisata',
                    'code' => 'S - C',
                    'desc' => 'Vokasi perencanaan tur penerbangan, negosiasi rekanan maskapai/hotel, dan ekspo wisata.',
                ],
                [
                    'name' => 'S1 Hubungan Masyarakat Pariwisata',
                    'code' => 'S - A',
                    'desc' => 'Mendalami etika keprotokoleran, kehumasan destinasi, dan public relations industri perjalanan.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Junior Tour Guide Lokal / Pemandu Bus Rute Pendek',
                    'code' => 'S - E',
                    'desc' => 'Memandu wisatawan di objek wisata daerah atau mendampingi bus pariwisata rute pendek.',
                ],
                [
                    'name' => 'Staf Ticketing & Reservasi Aplikasi Wisata',
                    'code' => 'C - S',
                    'desc' => 'Membantu memesankan tiket transportasi dan kamar hotel lewat sistem internal travel agent.',
                ],
                [
                    'name' => 'Admin Logistik Acara Open Trip Wisata',
                    'code' => 'S - R',
                    'desc' => 'Mengurus logistik dan pendaftaran peserta wisata kelompok.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Perintis Biro Open Trip Online Wisata Alternatif / Glamping',
                    'code' => 'E - S',
                    'desc' => 'Membuka usaha perjalanan wisata perjalanan alam terbuka dan paket camping/glamping privat.',
                ],
                [
                    'name' => 'Agensi Kreatif Pembuat Konten Dokumentasi Video Promosi Destinasi Wisata',
                    'code' => 'A - E',
                    'desc' => 'Menyediakan layanan pembuatan video promosi destinasi pariwisata dan dokumentasi perjalanan.',
                ],
            ],
        ],

        'Perhotelan' => [
            'name' => 'Perhotelan',
            'short_name' => 'Perhotelan (PH)',
            'code' => 'PH',
            'core_dimensions' => ['S', 'R', 'E'],
            'kuliah' => [
                [
                    'name' => 'D4 Manajemen Perhotelan',
                    'code' => 'S - R',
                    'desc' => 'Pendidikan vokasi pengelolaan divisi kamar, operasional front office, dan sanitasi komersial.',
                ],
                [
                    'name' => 'S1 Bisnis Perhotelan & Resor Internasional',
                    'code' => 'S - E',
                    'desc' => 'Mempelajari tata kelola resort mewah, manajemen pendapatan kamar, dan pemasaran hospitality.',
                ],
                [
                    'name' => 'D4 Pengelolaan Akomodasi Pariwisata',
                    'code' => 'S - C',
                    'desc' => 'Vokasi tata kelola operasional akomodasi pariwisata, homestay, dan layanan penginapan.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Room Attendant / Housekeeping Staff Hotel',
                    'code' => 'R - S',
                    'desc' => 'Membersihkan, merapikan, dan merawat kelayakan fasilitas kamar hotel sesuai standar industri.',
                ],
                [
                    'name' => 'Bellboy / Concierge Pemula Penyambut Tamu',
                    'code' => 'S - R',
                    'desc' => 'Menyambut tamu hotel di pintu masuk dan membantu mengantarkan barang bawaan mereka.',
                ],
                [
                    'name' => 'Front Desk Agent Trainee Meja Penerima',
                    'code' => 'S - C',
                    'desc' => 'Melayani proses check-in dan check-out tamu di meja resepsionis.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Pengelola Bisnis Penginapan Homestay Mandiri via Aplikasi AirBnB',
                    'code' => 'E - S',
                    'desc' => 'Mengelola unit rumah/kamar homestay ramah turis dengan pelayanan hangat khas lokal.',
                ],
                [
                    'name' => 'Jasa Layanan Kebersihan Properti (Cleaning Service) Panggilan via WhatsApp',
                    'code' => 'R - E',
                    'desc' => 'Menyediakan jasa pembersihan rumah, kamar kos, dan kantor panggilan terjangkau.',
                ],
            ],
        ],

        'Kuliner' => [
            'name' => 'Kuliner',
            'short_name' => 'Kuliner (Tata Boga / KL)',
            'code' => 'KL',
            'core_dimensions' => ['R', 'A', 'E'],
            'kuliah' => [
                [
                    'name' => 'D4 Manajemen Kuliner / Tata Boga',
                    'code' => 'R - A',
                    'desc' => 'Vokasi teknik pengolahan masakan barat/nusantara, higiene sanitasi, dan manajemen dapur.',
                ],
                [
                    'name' => 'S1 Seni Kuliner & Gastronomi',
                    'code' => 'A - R',
                    'desc' => 'Mendalami estetika penyajian hidangan (food plating), kreasi resep orisinal, dan sejarah seni kuliner.',
                ],
                [
                    'name' => 'S1 Teknologi Pangan Kuliner',
                    'code' => 'I - R',
                    'desc' => 'Mempelajari pengawetan makanan higienis, kandungan nutrisi, dan rekayasa bahan kuliner.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Commis Chef / Cook Helper (Pembantu Dapur)',
                    'code' => 'R - A',
                    'desc' => 'Membantu menyiapkan bahan masakan, memotong sayur/daging, dan menjaga kebersihan dapur profesional.',
                ],
                [
                    'name' => 'Junior Pastry Cook / Asisten Baker Toko Roti',
                    'code' => 'A - R',
                    'desc' => 'Membantu proses pembuatan dan pemanggangan roti atau kue di dapur bakery.',
                ],
                [
                    'name' => 'Pramusaji / Waiter Restoran Modern',
                    'code' => 'S - R',
                    'desc' => 'Melayani pencatatan pesanan menu dan mengantarkan makanan ke meja pelanggan dengan santun.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Mendirikan Bisnis Kuliner Makanan dengan Sistem Cloud Kitchen (Hanya Terima Order Online)',
                    'code' => 'E - R',
                    'desc' => 'Membuka gerai makanan viral berbasis pesanan antar (GoFood/GrabFood/ShopeeFood).',
                ],
                [
                    'name' => 'Usaha Katering Box Premium Sehat via Instagram',
                    'code' => 'R - E',
                    'desc' => 'Menyediakan katering kalori terhitung, menu sehat harian berlangganan bagi pekerja kantoran.',
                ],
            ],
        ],

        'Desain Komunikasi Visual' => [
            'name' => 'Desain Komunikasi Visual',
            'short_name' => 'Desain Komunikasi Visual (DKV)',
            'code' => 'DKV',
            'core_dimensions' => ['A', 'R', 'S'],
            'kuliah' => [
                [
                    'name' => 'S1 Desain Komunikasi Visual (DKV)',
                    'code' => 'A - R',
                    'desc' => 'Mempelajari komunikasi grafis, tipografi ekspresif, perancangan identitas visual brand, dan ilustrasi digital.',
                ],
                [
                    'name' => 'S1 Animasi, Film, & Fotografi Digital',
                    'code' => 'A - I',
                    'desc' => 'Mendalami perancangan karakter 2D/3D, sinematografi, visual effect, dan dokumentasi foto.',
                ],
                [
                    'name' => 'D4 Desain Grafis Cetak',
                    'code' => 'A - C',
                    'desc' => 'Vokasi perancangan tata letak kemasan produk, tata cetak offset, dan materi periklanan komersial.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Junior Graphic Designer Konten Harian Toko',
                    'code' => 'A - E',
                    'desc' => 'Mendesain materi promosi harian sederhana seperti brosur, pamflet, atau feed media sosial.',
                ],
                [
                    'name' => 'Junior Video Editor Konten Pendek Reels/TikTok',
                    'code' => 'A - R',
                    'desc' => 'Memotong materi video, menyelaraskan musik, dan menambahkan teks transisi untuk konten video pendek.',
                ],
                [
                    'name' => 'Operator Pra-Cetak di Studio Digital Printing',
                    'code' => 'A - I',
                    'desc' => 'Membantu memeriksa kecocokan warna dan mengoperasikan mesin cetak di digital printing.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Studio Visual Kreatif / Jasa Ilustrasi Kustom Mandiri',
                    'code' => 'A - E',
                    'desc' => 'Membuka studio jasa desain grafis, fotografi komersial, dan materi kampanye digital.',
                ],
                [
                    'name' => 'Kreator Penjual Aset Gambar Digital di Internet (Freepik/Shutterstock)',
                    'code' => 'A - C',
                    'desc' => 'Menjual karya ilustrasi, vektor grafis, template sosial media, dan aset visual di platform pasar kreatif dunia.',
                ],
            ],
        ],

        'Busana' => [
            'name' => 'Busana',
            'short_name' => 'Busana (Tata Busana / DPB)',
            'code' => 'DPB',
            'core_dimensions' => ['A', 'R', 'E'],
            'kuliah' => [
                [
                    'name' => 'S1 Fashion Design / Tata Busana',
                    'code' => 'A - R',
                    'desc' => 'Mendalami perancangan koleksi mode kekinian, rekayasa siluet busana, dan tren fashion global.',
                ],
                [
                    'name' => 'S1 Bisnis & Manajemen Retail Mode',
                    'code' => 'E - A',
                    'desc' => 'Mempelajari strategi pemasaran merek pakaian, rantai pasok garmen industri, dan merchandising mode.',
                ],
                [
                    'name' => 'D4 Desain Busana Kustom',
                    'code' => 'R - A',
                    'desc' => 'Pendidikan vokasi pada pembuatan pola pakaian, manipulasi kain tekstil, dan teknik jahit butik.',
                ],
            ],
            'kerja' => [
                [
                    'name' => 'Asisten Pola & Potong Kain (Pattern Maker Assistant)',
                    'code' => 'A - R',
                    'desc' => 'Membantu menerjemahkan sketsa menjadi pola dasar fisik/digital dan memotong kain secara presisi.',
                ],
                [
                    'name' => 'Operator Jahit Produksi (Sample Sewer) di Butik/Garmen',
                    'code' => 'R - I',
                    'desc' => 'Menjahit potongan kain menjadi pakaian utuh sesuai standar contoh industri garmen/butik.',
                ],
                [
                    'name' => 'Junior Wardrobe Assistant / Kru Penata Busana Studio Foto',
                    'code' => 'A - E',
                    'desc' => 'Membantu menyiapkan kelengkapan pakaian, aksesori, dan menyetrika busana di balik layar studio foto.',
                ],
            ],
            'wirausaha' => [
                [
                    'name' => 'Merintis Merek Pakaian Jadi Sendiri (Clothing Line) Skala Rumahan via E-Commerce',
                    'code' => 'E - A',
                    'desc' => 'Memproduksi koleksi baju muslim, busana casual, atau streetwear dengan identitas brand orisinal yang dipasarkan online.',
                ],
                [
                    'name' => 'Jasa Jahit Kustom Premium Online untuk Pakaian Pesta',
                    'code' => 'R - E',
                    'desc' => 'Melayani pesanan jahit baju kebaya wisuda, gaun pesta pesanan khusus, dan setelan formal berstandar butik.',
                ],
                [
                    'name' => 'Bisnis Pemasaran Tren Busana Muslim & Casual Wear',
                    'code' => 'E - S',
                    'desc' => 'Membangun usaha grosir/reseller busana muslim dan pakaian kasual kekinian via e-commerce.',
                ],
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
     * Format item linier agar memiliki badge, alasan psikologis, dan aksi rencana.
     */
    protected function formatLinierItems(array $rawList, string $category, array $letters, string $majorCode): array
    {
        $actionMap = [
            'jurusan' => 'kuliah',
            'profesi' => 'bekerja',
            'usaha'   => 'berwirausaha',
        ];

        $results = [];
        foreach (array_slice($rawList, 0, 3) as $idx => $item) {
            $results[] = [
                'name'         => $item['name'],
                'riasec_code'  => $item['code'] ?? ($letters[0] . ' - ' . ($letters[1] ?? 'C')),
                'category'     => $category,
                'category_label' => $category === 'jurusan' ? 'Program Studi' : ($category === 'profesi' ? 'Karier Industri' : 'Ide Bisnis'),
                'type'         => 'linier',
                'badge_label'  => "🌟 Rekomendasi Linier ({$majorCode})",
                'description'  => $item['desc'] ?? "Selaras dengan latar belakang jurusan {$majorCode}.",
                'action_plan'  => $actionMap[$category] ?? 'kuliah',
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
            'jurusan' => array_slice($crossJurusan, 0, 3),
            'profesi' => array_slice($crossProfesi, 0, 3),
            'usaha'   => array_slice($crossUsaha, 0, 3),
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
