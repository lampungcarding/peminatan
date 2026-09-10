<?php

namespace Database\Seeders;

use App\Models\CareerRecommendation;
use Illuminate\Database\Seeder;

class CareerRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Master Data Rekomendasi Terpadu Fresh Graduate SMK (Entry-Level Realistis):
     * PPLG, TJKT, Pemasaran, Manajemen Perkantoran, Akuntansi Keuangan Lembaga,
     * Usaha Layanan Wisata, Perhotelan, Kuliner, Desain Komunikasi Visual, Busana.
     */
    public function run(): void
    {
        $recommendations = [
            // ================= REALISTIC (R) =================
            // Jurusan Kampus
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'S1 Teknik Komputer', 'description' => 'Mempelajari rekayasa perangkat keras, telekomunikasi modern, arsitektur server, dan telematika.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'description' => 'Pendidikan vokasi pada perancangan jaringan fiber optik, transmisi seluler, dan routing switching enterprise.'],
            ['riasec_code' => 'R', 'category' => 'jurusan', 'name' => 'D4 Manajemen Kuliner / Tata Boga', 'description' => 'Vokasi teknik pengolahan masakan barat/nusantara, higiene sanitasi, dan manajemen dapur.'],

            // Profesi Industri (Entry-Level SMK)
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Teknisi Lapangan / Field Engineer ISP Internet', 'description' => 'Pekerjaan taktis memasang kabel jaringan internet dan setting router Wi-Fi pelanggan.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Room Attendant / Housekeeping Staff Hotel', 'description' => 'Membersihkan, merapikan, dan merawat kelayakan fasilitas kamar hotel sesuai standar industri.'],
            ['riasec_code' => 'R', 'category' => 'profesi', 'name' => 'Commis Chef / Cook Helper (Pembantu Dapur)', 'description' => 'Membantu menyiapkan bahan masakan, memotong sayur/daging, dan menjaga kebersihan dapur profesional.'],

            // Peluang Wirausaha
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Penyedia Jasa Instalasi Jaringan Internet Kantor/RT-RW Net lokal', 'description' => 'Menyediakan layanan instalasi WiFi terkelola, penarikan kabel fiber optik, dan pemeliharaan jaringan.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Jasa Layanan Kebersihan Properti (Cleaning Service) Panggilan via WhatsApp', 'description' => 'Menyediakan jasa pembersihan rumah, kamar kos, dan kantor panggilan terjangkau.'],
            ['riasec_code' => 'R', 'category' => 'usaha', 'name' => 'Usaha Katering Box Premium Sehat via Instagram', 'description' => 'Menyediakan katering kalori terhitung, menu sehat harian berlangganan bagi pekerja kantoran.'],

            // ================= INVESTIGATIVE (I) =================
            // Jurusan Kampus
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Informatika / Rekayasa Perangkat Lunak', 'description' => 'Memperdalam rekayasa arsitektur perangkat lunak, algoritma pemrograman tingkat lanjut, dan pengembangan aplikasi.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Keamanan Siber (Cybersecurity)', 'description' => 'Mengembangkan benteng pertahanan digital, investigasi penetrasi jaringan, dan proteksi server dari ancaman siber.'],
            ['riasec_code' => 'I', 'category' => 'jurusan', 'name' => 'S1 Sains Data / Bisnis Informasi', 'description' => 'Menggabungkan keahlian coding logika dengan pengolahan data analitik, analisis bisnis, dan sistem informasi.'],

            // Profesi Industri (Entry-Level SMK)
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Junior Web / Mobile Programmer', 'description' => 'Menulis & merapikan kode program standar di bawah supervisi senior programmer.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Software QA Tester Pemula (Pencari Bug)', 'description' => 'Mencoba aplikasi untuk mendeteksi error/bug berdasarkan SOP pengujian.'],
            ['riasec_code' => 'I', 'category' => 'profesi', 'name' => 'Staf Technical IT Support Magang', 'description' => 'Staf teknis pembantu untuk pemeliharaan sistem internal kantor.'],

            // Peluang Wirausaha
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Mendirikan Software House Mandiri (Jasa Web/Apps)', 'description' => 'Menyediakan layanan pembuatan aplikasi bisnis digital, point of sales (POS), dan software kasir untuk klien.'],
            ['riasec_code' => 'I', 'category' => 'usaha', 'name' => 'Membangun Studio Pembuat Game Independen Skala Mikro', 'description' => 'Memproduksi dan memonetisasi gim orisinal untuk pasar mobile (Google Play) dan PC.'],

            // ================= ARTISTIC (A) =================
            // Jurusan Kampus
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Desain Komunikasi Visual (DKV)', 'description' => 'Mempelajari komunikasi grafis, tipografi ekspresif, perancangan identitas visual brand, dan ilustrasi digital.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Animasi, Film, & Fotografi Digital', 'description' => 'Mendalami perancangan karakter 2D/3D, sinematografi, visual effect, dan dokumentasi foto.'],
            ['riasec_code' => 'A', 'category' => 'jurusan', 'name' => 'S1 Fashion Design / Tata Busana', 'description' => 'Mendalami perancangan koleksi mode kekinian, rekayasa siluet busana, dan tren fashion global.'],

            // Profesi Industri (Entry-Level SMK)
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Junior Graphic Designer Konten Harian Toko', 'description' => 'Mendesain materi promosi harian sederhana seperti brosur, pamflet, atau feed media sosial.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Junior Video Editor Konten Pendek Reels/TikTok', 'description' => 'Memotong materi video, menyelaraskan musik, dan menambahkan teks transisi untuk konten video pendek.'],
            ['riasec_code' => 'A', 'category' => 'profesi', 'name' => 'Asisten Pola & Potong Kain (Pattern Maker Assistant)', 'description' => 'Membantu menerjemahkan sketsa menjadi pola dasar fisik/digital dan memotong kain secara presisi.'],

            // Peluang Wirausaha
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Studio Visual Kreatif / Jasa Ilustrasi Kustom Mandiri', 'description' => 'Membuka studio jasa desain grafis, fotografi komersial, dan materi kampanye digital.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Kreator Penjual Aset Gambar Digital di Internet (Freepik/Shutterstock)', 'description' => 'Menjual karya ilustrasi, vektor grafis, template sosial media, dan aset visual di platform pasar kreatif dunia.'],
            ['riasec_code' => 'A', 'category' => 'usaha', 'name' => 'Agensi Kreatif Pembuat Konten Dokumentasi Video Promosi Destinasi Wisata', 'description' => 'Menyediakan layanan pembuatan video promosi destinasi pariwisata dan dokumentasi perjalanan.'],

            // ================= SOCIAL (S) =================
            // Jurusan Kampus
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'S1 Destinasi Pariwisata Digital', 'description' => 'Mempelajari tata kelola daya tarik wisata daerah, promosi pariwisata digital, dan pengembangan destinasi.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D4 Manajemen Bisnis Perjalanan Wisata', 'description' => 'Vokasi perencanaan tur penerbangan, negosiasi rekanan maskapai/hotel, dan ekspo wisata.'],
            ['riasec_code' => 'S', 'category' => 'jurusan', 'name' => 'D4 Manajemen Perhotelan', 'description' => 'Pendidikan vokasi pengelolaan divisi kamar, operasional front office, dan sanitasi komersial.'],

            // Profesi Industri (Entry-Level SMK)
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Junior Tour Guide Lokal / Pemandu Bus Rute Pendek', 'description' => 'Memandu wisatawan di objek wisata daerah atau mendampingi bus pariwisata rute pendek.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Bellboy / Concierge Pemula Penyambut Tamu', 'description' => 'Menyambut tamu hotel di pintu masuk dan membantu mengantarkan barang bawaan mereka.'],
            ['riasec_code' => 'S', 'category' => 'profesi', 'name' => 'Pramusaji / Waiter Restoran Modern', 'description' => 'Melayani pencatatan pesanan menu dan mengantarkan makanan ke meja pelanggan dengan santun.'],

            // Peluang Wirausaha
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Perintis Biro Open Trip Online Wisata Alternatif / Glamping', 'description' => 'Membuka usaha perjalanan wisata perjalanan alam terbuka dan paket camping/glamping privat.'],
            ['riasec_code' => 'S', 'category' => 'usaha', 'name' => 'Pengelola Bisnis Penginapan Homestay Mandiri via Aplikasi AirBnB', 'description' => 'Mengelola unit rumah/kamar homestay ramah turis dengan pelayanan hangat khas lokal.'],

            // ================= ENTERPRISING (E) =================
            // Jurusan Kampus
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Bisnis Digital', 'description' => 'Memadukan strategi pemasaran modern dengan ekosistem teknologi internet dan pasar daring.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Manajemen Pemasaran', 'description' => 'Mendalami seni promosi persuasif, perilaku konsumen, riset tren pasar global, dan manajemen merek.'],
            ['riasec_code' => 'E', 'category' => 'jurusan', 'name' => 'S1 Bisnis Perhotelan & Resor Internasional', 'description' => 'Mempelajari tata kelola resort mewah, manajemen pendapatan kamar, dan pemasaran hospitality.'],

            // Profesi Industri (Entry-Level SMK)
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Social Media Admin / Content Care Toko', 'description' => 'Mengelola pesan masuk, komentar, dan posting harian di akun media sosial toko/bisnis.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Live Streamer Jualan Kreatif (TikTok/Shopee)', 'description' => 'Menjadi pembawa acara siaran langsung untuk menawarkan produk di TikTok/Shopee.'],
            ['riasec_code' => 'E', 'category' => 'profesi', 'name' => 'Junior Sales / Merchant Acquisition Lapangan', 'description' => 'Membantu mengenalkan dan mendaftarkan mitra toko baru ke dalam sistem aplikasi/platform.'],

            // Peluang Wirausaha
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Owner Bisnis E-Commerce / Dropshipper Brand Sendiri', 'description' => 'Membangun merek produk konsumen sendiri dengan pemasaran langsung ke pengguna melalui marketplace.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Perintis Digital Marketing & Content Agency Skala Kecil', 'description' => 'Menyediakan jasa pengelolaan konten promosi, periklanan, dan manajemen akun jualan bagi UMKM lokal.'],
            ['riasec_code' => 'E', 'category' => 'usaha', 'name' => 'Mendirikan Bisnis Kuliner Makanan dengan Sistem Cloud Kitchen (Hanya Terima Order Online)', 'description' => 'Membuka gerai makanan viral berbasis pesanan antar (GoFood/GrabFood/ShopeeFood).'],

            // ================= CONVENTIONAL (C) =================
            // Jurusan Kampus
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'S1 Administrasi Bisnis Digital', 'description' => 'Menyiapkan tata kelola operasional kantor modern berbasis sistem komputasi terpadu.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'S1 Akuntansi Bisnis Digital / FinTech', 'description' => 'Mendalami penyusunan laporan keuangan digital, analisis investasi, dan teknologi finansial modern.'],
            ['riasec_code' => 'C', 'category' => 'jurusan', 'name' => 'D4 Akuntansi Perpajakan Negara', 'description' => 'Pendidikan vokasi spesialis perancangan laporan pajak korporat dan audit perpajakan terapan.'],

            // Profesi Industri (Entry-Level SMK)
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Admin Kantor / Data Entry Clerk Teknis', 'description' => 'Mengetik, memasukkan data pelanggan, dan mengarsipkan dokumen digital secara teratur.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Junior Bookkeeper / Staf Pembukuan Keuangan Harian', 'description' => 'Mencatat transaksi keuangan kas kecil ke program Excel atau aplikasi akuntansi digital.'],
            ['riasec_code' => 'C', 'category' => 'profesi', 'name' => 'Staf Administrasi Pajak Pemula di Kantor Konsultan Pajak', 'description' => 'Membantu merapikan nota, faktur, dan arsip dokumen pajak di Kantor Konsultan Pajak lokal.'],

            // Peluang Wirausaha
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Kantor Jasa Pembukuan & Akuntansi Berbasis Cloud untuk Toko Retail', 'description' => 'Mendirikan usaha layanan pencatatan kas harian dan penyusunan laporan keuangan bulanan untuk pedagang ritel.'],
            ['riasec_code' => 'C', 'category' => 'usaha', 'name' => 'Agensi Penyedia Jasa Virtual Assistant Mandiri', 'description' => 'Membuka agensi asisten sekretariat online yang melayani pengusaha luar negeri dan pebisnis daring.'],
        ];

        // Truncate dan re-seed rekomendasi
        CareerRecommendation::truncate();

        foreach ($recommendations as $rec) {
            CareerRecommendation::create([
                'riasec_code' => $rec['riasec_code'],
                'category' => $rec['category'],
                'name' => $rec['name'],
                'description' => $rec['description'],
                'status' => 'active',
            ]);
        }
    }
}
