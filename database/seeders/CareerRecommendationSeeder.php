<?php

namespace Database\Seeders;

use App\Models\CareerRecommendation;
use Illuminate\Database\Seeder;

class CareerRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Bank Rekomendasi Karier & Studi Komprehensif untuk 10 Kompetensi Keahlian SMK:
     * PPLG/RPL, TJKT/TKJ, AKL, MPLB, Pemasaran/BR, DKV, Perhotelan, Kuliner, Tata Busana, ULW.
     * Terbagi ke dalam 6 Tipe RIASEC (Realistic, Investigative, Artistic, Social, Enterprising, Conventional).
     * Total ~180+ Rekomendasi Terkurasi (Jurusan Kuliah, Profesi Kerja, dan Bidang Wirausaha).
     */
    public function run(): void
    {
        $recommendations = [
            // =========================================================================
            // 1. REALISTIC (R) — Praktis, Mekanikal, Fisik, Perangkat Keras & Operasional
            // =========================================================================
            // Jurusan Kuliah (D3 / D4 / S1)
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'S1 Teknik Komputer & Sistem Benam', 'description' => 'Mempelajari rekayasa perangkat keras, telekomunikasi modern, arsitektur server mikro, dan telematika.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'description' => 'Pendidikan vokasi pada perancangan jaringan fiber optik, transmisi nirkabel, dan routing switching enterprise.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Manajemen Kuliner / Seni Pengolahan Masakan', 'description' => 'Vokasi teknik pengolahan masakan nusantara dan internasional, higiene sanitasi, dan operasional kitchen hotel.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Teknologi Rekayasa Komputer Jaringan & Cloud', 'description' => 'Fokus vokasi infrastruktur data center, komputasi awan, virtualisasi server, dan automasi jaringan.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D3 / D4 Pengelolaan Perhotelan (Divisi Kamar & Sarana)', 'description' => 'Fokus pada tata kelola operasional sarana fisik hotel, sanitasi komersial, dan pemeliharaan kenyamanan interior.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Desain dan Produksi Garmen / Tata Busana Industri', 'description' => 'Pendidikan vokasi teknologi jahit industri, permesinan garmen modern, dan efisiensi lini perakitan pakaian.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Rekayasa Perawatan Fasilitas Komersial & Bangunan Gedung', 'description' => 'Mempelajari teknik kelistrikan gedung komersial, HVAC tata udara, plumbing, dan keselamatan instalasi umum.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D3 Teknologi Pengolahan Hasil Perkebunan & Pangan', 'description' => 'Teknik terapan pengolahan bahan pangan nabati dan hewani menjadi produk olahan bernilai ekonomi tinggi.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Teknologi Rekayasa Mekatronika & Otomasi Pabrik', 'description' => 'Mempelajari kontrol mekanik robotik, sensor elektronik industri, dan sistem manufaktur otomatis.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D3 Teknik Komputer & Sistem Pemeliharaan IT', 'description' => 'Pendidikan praktis perbaikan perangkat keras komputer, setting periferal kantor, dan troubleshooting LAN.'],

            // Profesi / Karier di Dunia Kerja
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Teknisi Lapangan / Field Engineer ISP & Fiber Optik', 'description' => 'Melakukan instalasi penarikan kabel fiber optik, splicing FO, dan setting router Wi-Fi pelanggan.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Teknisi Instalasi & Pemeliharaan CCTV / Smart Home IoT', 'description' => 'Memasang dan mengonfigurasi kamera keamanan IP CAM, DVR/NVR, serta sensor otomatisasi pintar.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Commis Chef / Cook Helper (Asisten Juru Masak Dapur)', 'description' => 'Membantu persiapan bahan kuliner mentah, pemotongan daging/sayur, dan memasak masakan standar di kitchen line.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Room Attendant / Housekeeping Staff Hotel Berbintang', 'description' => 'Membersihkan, menata ranjang make-up room, dan merawat kelayakan sarana kamar hotel berstandar bintang 5.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Operator Mesin Digital Printing & Finishing Percetakan', 'description' => 'Mengoperasikan mesin cetak banner format besar, laser cutting grafis, dan finishing laminasi di studio offset.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Sample Sewer / Operator Jahit Butik & Modiste Halus', 'description' => 'Menjahit sampel busana baru, kebaya payet wisuda, dan gaun pesta pesanan butik dengan tusuk jarum presisi.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Teknisi Servis Hardware Komputer & Laptop di Service Center', 'description' => 'Mendiagnosis kerusakan komponen motherboard, instalasi OS berlisensi, reballing chip, dan ganti layar LCD.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Staf Logistik Pergudangan & Pengoperasian Forklift/Pallet', 'description' => 'Mengatur penataan barang fisik di rak gudang ritel, pengepakan muatan barang, dan bongkar muat ekspedisi.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Baker & Pastry Production Associate di Industri Roti Modern', 'description' => 'Menangani pengadukan adonan dalam jumlah besar, kontrol proofing ragi, dan pemanggangan roti otomatis.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'NOC (Network Operation Center) Monitoring Operator 24/7', 'description' => 'Memantau indikator stabilitas server dan link transmisi jaringan internet secara fisik dan live dashboard.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Operator Sablon Screen Printing Manual & Mesin DTF Digital', 'description' => 'Mencetak grafis desain pakaian di atas kain katun menggunakan teknik sablon manual discharge atau mesin transfer DTF.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Teknisi Audio Visual (AV) & Tata Panggung Pertunjukan', 'description' => 'Merakit sound system, kabel mikrofon, instalasi video LED videotron, dan tata lampu panggung acara.'],

            // Peluang Usaha / Wirausaha
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Jasa Pemasangan & Perawatan Jaringan WiFi Kantor / RT-RW Net', 'description' => 'Usaha instalasi jaringan nirkabel terkelola, voucher hotspot desa, dan penarikan kabel LAN perkantoran.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Layanan Cuci Sepatu & Restorasi Tas Kulit (Shoes & Leather Care)', 'description' => 'Bisnis perawatan kebersihan sepatu premium, deep cleaning sneakers, unyellowing sol, dan repaint tas kulit.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Bengkel Servis Komputer, Laptop & Rakit PC Gaming Spesialis', 'description' => 'Membuka gerai perbaikan komputer kilat, upgrade SSD/RAM, dan perakitan PC gaming/desain berpendingin cairan.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Jasa Cleaning Service Properti Panggilan Rumah & Kantor via WhatsApp', 'description' => 'Penyedia jasa pembersihan kasur tungau hydro-vacuum, poles lantai keramik, dan pembersihan rumah pasca renovasi.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Usaha Katering Harian Box Sehat & Bento Kantoran via Instagram', 'description' => 'Menyediakan katering kalori terhitung, menu bento higienis, dan rantangan keluarga dengan bahan segar lokal.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Rumah Jahit Kustom Busana Pesta, Kebaya Wisuda, & Permak Cepat', 'description' => 'Menerima pesanan jahit baju kebaya wisuda pas badan, payet mutiara estetik, dan permak celana jeans kilat.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Gerai Sablon DTF Kaos Satuan & Souvenir Merchandise Custom', 'description' => 'Menyediakan sablon kaos print satuan tanpa minimal order, cetak tote bag kanvas, mug keramik, dan gantungan kunci.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Jasa Pemasangan & Paket Pengawasan CCTV untuk Toko & Perumahan', 'description' => 'Menyediakan paket instalasi kamera CCTV lengkap dengan monitoring streaming via aplikasi smartphone.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Rental Alat Wisata Luar Ruangan & Sewa Tenda Camping Gunung', 'description' => 'Menyewakan tenda dome waterproof, kompor gas portabel, matras foil, dan headlamp untuk pendaki pemula.'],

            // =========================================================================
            // 2. INVESTIGATIVE (I) — Analitis, Riset, Logika Pemrograman, & Data
            // =========================================================================
            // Jurusan Kuliah (D3 / D4 / S1)
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Informatika / Rekayasa Perangkat Lunak', 'description' => 'Mendalami arsitektur software engineering, struktur data algoritma tingkat lanjut, dan distributed computing.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'D4 Teknologi Rekayasa Perangkat Lunak Aplikasi', 'description' => 'Pendidikan vokasi terapan perancangan sistem web enterprise, arsitektur RESTful API, dan database backend.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Keamanan Siber (Cybersecurity & Forensic Computing)', 'description' => 'Mempelajari benteng pertahanan digital, penetration testing, kriptografi, dan mitigasi serangan siber.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Sains Data & Analitika Bisnis (Data Science)', 'description' => 'Mengolah big data, machine learning, statistika komputasi, dan pemodelan prediktif untuk keputusan bisnis.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Sistem Informasi Akuntansi & Audit Komputer', 'description' => 'Mengintegrasikan audit forensik laporan keuangan dengan pengawasan sistem informasi database ERP.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Teknologi Pangan & Riset Pengendalian Mutu (QC)', 'description' => 'Menganalisis kimia pangan, uji laboratorium mikrobiologi, masa kedaluwarsa, dan sertifikasi HACCP/Halal.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Akuntansi Bisnis Digital & FinTech Terapan', 'description' => 'Mendalami perancangan sistem keuangan digital, evaluasi kelayakan investasi, dan algoritma transaksi finansial.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'D4 Rekayasa Instrumentasi & Otomasi Kontrol Industri', 'description' => 'Mempelajari analisa data sensorik, Programmable Logic Controller (PLC), dan integrasi sistem SCADA.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Kecerdasan Buatan & Pemrosesan Bahasa Alami (AI/NLP)', 'description' => 'Mempelajari pembuatan model deep learning, algoritma chatbot cerdas, dan computer vision untuk industri.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'D3 Komputerisasi Akuntansi & Pengolahan Spreadsheet', 'description' => 'Pendidikan praktis pengolahan spreadsheet keuangan lanjutan, formula makro Excel, dan software Accurate/MYOB.'],

            // Profesi / Karier di Dunia Kerja
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Junior Web Backend & Frontend Developer', 'description' => 'Menulis dan merapikan kode program aplikasi web menggunakan framework modern (Laravel, React, atau Vue.js).'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Junior Mobile Application Developer (Android/iOS)', 'description' => 'Mengembangkan aplikasi smartphone menggunakan Flutter, Kotlin, atau React Native di bawah supervisi senior.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Software QA Tester Pemula (Bug Hunter & Automation)', 'description' => 'Mencoba fitur aplikasi, mendokumentasikan error/bug, dan menguji integrasi API berdasarkan dokumen SOP pengujian.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Junior Data Analyst & Visualisasi Dashboard Bisnis', 'description' => 'Mengolah data penjualan dengan SQL/Python dan menyajikan laporan visual interaktif di Power BI atau Looker Studio.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Junior Database Administrator (DBA)', 'description' => 'Menjaga integritas data MySQL/PostgreSQL, melakukan backup rutin, dan mengoptimasi query database lambat.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Asisten Auditor Finansial & Forensik di KAP', 'description' => 'Melakukan sampling transaksi kas, vouching kelengkapan kuitansi, dan mencocokkan saldo buku besar klien.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Staf Pengendali Mutu Pangan & Laboratorium (QC Laboran)', 'description' => 'Menguji kadar keasaman pH makanan, memeriksa cemaran bakteri adonan kue, dan mengawasi sanitasi dapur.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Junior Network Security & Penetration Testing Assistant', 'description' => 'Membantu pengujian celah keamanan jaringan lokal (LAN/WLAN) menggunakan tools ethical hacking tersertifikasi.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Market Research Analyst & Pengolah Angket Konsumen', 'description' => 'Menyebarkan kuesioner riset produk, memetakan demografi target pasar, dan menganalisis preferensi harga.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Staf Technical Helpdesk IT & Troubleshooting Software', 'description' => 'Menganalisis penyebab crash aplikasi komputer kantor dan memandu staf mengatasi error sistem operasional.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Spesialis Konfigurasi SEO Teknis & Web Performance', 'description' => 'Menganalisis kecepatan loading website, struktur schema markup, dan optimasi algoritma mesin pencari Google.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Staf Analis Rekam Medis & Kodifikasi Penyakit Digital', 'description' => 'Menganalisis riwayat rekam medis pasien di rumah sakit dan menginput kode diagnosis sesuai standar ICD-10.'],

            // Peluang Usaha / Wirausaha
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Mendirikan Software House Mandiri (Jasa Web, Apps, & POS)', 'description' => 'Menyediakan layanan pembuatan website profil bisnis, toko online terintegrasi, dan software kasir UMKM.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Layanan Otomasi Chatbot WhatsApp & Integrasi Sistem Toko', 'description' => 'Membangun sistem balasan otomatis cerdas untuk toko online guna mempercepat layanan pelanggan 24 jam.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Kantor Jasa Pembukuan & Akuntansi Berbasis Cloud UMKM', 'description' => 'Mendirikan usaha layanan pencatatan kas harian dan penyusunan laporan laba rugi bulanan untuk pemilik usaha ritel.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Konsultan Audit Keamanan Website & Backup Database', 'description' => 'Menawarkan jasa pengecekan celah keamanan WordPress, perbaikan web kena malware, dan perlindungan server.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Jasa Riset Analisis Kompetitor & Pemetaan Peluang Bisnis Daring', 'description' => 'Membantu pemilik brand menganalisis kata kunci terlaris di Shopee/Tokopedia dan strategi produk kompetitor.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Laboratorium Uji Formulasi Resep Kuliner & Uji Umur Simpan (Shelf-Life)', 'description' => 'Menyediakan jasa konsultasi rekayasa pengawetan makanan alami, uji ketahanan jamur roti, dan kemasan kedap udara.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Jasa Pembuatan Template Notion & Sistem Spreadsheet Manajemen Proyek', 'description' => 'Merancang template manajemen tugas dan dashboard keuangan terstruktur untuk dijual ke pasar digital global.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Studio Pembuatan Game Edukasi & Interaktif Pembelajaran', 'description' => 'Membangun game teka-teki logika dan aplikasi edukatif untuk anak sekolah berbasis engine Unity.'],

            // =========================================================================
            // 3. ARTISTIC (A) — Kreatif, Desain Visual, Seni, Mode, & Estetika
            // =========================================================================
            // Jurusan Kuliah (D3 / D4 / S1)
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Desain Komunikasi Visual (DKV)', 'description' => 'Mempelajari komunikasi grafis, tipografi ekspresif, perancangan identitas visual brand, dan ilustrasi digital.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Animasi, Film, & Efek Visual Digital (VFX)', 'description' => 'Mendalami perancangan karakter 2D/3D, sinematografi, visual effect, dan proses pasca produksi video.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Desain Mode & Tata Busana (Fashion Design)', 'description' => 'Mendalami perancangan koleksi mode adibusana, peramalan tren gaya dunia, dan inovasi siluet busana.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'D4 Seni Roti, Pastry, & Bakery Komersial (Cake Decoration)', 'description' => 'Pendidikan vokasi teknik pembuatan aneka roti artisan Eropa, laminated dough, dan seni dekorasi kue tart modern.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Desain Media Interaktif, Game, & UI/UX Digital', 'description' => 'Mempelajari interaksi manusia dan komputer, estetika antarmuka grafis aplikasi, dan desain dunia gim.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Fotografi Komersial & Sinematografi Digital', 'description' => 'Mempelajari tata pencahayaan studio foto, pengoperasian kamera sinema, dan penyutradaraan video komersial.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'D4 Desain Grafis & Teknologi Cetak Komersial', 'description' => 'Vokasi perancangan tata letak kemasan produk ritel, separasi warna cetak offset, dan branding visual.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Kriya Tekstil & Desain Aksesoris Mode', 'description' => 'Mempelajari seni batik kontemporer, tenun kriya modern, pembuatan tas kulit kriya, dan perhiasan mode.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'D4 Seni Kuliner & Gastronomi Terapan', 'description' => 'Mendalami estetika penyajian hidangan (food plating), kreasi resep orisinal modern, dan seni mencicipi rasa.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'D3 Tata Busana & Pembuatan Pola Pakaian', 'description' => 'Pendidikan praktis menjahit pakaian wanita, teknik draping kain, dan pembuatan busana kerja sehari-hari.'],

            // Profesi / Karier di Dunia Kerja
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Junior Graphic Designer Konten Media Sosial & Toko Online', 'description' => 'Mendesain materi promosi harian seperti feed Instagram, banner marketplace, pamflet, dan katalog digital.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Junior Video Editor Konten Pendek Reels/TikTok/YouTube', 'description' => 'Memotong footage video mentah, memilih background music dinamis, dan menambahkan transisi serta teks menarik.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Junior UI/UX Designer Tampilan Aplikasi Mobile & Website', 'description' => 'Mendesain antarmuka aplikasi di software Figma yang mudah digunakan pengguna dan enak dipandang secara visual.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Fotografer Produk & Retoucher Foto Studio', 'description' => 'Mengambil foto produk UMKM di studio mini dan melakukan retouching warna menggunakan Adobe Lightroom/Photoshop.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Food Stylist & Penata Hidangan Pemotretan Komersial', 'description' => 'Menata tampilan makanan di atas piring agar terlihat menggugah selera untuk kebutuhan foto menu restoran dan iklan.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Asisten Pembuat Pola Busana Digital (Pattern Maker)', 'description' => 'Menerjemahkan gambar sketsa busana menjadi pola potongan kain presisi menggunakan software CAD busana.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Junior Wardrobe Assistant & Kru Penata Busana Studio', 'description' => 'Membantu menyiapkan padu padan setelan pakaian model, menyetrika uap busana, dan mengurus busana pemotretan.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Asisten Animator 2D & Motion Graphic Designer', 'description' => 'Membuat animasi logo bergerak, teks grafis dinamis bumper video, dan elemen animasi kartun untuk video promosi.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Visual Merchandiser (VM) Toko Ritel & Butik Mode', 'description' => 'Mendandani patung manekin di etalase toko pakaian dan menata pencahayaan pajangan agar menarik perhatian.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Cake Decorator & Artist Kue Ulang Tahun Tematik', 'description' => 'Menghias kue tart menggunakan fondant, buttercream artistik, dan lukisan edible untuk pesta perayaan.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Ilustrator Vektor & Desainer Karakter Buku Cerita', 'description' => 'Menggambar ilustrasi karakter dengan pen tablet digital untuk buku anak, stiker chat, dan kemasan produk.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Barista Seni Latte Art di Kafe Kopi Spesialti', 'description' => 'Membuat lukisan busa susu (latte art) pola tulip/rosetta di cangkir kopi dan meracik minuman estetik.'],

            // Peluang Usaha / Wirausaha
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Studio Desain Visual Kreatif / Jasa Branding & Logo UMKM', 'description' => 'Membuka studio jasa perancangan identitas visual merek, maskot, kemasan makanan, dan kampanye digital.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Kreator Penjual Aset Grafis Digital di Pasar Dunia (Freepik/Envato)', 'description' => 'Menjual karya ilustrasi vektor, template sosial media, dan aset visual 3D di pasar kreatif dunia berpenghasilan dolar.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Studio Foto & Video Sinematik Pernikahan / Wisuda Intimate', 'description' => 'Menyediakan paket dokumentasi wisuda sekolah, prewedding romantis, dan liputan acara keluarga dengan visual sinematik.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Toko Roti Artisan, Donat Kentang Premium, & Custom Tart Cake', 'description' => 'Memproduksi donat empuk aneka topping estetik dan kue tart hias ulang tahun kustom berbasis pesanan online.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Merintis Brand Fashion Streetwear & Hijab Printing Desain Sendiri', 'description' => 'Memproduksi pakaian kaos oversized dengan sablon artistik atau hijab motif eksklusif karya orisinal.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Studio Kerajinan Aksesoris Mode & Souvenir Pernikahan Kustom', 'description' => 'Membuat perhiasan manik-manik resin cantik, bros hijab mutiara, dan undangan pernikahan visual estetik.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Jasa Pembuatan Konten Video Pendek Animasi & Promosi Bisnis', 'description' => 'Menyediakan paket video reels animasi promosi produk untuk toko online dan pedagang kuliner lokal.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Studio Pembuat Game Independen Skala Mikro', 'description' => 'Memproduksi dan memonetisasi gim kasual orisinal untuk pasar smartphone Android di Google Play Store.'],

            // =========================================================================
            // 4. SOCIAL (S) — Komunikasi, Pelayanan, Keramahan, & Kepedulian
            // =========================================================================
            // Jurusan Kuliah (D3 / D4 / S1)
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D4 Manajemen Perhotelan & Pengelolaan Resor Internasional', 'description' => 'Pendidikan vokasi pengelolaan kepuasan tamu, etika hospitaliti internasional, dan manajemen hubungan tamu hotel.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'S1 Destinasi Pariwisata & Komunikasi Ekowisata', 'description' => 'Mempelajari tata kelola daya tarik wisata alam, promosi pariwisata ramah lingkungan, dan pemberdayaan masyarakat lokal.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'S1 Manajemen Sumber Daya Manusia (SDM & HRD)', 'description' => 'Mempelajari pengelolaan rekrutmen karyawan, pembinaan hubungan industrial, pelatihan kerja, dan budaya kekeluargaan.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D4 Pengelolaan Konvensi & Perhelatan Acara (MICE Management)', 'description' => 'Pendidikan vokasi perencanaan festival seni, pameran expo industri, dan seminar korporat bertaraf internasional.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'S1 Ilmu Komunikasi Pemasaran & Hubungan Masyarakat (PR)', 'description' => 'Mempelajari seni berbicara di depan publik, pembentukan reputasi produk, kehumasan, dan media relations.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D4 Manajemen Tata Hidang & Restoran (F&B Service)', 'description' => 'Mempelajari tata cara jamuan resmi (banquet), etika table manner, pelayanan pramusaji bintang 5, dan mixology mocktail.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D3 Kesekretariatan & Humas Komunikasi Korporat', 'description' => 'Pendidikan vokasi tata naskah dinas, penataan agenda pimpinan, dan pelayanan komunikasi tamu dinas resmi.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D3 Usaha Perjalanan Wisata & Kepemanduan Wisatawan', 'description' => 'Pendidikan praktis pemesanan tiket penerbangan GDS, penentuan rute wisata efisien, dan kepemanduan grup.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'S1 Pendidikan Vokasi Kejuruan (Guru SMK Berkompetensi)', 'description' => 'Mempersiapkan tenaga pendidik profesional yang melatih generasi muda dalam kompetensi keahlian kejuruan.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D4 Kebijakan Pelayanan Publik & Administrasi Layanan Komunitas', 'description' => 'Fokus pada peningkatan kualitas kepuasan masyarakat terhadap loket pelayanan perizinan dan publik.'],

            // Profesi / Karier di Dunia Kerja
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Front Desk Agent / Resepsionis Hotel Berbintang', 'description' => 'Melayani proses check-in/out tamu di meja lobi dengan senyuman ramah, membuat kunci kartu, dan pembayaran tagihan.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Junior Tour Guide Lokal & Pemandu Budaya Berlisensi', 'description' => 'Memandu wisatawan di objek wisata sejarah/alam daerah dan menjelaskan nilai kearifan lokal secara menyenangkan.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Tour Leader Pendamping Rombongan Wisata & Studi Tur', 'description' => 'Mendampingi kelompok wisatawan sepanjang perjalanan dari bandara asal hingga kembali dengan aman dan ceria.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Customer Service Representative & Layanan Keluhan Tamu', 'description' => 'Mendengarkan aspirasi pelanggan, memecahkan keluhan produk dengan empati, dan menjaga kepuasan konsumen.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Teller & Customer Service Perbankan (Frontliner)', 'description' => 'Melayani setoran tunai nasabah, pembukaan buku rekening, dan transaksi kas dengan etika pelayanan prima.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Pramusaji / Waiter Restoran Fine Dining & Banquet Hotel', 'description' => 'Menyajikan hidangan sarapan buffet, mengantarkan pesanan room service ke kamar tamu, dan menyapa tamu dengan sopan.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Airport Representative / Staf Penyambut Tamu di Bandara', 'description' => 'Menyambut kedatangan tamu turis di terminal bandara, mengatur koper bawaan, dan mengoordinasikan armada shuttle bus.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Staf Administrasi HRD & Rekrutmen Karyawan Baru', 'description' => 'Membantu menjadwalkan tes wawancara pelamar kerja, menyambut kandidat, dan mengelola berkas karyawan.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Staf Event Organizer Outbound & Fasilitator Teambuilding', 'description' => 'Memandu games seru keakraban kelompok karyawan perusahaan, memimpin yel-yel, dan mengurus kenyamanan peserta.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Barista Spesialis Percakapan Ramah & Interaksi Pelanggan', 'description' => 'Menyapa pengunjung kafe, merekomendasikan racikan minuman favorit, dan menciptakan suasana kafe yang hangat.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Resepsionis & Penerima Tamu Kantor Perusahaan Swasta', 'description' => 'Menyambut tamu mitra bisnis dengan tata bahasa sopan, menerima panggilan telepon masuk, dan mengantar tamu ke ruang rapat.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Instruktur / Asisten Pelatih Keterampilan Praktis SMK', 'description' => 'Mendampingi adik kelas atau peserta kursus dalam latihan keterampilan praktis kejuruan di laboratorium.'],

            // Peluang Usaha / Wirausaha
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Perintis Biro Open Trip Online Wisata Alternatif & Glamping', 'description' => 'Membuka usaha perjalanan wisata kelompok kecil ke destinasi alam tersembunyi, camping, dan glamping privat.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Pengelola Bisnis Penginapan Homestay & Kost Ramah Tamu via AirBnB', 'description' => 'Mengelola unit kamar homestay ramah turis dengan pelayanan hangat khas lokal dan review bintang 5.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Penyedia Jasa Tenaga Layanan Resepsionis & Waiter Acara Pesta', 'description' => 'Menyediakan tim pramusaji dan penerima tamu profesional terlatih untuk perhelatan jamuan resepsi pernikahan.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Lembaga Kursus Keterampilan Kejuruan & Bimbingan Minat Bakat', 'description' => 'Membuka kelas kursus menjahit, barista kopi pemula, atau les komputer dasar untuk remaja lingkungan sekitar.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Penyedia Jasa Event Organizer Ulang Tahun Anak & Perayaan Keluarga', 'description' => 'Merancang acara pesta ulang tahun meriah lengkap dengan pembawa acara badut ramah, dekorasi balon, dan game.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Kedai Kopi Komunitas & Ruang Diskusi Pemuda Estetik', 'description' => 'Membuka kafe tempat berkumpul komunitas hobi dengan suasana bersahabat dan harga menu terjangkau.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Jasa Pemandu Jelajah Budaya & Wisata Kuliner Khas Daerah', 'description' => 'Menawarkan paket wisata berjalan kaki (walking tour) mencicipi makanan legendaris dan mengunjungi cagar budaya lokal.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Agensi Penyedia Layanan Customer Support & Chatbot Agent Outsource', 'description' => 'Menyediakan tim admin penjawab chat ramah bagi toko online yang membutuhkan balasan pesan pelanggan 24 jam.'],

            // =========================================================================
            // 5. ENTERPRISING (E) — Bisnis, Kepemimpinan, Penjualan, & Inisiatif
            // =========================================================================
            // Jurusan Kuliah (D3 / D4 / S1)
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Bisnis Digital & E-Commerce Terapan', 'description' => 'Memadukan strategi pemasaran modern dengan ekosistem teknologi internet, marketplace daring, dan analisis transaksi.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Manajemen Pemasaran & Periklanan Komersial', 'description' => 'Mendalami seni promosi persuasif, perilaku konsumen, riset tren pasar global, dan manajemen merek korporat.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'D4 Pemasaran Digital & Manajemen Ritel Vokasi', 'description' => 'Pendidikan vokasi pada praktik live-stream commerce, manajemen promosi media sosial, dan negosiasi ritel.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Manajemen Bisnis Kuliner & Restoran Waralaba', 'description' => 'Mempelajari kelayakan finansial usaha makanan, standardisasi resep waralaba, dan ekspansi cabang gerai kuliner.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Bisnis & Manajemen Retail Mode Busana', 'description' => 'Mempelajari strategi pemasaran merek pakaian, rantai pasok industri tekstil, dan merchandising butik mode.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Manajemen Keuangan & Pasar Modal Korporat', 'description' => 'Mempelajari analisis portofolio saham, valuasi investasi, manajemen permodalan, dan pendanaan ekspansi usaha.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Bisnis Perhotelan & Manajemen Properti Investasi', 'description' => 'Mempelajari tata kelola resort mewah, manajemen pendapatan kamar (revenue management), dan ekspansi jaringan hotel.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'D3 Manajemen Perdagangan & Distribusi Produk Konsumen', 'description' => 'Pendidikan vokasi tata niaga barang dagangan, saluran distribusi produk konsumen, dan teknik salesmanship.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Kewirausahaan & Manajemen Inovasi Startup', 'description' => 'Fokus pada perancangan model bisnis lean canvas, validasi produk ke konsumen, dan pencarian pendanaan modal.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'D4 Manajemen Logistik Bisnis & Ekspor Impor', 'description' => 'Mempelajari tata kelola pengiriman kargo internasional, perizinan kepabeanan cukai, dan kontrak niaga global.'],

            // Profesi / Karier di Dunia Kerja
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Live Streamer Jualan Kreatif (TikTok / Shopee Live)', 'description' => 'Menjadi pembawa acara siaran langsung interaktif untuk mempromosikan dan menjual produk secara persuasif.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Digital Marketing Specialist & Pengelola Iklan Berbayar (Ads)', 'description' => 'Merancang dan memantau performa kampanye iklan berbayar di platform Meta Ads, Google Ads, dan TikTok Ads.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Store Supervisor / Asisten Kepala Toko Ritel Modern', 'description' => 'Memimpin tim kasir dan pramuniaga toko, memantau pencapaian target omzet harian, dan menjaga standar display.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Junior Sales / Merchant Acquisition Specialist Lapangan', 'description' => 'Membantu mengenalkan dan mendaftarkan mitra pedagang baru ke dalam ekosistem aplikasi/platform perbankan.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Social Media Admin & Content Care Toko Online', 'description' => 'Mengelola pesan masuk, membalas komentar calon pembeli, dan posting konten promosi harian di akun media sosial toko.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Koordinator Sales & Pemasaran Paket Wisata Biro Perjalanan', 'description' => 'Menawarkan paket liburan keluarga dan instansi perusahaan dengan target closing penjualan yang jelas.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Restoran Supervisor Trainee di Jaringan Resto Waralaba', 'description' => 'Mengawasi kecepatan penyajian pesanan makanan di kasir, kepuasan pengunjung, dan pencatatan kas harian.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Staf Penagihan & Negosiasi Piutang (Account Receivable Officer)', 'description' => 'Menghubungi klien perusahaan untuk mengonfirmasi pembayaran faktur jatuh tempo dengan teknik komunikasi lugas.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Fashion Merchandiser & Buyer Pakaian Ritel', 'description' => 'Menganalisis tren busana yang sedang digemari pasar dan memilih model baju yang akan distok oleh butik toko.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Public Relations Officer & Media Partner Coordinator', 'description' => 'Menghubungi media massa, influencer, dan mitra sponsor untuk mempromosikan kegiatan kampanye brand.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Operations Team Leader di Gerai Minimarket / Supermarket', 'description' => 'Memastikan kelancaran operasional toko dari buka hingga tutup, briefing pagi tim, dan pengawasan stok barang.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Business Development Associate Pemula', 'description' => 'Melakukan riset peluang kemitraan baru, menyusun proposal kerja sama bisnis, dan presentasi ke calon klien.'],

            // Peluang Usaha / Wirausaha
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Owner Bisnis E-Commerce & Dropshipper Brand Sendiri', 'description' => 'Membangun merek produk konsumen sendiri dengan pemasaran langsung ke pengguna melalui marketplace.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Perintis Digital Marketing & Content Agency Skala UMKM', 'description' => 'Menyediakan jasa foto produk profesional, pengelolaan akun media sosial, dan penayangan iklan bagi pedagang lokal.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Minimarket Lingkungan Mandiri dengan Kasir Digital QRIS', 'description' => 'Membuka toko kelontong modern yang menjual sembako lengkap dengan barcode scanner dan pembayaran non-tunai.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Distributor / Agen Pemasok Kemasan Kardus & Plastik Packing Toko Online', 'description' => 'Menjadi pemasok bubble wrap, kardus packing, botol kemasan, dan isolasi bagi ratusan seller online sekitar.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Kedai Minuman Franchise Kopi Susu & Teh Buah Segar Kekinian', 'description' => 'Membuka stan minuman segar di lokasi strategis dekat sekolah atau perkantoran dengan sistem operasional praktis.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Mendirikan Brand Hijab & Busana Gamis Muslimah via Shopee Live', 'description' => 'Memproduksi pakaian muslimah modis secara massal dan menjualnya secara agresif lewat siaran live streaming.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Agensi Event Organizer & Pameran Usaha Mikro (Bazaar Kuliner)', 'description' => 'Menyewakan stan bazar di mall atau lapangan kota dan mengumpulkan puluhan pedagang kuliner dalam satu festival.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Rental Mobil Wisata & Armada Shuttle Elf Wisatawan Liburan', 'description' => 'Menyediakan jasa sewa armada mobil pariwisata bersih beserta supir profesional berpengalaman.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Toko Retail Pakaian Kaos Polos & Pusat Sablon Cepat Grosir', 'description' => 'Menjual bahan kaos katun combed berbagai warna untuk para pelaku usaha sablon dan komunitas motor/sekolah.'],

            // =========================================================================
            // 6. CONVENTIONAL (C) — Terstruktur, Ketelitian, Administrasi, & Keuangan
            // =========================================================================
            // Jurusan Kuliah (D3 / D4 / S1)
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'S1 Akuntansi Bisnis Digital & FinTech', 'description' => 'Mendalami penyusunan laporan keuangan digital, audit kepatuhan, pelaporan IFRS, dan perpajakan modern.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D4 Akuntansi Perpajakan Sektor Publik & Korporat', 'description' => 'Pendidikan vokasi spesialis perancangan laporan pajak perusahaan, pemeriksaan fiskal, dan audit perpajakan terapan.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'S1 Administrasi Bisnis Digital & Tata Kelola Kantor', 'description' => 'Menyiapkan tata kelola operasional kantor modern berbasis sistem komputasi terpadu dan efisiensi SOP korporat.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D4 Administrasi Perkantoran & Sekretaris Eksekutif', 'description' => 'Vokasi pengelolaan basis data arsip korporat, dokumentasi legalitas, otomasi korespondensi, dan protokoler rapat.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D4 Perbankan Syariah & Lembaga Keuangan Terapan', 'description' => 'Pendidikan vokasi operasional perbankan syariah, manajemen pembiayaan nasabah, dan kepatuhan regulasi OJK.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'S1 Manajemen Logistik & Tata Kelola Pengadaan (Procurement)', 'description' => 'Mempelajari administrasi pencatatan inventaris gudang, alur dokumen tender pengadaan barang, dan rantai pasok.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D4 Manajemen Informasi Kesehatan / Rekam Medis Digital', 'description' => 'Vokasi pengelolaan kerahasiaan data rekam medis pasien rumah sakit, kodifikasi penyakit, dan arsip digital klinik.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D3 Komputerisasi Akuntansi & Software Bisnis Terpadu', 'description' => 'Pendidikan praktis pengoperasian software akuntansi Accurate, MYOB, SAP ERP, dan spreadsheet database.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D4 Manajemen Pengendalian Mutu Produksi Garmen (QC Compliance)', 'description' => 'Vokasi audit mutu standar ekspor pakaian, penulisan lembar spesifikasi teknis jahit, dan inspeksi defect garmen.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D3 Kearsipan & Dokumentasi Informasi Digital Perkantoran', 'description' => 'Pendidikan vokasi penataan arsip fisik dengan sistem desimal, digitalisasi dokumen scanner, dan proteksi berkas rahasia.'],

            // Profesi / Karier di Dunia Kerja
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Junior Bookkeeper / Staf Pembukuan Keuangan Harian', 'description' => 'Mencatat transaksi keuangan kas kecil, mencocokkan rekening koran bank, dan menyusun jurnal umum harian.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Administrasi Pajak Pemula di Kantor Konsultan Pajak', 'description' => 'Membantu merapikan nota, validasi e-Faktur PPN, penyiapan bukti potong PPh 21/23, dan e-SPT tahunan.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Admin Kantor / Data Entry Clerk Teknis', 'description' => 'Mengetik dokumen resmi, memasukkan data transaksi pelanggan, dan mengarsipkan berkas digital secara teratur.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Administrasi HRD & Penggajian Karyawan (Payroll Assistant)', 'description' => 'Merekapitulasi absensi finger scan, menghitung uang lembur, dan menyiapkan dokumen kontrak kerja karyawan.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Administrasi Pergudangan & Stock Opname (Inventory Clerk)', 'description' => 'Mencatat mutasi keluar masuk barang di gudang dan mencocokkan jumlah stok fisik dengan sistem komputer.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Kasir Perusahaan & Pengelola Kas Kecil (Petty Cashier)', 'description' => 'Mengelola uang kas kecil untuk kebutuhan belanja operasional harian kantor dan membuat laporan pertanggungjawaban.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Sekretaris Junior / Personal Assistant Pimpinan Cabang', 'description' => 'Mengatur jadwal rapat pimpinan, membuat notula pertemuan resmi, dan menyiapkan tiket perjalanan dinas.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Ticketing & Reservasi Sistem Global Maskapai (Amadeus/Sabre)', 'description' => 'Membantu memesankan tiket penerbangan dan voucher kamar hotel lewat sistem reservasi online terpadu.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Pengendali Dokumen Mutu (Document Controller ISO/SOP)', 'description' => 'Mengarsip dan memastikan setiap formulir standar kerja ISO di perusahaan tercatat dengan nomor registrasi yang benar.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Junior Compliance & Verifikator Berkas Pembiayaan Kredit', 'description' => 'Memeriksa kelengkapan KTP, slip gaji, dan kartu keluarga nasabah pemohon pinjaman sebelum disetujui analis.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Quality Control Jahitan & Cek Ukuran Size Chart Garmen', 'description' => 'Mengukur panjang lingkar dada/pinggang baju sampel dengan pita ukur dan mencatat selisih toleransi jahit.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Operator Entri Data Gudang Farmasi & Apotek Terintegrasi', 'description' => 'Memasukkan nomor batch obat masuk, masa kedaluwarsa sirup/tablet, dan mencetak nota resep dokter.'],

            // Peluang Usaha / Wirausaha
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Biro Jasa Pengurusan NIB Usaha, NPWP, & Sertifikasi Halal Online', 'description' => 'Membantu pelaku UMKM membuat Nomor Induk Berusaha lewat OSS, pendaftaran BPJS Ketenagakerjaan, dan sertifikat halal.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Jasa Perhitungan & Pelaporan SPT Tahunan Pajak UMKM & Pribadi', 'description' => 'Membantu pemilik toko dan wajib pajak orang pribadi menyusun laporan omzet dan pelaporan SPT tahunan secara sah.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Agen Mandiri Layanan Keuangan Digital & Transaksi Loket (BRILink / Agen Mandiri)', 'description' => 'Mengelola loket resmi transaksi transfer uang, setor tunai tanpa kartu, pembayaran tagihan listrik, BPJS, dan cicilan.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Agensi Virtual Assistant Administrasi & Penataan Arsip Cloud', 'description' => 'Membuka agensi asisten administrasi jarak jauh yang melayani pebisnis daring dalam merapikan invoice dan jadwal.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Toko Alat Tulis Kantor (ATK), Kertas Struk Thermal Kasir, & Fotokopi', 'description' => 'Menjual kertas kasir roll, ordner dokumen, stempel kilat, dan layanan cetak dokumen administrasi kantor lokal.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Jasa Pengetikan Kilat, Format Skripsi / Tugas Akhir, & Penerjemahan Dokumen', 'description' => 'Membantu mahasiswa dan instansi merapikan format tulisan, daftar pustaka otomatis Mendeley, dan penataan margin rapi.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Usaha Percetakan Buku Nota Kontan Berporporasi, Kuitansi, & Formulir', 'description' => 'Mencetak buku nota penjualan dua/tiga rangkap dengan nomor urut porporasi khusus untuk warung dan toko grosir.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Jasa Pengelolaan Arsip Digital & Scan Dokumen Legal Perusahaan', 'description' => 'Menyediakan layanan pemindaian dokumen fisik ratusan lembar menjadi PDF berindeks rapi yang mudah dicari di komputer.'],
        ];

        // Kosongkan dan isi ulang tabel career_recommendations
        CareerRecommendation::truncate();

        foreach ($recommendations as $data) {
            CareerRecommendation::create([
                'riasec_code' => $data['riasec_code'],
                'category'    => $data['category'],
                'name'        => $data['name'],
                'description' => $data['description'],
                'status'      => 'active',
            ]);
        }
    }
}
