<?php

namespace Database\Seeders;

use App\Models\CareerQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareerQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 48 Butir Soal Tes Minat Karier Digital (RIASEC) Berbasis 10 Jurusan SMK:
     * PPLG, TJKT, Pemasaran, Manajemen Perkantoran, Akuntansi Keuangan Lembaga,
     * Usaha Layanan Wisata, Perhotelan, Kuliner, Desain Komunikasi Visual, Busana.
     */
    public function run(): void
    {
        $questions = [
            // ================= REALISTIC (R) — 8 SOAL =================
            // Fokus: Praktis, teknis fisik, perangkat keras, perkakas kerja, operasional lapangan
            [
                'order_num' => 1,
                'type_riasec' => 'R',
                'question' => 'Saya senang merakit komponen PC komputer, memasang perangkat keras (hardware), atau membongkar perlengkapan digital.',
            ],
            [
                'order_num' => 2,
                'type_riasec' => 'R',
                'question' => 'Saya tertarik mengoperasikan mesin jahit industri, memotong pola kain, dan menjahit busana secara presisi.',
            ],
            [
                'order_num' => 3,
                'type_riasec' => 'R',
                'question' => 'Saya terampil dan menikmati menggunakan perkakas dapur profesional, kompor industri, serta pisau chef untuk mengolah masakan.',
            ],
            [
                'order_num' => 4,
                'type_riasec' => 'R',
                'question' => 'Saya senang memasang kabel fiber optic, melakukan crimping kabel LAN, dan mengatur fisik perangkat router jaringan.',
            ],
            [
                'order_num' => 5,
                'type_riasec' => 'R',
                'question' => 'Saya menyukai kegiatan memeriksa dan memastikan fungsi teknis kamera, lensa, tripod, atau lighting studio sebelum sesi pemotretan.',
            ],
            [
                'order_num' => 6,
                'type_riasec' => 'R',
                'question' => 'Saya lebih menyukai kegiatan dinamis di lapangan atau luar ruangan daripada harus duduk diam sepanjang hari di depan meja.',
            ],
            [
                'order_num' => 7,
                'type_riasec' => 'R',
                'question' => 'Saya tertarik pada pekerjaan tata graha (housekeeping), merapikan fasilitas kamar hotel, dan merawat kelayakan perabot gedung.',
            ],
            [
                'order_num' => 8,
                'type_riasec' => 'R',
                'question' => 'Saya merasa bangga ketika berhasil membuat sebuah karya fisik nyata yang berfungsi dengan baik menggunakan keterampilan tangan saya.',
            ],

            // ================= INVESTIGATIVE (I) — 5 SOAL =================
            // Fokus: Analitis, logika, riset, pemecahan masalah algoritma, analisis data, troubleshooting
            [
                'order_num' => 9,
                'type_riasec' => 'R',
                'question' => 'Saya senang menulis kode pemrograman (coding) untuk membangun fitur aplikasi, situs web, atau logika permainan (gim).',
            ],
            [
                'order_num' => 10,
                'type_riasec' => 'I',
                'question' => 'Saya tertantang mendiagnosis penyebab error (troubleshooting) pada sistem jaringan komputer, koneksi internet, atau server yang down.',
            ],
            [
                'order_num' => 11,
                'type_riasec' => 'R',
                'question' => 'Saya menikmati proses menelusuri baris-baris kode untuk menemukan dan memperbaiki kesalahan (bug) pada aplikasi komputer.',
            ],
            [
                'order_num' => 12,
                'type_riasec' => 'C',
                'question' => 'Saya senang meneliti dan menganalisis angka pada laporan keuangan untuk menemukan selisih saldo atau kejanggalan audit kas.',
            ],
            [
                'order_num' => 13,
                'type_riasec' => 'I',
                'question' => 'Saya tertarik mengevaluasi data analitik digital, seperti traffic pengunjung web, performa iklan online, atau tren kata kunci pencarian.',
            ],
            [
                'order_num' => 14,
                'type_riasec' => 'I',
                'question' => 'Saya penasaran mempelajari takaran gramasi bahan baku resep baru, kandungan nutrisi, dan reaksi kimia dalam proses pengolahan makanan.',
            ],
            [
                'order_num' => 15,
                'type_riasec' => 'I',
                'question' => 'Saya senang mempelajari perkembangan teknologi terbaru, kecerdasan buatan (AI), atau teknik pertahanan keamanan siber (cybersecurity).',
            ],
            [
                'order_num' => 16,
                'type_riasec' => 'I',
                'question' => 'Saya tertarik melakukan riset mendalam tentang latar belakang sejarah, budaya lokal, dan fakta menarik sebuah destinasi wisata.',
            ],

            // ================= ARTISTIC (A) — 8 SOAL =================
            // Fokus: Desain visual, kreativitas seni, mode busana, estetika sajian, multimedia, animasi
            [
                'order_num' => 17,
                'type_riasec' => 'A',
                'question' => 'Saya senang mendesain poster promosi, logo, pamflet, atau konten visual media sosial yang estetik menggunakan software grafis.',
            ],
            [
                'order_num' => 18,
                'type_riasec' => 'A',
                'question' => 'Saya gemar menggambar sketsa model pakaian kekinian, memadukan kombinasi warna kain, dan merancang konsep gaya busana (fashion).',
            ],
            [
                'order_num' => 19,
                'type_riasec' => 'A',
                'question' => 'Saya sangat menikmati seni menata dan menghias sajian makanan (food plating) serta kue pastry agar terlihat memikat dan indah.',
            ],
            [
                'order_num' => 20,
                'type_riasec' => 'A',
                'question' => 'Saya senang merekam video sinematik, memilih sudut kamera yang kreatif, dan mengedit video pendek dengan musik yang selaras.',
            ],
            [
                'order_num' => 21,
                'type_riasec' => 'A',
                'question' => 'Saya tertarik menggambar ilustrasi karakter 2D/3D, membuat animasi gerak, atau merancang tampilan visual antarmuka (UI) gim.',
            ],
            [
                'order_num' => 22,
                'type_riasec' => 'A',
                'question' => 'Saya suka merancang konsep dekorasi interior ruangan, pencahayaan kamar hotel, atau penataan meja perjamuan (table setting) yang mewah.',
            ],
            [
                'order_num' => 23,
                'type_riasec' => 'A',
                'question' => 'Saya sering melahirkan ide-ide kreatif out-of-the-box yang unik dan belum pernah dibuat orang lain untuk karya multimedia.',
            ],
            [
                'order_num' => 24,
                'type_riasec' => 'A',
                'question' => 'Saya senang memadupadankan busana, aksesoris, dan tren mode gaya busana untuk model foto atau peragaan busana.',
            ],

            // ================= SOCIAL (S) — 8 SOAL =================
            // Fokus: Pelayanan prima, hospitality, keramahan, komunikasi interpersonal, pendampingan
            [
                'order_num' => 25,
                'type_riasec' => 'S',
                'question' => 'Saya senang menyambut tamu dengan senyum ramah, menyapa santun, dan memastikan kenyamanan mereka selama berkunjung.',
            ],
            [
                'order_num' => 26,
                'type_riasec' => 'S',
                'question' => 'Saya bersemangat memandu rombongan wisatawan, menceritakan keunikan tempat wisata, dan menciptakan suasana tour yang menyenangkan.',
            ],
            [
                'order_num' => 27,
                'type_riasec' => 'S',
                'question' => 'Saya senang melayani pelanggan di meja restoran atau meja informasi kantor dengan tutur bahasa santun dan penuh perhatian.',
            ],
            [
                'order_num' => 28,
                'type_riasec' => 'S',
                'question' => 'Saya mudah berempati, senang mendengarkan cerita teman, dan dengan sukarela membantu mereka yang mengalami kesulitan.',
            ],
            [
                'order_num' => 29,
                'type_riasec' => 'S',
                'question' => 'Saya cepat akrab dengan orang-orang baru dari beragam latar belakang budaya dan senang membangun komunikasi yang hangat.',
            ],
            [
                'order_num' => 30,
                'type_riasec' => 'S',
                'question' => 'Saya memiliki kesabaran dalam mendengarkan dan menangani keluhan pelanggan (customer service) hingga mereka merasa dihargai.',
            ],
            [
                'order_num' => 31,
                'type_riasec' => 'S',
                'question' => 'Saya senang mengajari teman atau membagikan panduan praktis tentang suatu keterampilan agar kita bisa maju bersama-sama.',
            ],
            [
                'order_num' => 32,
                'type_riasec' => 'S',
                'question' => 'Saya menikmati peran mengoordinasikan acara kebersamaan, liburan wisata kelompok, atau perjamuan agar berlangsung meriah dan berkesan.',
            ],

            // ================= ENTERPRISING (E) — 8 SOAL =================
            // Fokus: Penjualan, digital marketing, negosiasi, strategi bisnis, wirausaha, kepemimpinan
            [
                'order_num' => 33,
                'type_riasec' => 'E',
                'question' => 'Saya percaya diri mempromosikan barang atau jasa di depan audiens, berbicara di depan umum, atau memandu siaran live streaming jualan.',
            ],
            [
                'order_num' => 34,
                'type_riasec' => 'E',
                'question' => 'Saya senang bernegosiasi harga dan persyaratan kerja sama dengan vendor atau pembeli agar mendapatkan kesepakatan terbaik.',
            ],
            [
                'order_num' => 35,
                'type_riasec' => 'E',
                'question' => 'Saya memiliki impian merintis usaha mandiri (seperti kafe kuliner, butik busana, agensi digital, atau tour travel) dan memimpinnya.',
            ],
            [
                'order_num' => 36,
                'type_riasec' => 'E',
                'question' => 'Saya tertarik menyusun strategi promosi di media sosial (TikTok, Instagram, Meta Ads) untuk melipatgandakan omzet penjualan toko.',
            ],
            [
                'order_num' => 37,
                'type_riasec' => 'E',
                'question' => 'Saya tertantang merancang paket wisata komersial dan meyakinkan calon pelanggan untuk membeli paket liburan yang ditawarkan.',
            ],
            [
                'order_num' => 38,
                'type_riasec' => 'E',
                'question' => 'Saya bersemangat memimpin tim kerja, membagi tanggung jawab, dan memotivasi anggota kelompok agar target proyek tercapai.',
            ],
            [
                'order_num' => 39,
                'type_riasec' => 'E',
                'question' => 'Saya cepat menangkap peluang tren pasar yang sedang viral dan berpikir bagaimana mengubah tren tersebut menjadi bisnis yang menguntungkan.',
            ],
            [
                'order_num' => 40,
                'type_riasec' => 'E',
                'question' => 'Saya gemar memperluas relasi bisnis (networking) dengan para pengusaha, mitra hotel, dan komunitas wirausaha muda.',
            ],

            // ================= CONVENTIONAL (C) — 8 SOAL =================
            // Fokus: Keteraturan, administrasi rapi, tata kearsipan, pencatatan keuangan, spreadsheet, akurasi prosedur
            [
                'order_num' => 41,
                'type_riasec' => 'C',
                'question' => 'Saya senang mencatat transaksi pengeluaran dan pemasukan uang secara detail serta menyusun buku keuangan yang seimbang (balance).',
            ],
            [
                'order_num' => 42,
                'type_riasec' => 'C',
                'question' => 'Saya teliti dalam menata arsip dokumen digital, mengelompokkan berkas surat kantor, dan memberi penamaan folder yang teratur.',
            ],
            [
                'order_num' => 43,
                'type_riasec' => 'C',
                'question' => 'Saya mahir dan nyaman menggunakan aplikasi spreadsheet (Microsoft Excel / Google Sheets) dengan rumus formula untuk mengolah data.',
            ],
            [
                'order_num' => 44,
                'type_riasec' => 'C',
                'question' => 'Saya senang menyusun agenda jadwal kegiatan pimpinan, menulis notulensi rapat resmi, dan membuat format surat bisnis formal.',
            ],
            [
                'order_num' => 45,
                'type_riasec' => 'C',
                'question' => 'Saya terbiasa teliti menghitung kewajiban perpajakan, mencocokkan nota belanja, dan memastikan kepatuhan administrasi keuangan.',
            ],
            [
                'order_num' => 46,
                'type_riasec' => 'C',
                'question' => 'Saya menyukai sistem pengelolaan inventaris barang, pencatatan kartu stok dapur / hotel, dan memastikan ketersediaan logistik selalu rapi.',
            ],
            [
                'order_num' => 47,
                'section' => 'riasec',
                'type_riasec' => 'C',
                'question' => 'Saya lebih tenang dan percaya diri ketika bekerja mengikuti Standard Operating Procedure (SOP) resmi yang telah ditetapkan secara baku.',
            ],
            [
                'order_num' => 48,
                'section' => 'riasec',
                'type_riasec' => 'C',
                'question' => 'Saya disiplin melakukan pencadangan data (backup), memverifikasi ketepatan laporan, dan mengumpulkan rekap data tepat waktu.',
            ],

            // ================= SESI 2: ANGKET MOTIVASI KERJA (CAREER ANCHORS) — 24 SOAL =================
            // 1. Technical/Functional Competence (TF)
            [
                'order_num' => 49,
                'section' => 'career_anchor',
                'type_anchor' => 'TF',
                'question' => 'Saya ingin dikenal sebagai orang yang paling ahli dan paling jago dalam satu bidang keterampilan tertentu.',
            ],
            [
                'order_num' => 50,
                'section' => 'career_anchor',
                'type_anchor' => 'TF',
                'question' => 'Saya lebih memilih terus mengasah keterampilan teknis saya daripada harus naik jabatan menjadi manajer yang mengurusi urusan absen dan rapat pegawai.',
            ],
            [
                'order_num' => 51,
                'section' => 'career_anchor',
                'type_anchor' => 'TF',
                'question' => 'Bagi saya, kepuasan terbesar dalam bekerja adalah ketika saya berhasil menyelesaikan tugas yang membutuhkan keahlian tingkat tinggi.',
            ],

            // 2. General Manager Competence (GM)
            [
                'order_num' => 52,
                'section' => 'career_anchor',
                'type_anchor' => 'GM',
                'question' => 'Saya sangat menikmati tanggung jawab untuk memimpin, mengarahkan, dan mengatur kerja teman-teman dalam satu tim.',
            ],
            [
                'order_num' => 53,
                'section' => 'career_anchor',
                'type_anchor' => 'GM',
                'question' => 'Saya memiliki impian untuk menduduki posisi pemimpin tertinggi (seperti manajer atau direktur) di sebuah organisasi atau perusahaan.',
            ],
            [
                'order_num' => 54,
                'section' => 'career_anchor',
                'type_anchor' => 'GM',
                'question' => 'Saya merasa tertantang jika harus mengoordinasikan berbagai bagian kerja yang berbeda agar target besar organisasi bisa tercapai.',
            ],

            // 3. Autonomy/Independence (AU)
            [
                'order_num' => 55,
                'section' => 'career_anchor',
                'type_anchor' => 'AU',
                'question' => 'Saya merasa tidak nyaman jika jam kerja, pakaian, dan cara kerja saya diatur secara sangat ketat oleh aturan kantor.',
            ],
            [
                'order_num' => 56,
                'section' => 'career_anchor',
                'type_anchor' => 'AU',
                'question' => 'Saya lebih produktif jika diberikan kebebasan penuh untuk mengatur waktu dan cara saya sendiri dalam menyelesaikan tugas.',
            ],
            [
                'order_num' => 57,
                'section' => 'career_anchor',
                'type_anchor' => 'AU',
                'question' => 'Saya rela mendapatkan penghasilan yang tidak menentu, asalkan saya bebas menjadi bos bagi diri saya sendiri tanpa ada yang memerintah.',
            ],

            // 4. Security/Stability (SE)
            [
                'order_num' => 58,
                'section' => 'career_anchor',
                'type_anchor' => 'SE',
                'question' => 'Memiliki pekerjaan dengan gaji tetap yang pasti setiap bulan dan jaminan masa tua adalah prioritas utama hidup saya.',
            ],
            [
                'order_num' => 59,
                'section' => 'career_anchor',
                'type_anchor' => 'SE',
                'question' => 'Saya lebih memilih bertahan di satu perusahaan atau instansi yang stabil dalam waktu lama daripada berpindah-pindah demi gaji yang lebih tinggi tapi berisiko.',
            ],
            [
                'order_num' => 60,
                'section' => 'career_anchor',
                'type_anchor' => 'SE',
                'question' => 'Saya merasa lebih tenang bekerja di tempat yang struktur organisasinya jelas, tugasnya pasti, dan masa depannya aman dari kebangkrutan.',
            ],

            // 5. Entrepreneurial Creativity (EC)
            [
                'order_num' => 61,
                'section' => 'career_anchor',
                'type_anchor' => 'EC',
                'question' => 'Saya selalu tertantang untuk menciptakan sesuatu yang baru, seperti membangun bisnis, merek (brand), atau produk saya sendiri dari nol.',
            ],
            [
                'order_num' => 62,
                'section' => 'career_anchor',
                'type_anchor' => 'EC',
                'question' => 'Saya merasa bosan jika hanya menjalankan bisnis yang sudah ada; saya ingin menjadi orang yang menemukan ide bisnis baru.',
            ],
            [
                'order_num' => 63,
                'section' => 'career_anchor',
                'type_anchor' => 'EC',
                'question' => 'Menghasilkan banyak uang dari hasil keringat dan keberanian mengambil risiko bisnis sendiri adalah impian terbesar saya.',
            ],

            // 6. Service/Dedication to a Cause (SV)
            [
                'order_num' => 64,
                'section' => 'career_anchor',
                'type_anchor' => 'SV',
                'question' => 'Bagi saya, tujuan utama bekerja adalah untuk menolong sesama manusia dan memberikan dampak kebaikan bagi lingkungan sekitar.',
            ],
            [
                'order_num' => 65,
                'section' => 'career_anchor',
                'type_anchor' => 'SV',
                'question' => 'Saya tidak akan sudi bekerja di perusahaan yang merugikan masyarakat luas, meskipun perusahaan tersebut menawarkan gaji yang sangat tinggi.',
            ],
            [
                'order_num' => 66,
                'section' => 'career_anchor',
                'type_anchor' => 'SV',
                'question' => 'Saya merasa sangat puas jika keahlian yang saya miliki bisa digunakan untuk menyelesaikan masalah sosial di lingkungan saya.',
            ],

            // 7. Pure Challenge (CH)
            [
                'order_num' => 67,
                'section' => 'career_anchor',
                'type_anchor' => 'CH',
                'question' => 'Saya cepat merasa bosan dengan pekerjaan yang rutinitasnya sama setiap hari dan tidak memiliki tingkat kesulitan yang tinggi.',
            ],
            [
                'order_num' => 68,
                'section' => 'career_anchor',
                'type_anchor' => 'CH',
                'question' => 'Saya sangat senang jika diberikan tugas berat yang dianggap mustahil atau sangat sulit diselesaikan oleh orang lain.',
            ],
            [
                'order_num' => 69,
                'section' => 'career_anchor',
                'type_anchor' => 'CH',
                'question' => 'Bagi saya, hidup dan karier akan terasa hambar jika tidak ada masalah rumit atau kompetisi ketat yang harus saya menangkan.',
            ],

            // 8. Lifestyle (LS)
            [
                'order_num' => 70,
                'section' => 'career_anchor',
                'type_anchor' => 'LS',
                'question' => 'Saat memilih karier nanti, pertimbangan utama saya adalah apakah pekerjaan tersebut fleksibel dan tidak merusak waktu saya bersama keluarga/hobi.',
            ],
            [
                'order_num' => 71,
                'section' => 'career_anchor',
                'type_anchor' => 'LS',
                'question' => 'Saya lebih menyukai sistem kerja yang mendukung keseimbangan hidup, seperti bisa bekerja dari rumah (Work From Home) atau waktu kerja yang bisa disesuaikan.',
            ],
            [
                'order_num' => 72,
                'section' => 'career_anchor',
                'type_anchor' => 'LS',
                'question' => 'Saya rela menolak promosi jabatan yang lebih tinggi jika hal itu akan menyita seluruh waktu luang dan kehidupan pribadi saya.',
            ],
        ];

        // Kosongkan dan re-seed 72 pertanyaan terstandarisasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CareerQuestion::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($questions as $q) {
            CareerQuestion::create([
                'question' => $q['question'],
                'section' => $q['section'] ?? 'riasec',
                'type_riasec' => $q['type_riasec'] ?? null,
                'type_anchor' => $q['type_anchor'] ?? null,
                'order_num' => $q['order_num'],
                'status' => 'active',
            ]);
        }
    }
}
