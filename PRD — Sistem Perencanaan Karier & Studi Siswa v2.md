# PRODUCT REQUIREMENT DOCUMENT (PRD)

## Sistem Perencanaan Karier & Studi Siswa

**Platform:** Web  
**Framework:** Laravel 12  
**Frontend:** Bootstrap 5  
**Database:** MySQL / MariaDB  
**Data Kampus & Prodi:** `prodi.json`  
**Bahasa:** Indonesia  
**Pengguna:** Siswa dan Admin  
**Sifat aplikasi:** Sederhana, sekali pakai untuk pendataan siswa

---

# 1. Tujuan Aplikasi

Aplikasi digunakan untuk mendata rencana siswa setelah lulus sekolah sekaligus memberikan gambaran mengenai minat karier siswa berdasarkan model Holland/RIASEC.

Siswa dapat:

1. Login ke aplikasi.
2. Mengikuti Tes Minat Karier Digital.
3. Melihat hasil tes dalam bentuk grafik.
4. Mengetahui tipe minat karier dominan.
5. Melihat Holland Code.
6. Melihat rekomendasi jurusan/prodi.
7. Melihat rekomendasi profesi.
8. Melihat rekomendasi bidang usaha.
9. Menentukan rencana setelah lulus:
   - Kuliah
   - Bekerja
   - Berwirausaha
10. Jika memilih kuliah, memilih kampus dan program studi.
11. Mengirimkan pilihan akhir.

Admin dapat melihat seluruh data dan statistik siswa.

---

# 2. Konsep Utama Sistem

Sistem mempunyai dua data utama:

### A. Rencana Setelah Lulus

Pilihan siswa:

- Kuliah
- Bekerja
- Berwirausaha

### B. Profil Minat Karier

Hasil Tes Holland/RIASEC:

- Realistic (R)
- Investigative (I)
- Artistic (A)
- Social (S)
- Enterprising (E)
- Conventional (C)

Kedua data tersebut disimpan secara terpisah.

Contoh:

> Rencana siswa: Kuliah  
> Kampus: Universitas Lampung  
> Prodi: Informatika  
> Holland Code: I-C-R  
> Minat dominan: Investigative

---

# 3. Role Pengguna

## 3.1 Siswa

Siswa dapat:

- Login
- Melihat dashboard
- Mengikuti tes minat karier
- Melihat hasil tes
- Melihat rekomendasi
- Mengisi rencana setelah lulus
- Memilih kampus dan prodi
- Mengubah pilihan sebelum dikunci
- Melihat data pilihannya

## 3.2 Admin

Admin dapat:

- Login admin
- Melihat dashboard statistik
- Melihat daftar siswa
- Melihat hasil tes RIASEC
- Melihat rencana siswa
- Melihat pilihan kampus
- Melihat pilihan prodi
- Melihat rekomendasi karier
- Melakukan filter dan pencarian
- Export data

---

# 4. Alur Siswa

```text
LOGIN
  ↓
DASHBOARD
  ↓
TES MINAT KARIER
  ↓
MENGERJAKAN PERTANYAAN
  ↓
PERHITUNGAN RIASEC
  ↓
HASIL TES
  ↓
REKOMENDASI JURUSAN / PROFESI / USAHA
  ↓
RENCANA SETELAH LULUS
  ↓
┌─────────────┬──────────────┬────────────────┐
│   KULIAH    │    BEKERJA   │  BERWIRAUSAHA  │
└──────┬──────┴──────────────┴────────────────┘
       ↓
 PILIHAN AKHIR
       ↓
     SUBMIT
```

---

# 5. Dashboard Siswa

Dashboard menampilkan ringkasan:

### Selamat Datang

Menampilkan:

- Nama siswa
- NIS/NISN
- Kelas
- Jurusan

### Status Tes Minat

Contoh:

> Tes Minat Karier  
> Status: **Sudah Dikerjakan**

Menampilkan:

- Tipe dominan
- Holland Code
- Tombol "Lihat Hasil"

### Rencana Setelah Lulus

Contoh:

> Rencana: **Kuliah**

> Universitas Lampung  
> S1 Informatika

### Ringkasan Minat

Menampilkan grafik sederhana enam tipe RIASEC.

---

# 6. Modul Tes Minat Karier Digital

## 6.1 Tujuan

Tes digunakan untuk mengetahui kecenderungan minat karier siswa berdasarkan enam tipe Holland/RIASEC.

## 6.2 Enam Tipe Holland

### R — Realistic

Kecenderungan:

- Praktis
- Teknis
- Menggunakan alat
- Aktivitas lapangan
- Membuat atau memperbaiki sesuatu

Contoh bidang:

- Teknik
- Otomotif
- Elektronika
- Teknologi
- Manufaktur

### I — Investigative

Kecenderungan:

- Analitis
- Penelitian
- Pemecahan masalah
- Logika
- Eksperimen

Contoh bidang:

- Informatika
- Sains
- Data
- Kedokteran
- Penelitian

### A — Artistic

Kecenderungan:

- Kreativitas
- Seni
- Desain
- Ide baru
- Ekspresi

Contoh bidang:

- Desain
- Multimedia
- Animasi
- Seni
- Content creation

### S — Social

Kecenderungan:

- Membantu orang
- Mengajar
- Berkomunikasi
- Kerja sama
- Membimbing

Contoh bidang:

- Pendidikan
- Psikologi
- Konseling
- Kesehatan
- Pelayanan sosial

### E — Enterprising

Kecenderungan:

- Memimpin
- Bisnis
- Negosiasi
- Komunikasi
- Mengambil keputusan

Contoh bidang:

- Manajemen
- Bisnis
- Marketing
- Sales
- Entrepreneurship

### C — Conventional

Kecenderungan:

- Terstruktur
- Data
- Administrasi
- Ketelitian
- Sistematis

Contoh bidang:

- Akuntansi
- Administrasi
- Keuangan
- Perbankan
- Data administration

---

# 7. Pertanyaan Tes

Tes menggunakan sejumlah pertanyaan yang sudah ditentukan oleh admin/developer.

Contoh:

> Saya senang memperbaiki atau membuat benda menggunakan alat.

Pilihan jawaban:

- Sangat Tidak Suka
- Tidak Suka
- Netral
- Suka
- Sangat Suka

Setiap pertanyaan mempunyai kategori RIASEC.

Contoh:

```text
Pertanyaan:
Saya senang membuat atau memperbaiki sesuatu.

Kategori:
Realistic

Skor:
1 - 5
```

Pertanyaan disimpan di database sehingga dapat dikelola tanpa mengubah kode aplikasi.

---

# 8. Perhitungan RIASEC

Sistem menghitung total skor setiap tipe:

```text
R = Total skor Realistic
I = Total skor Investigative
A = Total skor Artistic
S = Total skor Social
E = Total skor Enterprising
C = Total skor Conventional
```

Kemudian sistem melakukan ranking.

Contoh:

```text
I = 92
C = 85
R = 78
S = 54
A = 48
E = 41
```

Maka:

```text
Tipe dominan:
Investigative

Holland Code:
I-C-R
```

Holland Code mengambil tiga skor tertinggi.

---

# 9. Hasil Tes

Setelah selesai, siswa melihat halaman:

## Hasil Tes Minat Karier

Menampilkan:

- Grafik RIASEC
- Enam skor
- Tipe dominan
- Holland Code
- Penjelasan tipe dominan
- Dua tipe pendukung
- Rekomendasi

Contoh:

```text
Holland Code

I - C - R

Investigative
Conventional
Realistic
```

---

# 10. Visualisasi Hasil

Hasil ditampilkan secara visual.

Komponen:

### Grafik RIASEC

Menampilkan:

```text
Realistic       78
Investigative   92
Artistic        48
Social          54
Enterprising    41
Conventional    85
```

Grafik dapat menggunakan:

- Bar chart
- Radar chart menggunakan library frontend jika diperlukan

Jika ingin implementasi paling sederhana, gunakan **Bootstrap + CSS/HTML** atau library chart ringan.

---

# 11. Penjelasan Hasil

Sistem menampilkan penjelasan otomatis berdasarkan tipe dominan.

Contoh:

> **Investigative**
>
> Kamu cenderung menyukai aktivitas yang melibatkan analisis, logika, pemecahan masalah, penelitian, dan mencari tahu bagaimana sesuatu bekerja.

Kemudian:

> **Conventional**
>
> Kamu cenderung menyukai pekerjaan yang terstruktur, sistematis, membutuhkan ketelitian, dan berhubungan dengan data.

---

# 12. Rekomendasi Karier

Sistem mempunyai database mapping:

```text
RIASEC
   ↓
Jurusan
Profesi
Bidang Usaha
```

Contoh:

```text
I-C-R
↓
Jurusan:
- Informatika
- Sistem Informasi
- Teknik Komputer
- Statistika

Profesi:
- Programmer
- Data Analyst
- System Analyst
- Network Engineer

Usaha:
- Jasa IT
- Web Development
- Digital Agency
- Konsultan Teknologi
```

Rekomendasi berdasarkan kombinasi tipe dominan siswa.

---

# 13. Rencana Setelah Lulus

Setelah melihat hasil tes, siswa mengisi:

## Apa rencana kamu setelah lulus?

### Pilihan:

```text
○ Kuliah
○ Bekerja
○ Berwirausaha
```

---

# 14. Jika Memilih Kuliah

Sistem menampilkan:

### Pilih Kampus

Data kampus diambil dari:

```text
prodi.json
```

Siswa dapat:

- Mencari kampus
- Memilih kampus
- Melihat program studi
- Memilih program studi

Contoh:

```text
Universitas Lampung

Program Studi:
- Informatika
- Sistem Informasi
- Teknik Elektro
- Manajemen
```

Data tidak diketik manual oleh siswa.

---

# 15. Jika Memilih Bekerja

Tampilkan:

### Bidang Pekerjaan

Pilihan bidang:

- Teknologi Informasi
- Administrasi
- Pendidikan
- Kesehatan
- Teknik
- Marketing
- Keuangan
- Industri
- Lainnya

Tambahkan:

```text
Keterangan pekerjaan yang diminati
[____________________________]
```

---

# 16. Jika Memilih Berwirausaha

Tampilkan:

### Bidang Usaha

Contoh:

- Kuliner
- Fashion
- Teknologi
- Jasa
- Perdagangan
- Kreatif
- Pertanian
- Peternakan
- Lainnya

Tambahkan:

```text
Jenis usaha yang diminati
[____________________________]
```

---

# 17. Validasi Pilihan

Siswa tidak dapat melakukan submit jika data belum lengkap.

### Kuliah

Wajib:

- Pilihan = Kuliah
- Kampus
- Program studi

### Bekerja

Wajib:

- Pilihan = Bekerja
- Bidang pekerjaan

### Berwirausaha

Wajib:

- Pilihan = Berwirausaha
- Bidang usaha

---

# 18. Status Pengisian

Dashboard menampilkan:

```text
Tes Minat Karier
✓ Selesai

Rencana Setelah Lulus
✓ Selesai

Status Pengisian
✓ Lengkap
```

Jika belum:

```text
Tes Minat Karier
○ Belum dikerjakan

Rencana Setelah Lulus
○ Belum dipilih
```

---

# 19. Dashboard Admin

Dashboard admin menampilkan statistik:

```text
TOTAL SISWA
1.250

TES SELESAI
1.120

BELUM TES
130

DATA LENGKAP
1.050
```

Statistik rencana:

```text
Kuliah          720
Bekerja         280
Wirausaha      120
Belum memilih   130
```

Statistik RIASEC:

```text
Investigative
Realistic
Conventional
Social
Artistic
Enterprising
```

Admin dapat melihat distribusi minat siswa.

---

# 20. Data Siswa Admin

Tabel:

| Siswa | Kelas | Rencana | Holland Code | Dominan | Kampus | Prodi |
|---|---|---|---|---|---|---|
| Budi | XII RPL | Kuliah | ICR | Investigative | UNILA | Informatika |
| Andi | XII TKJ | Bekerja | RCE | Realistic | - | - |
| Siti | XII MM | Wirausaha | AES | Artistic | - | - |

Fitur:

- Search
- Filter kelas
- Filter rencana
- Filter tipe RIASEC
- Filter status tes
- Filter status pengisian
- Detail siswa
- Export Excel/CSV

---

# 21. Detail Siswa Admin

Admin dapat melihat:

## Identitas

- Nama
- NIS
- NISN
- Kelas
- Jurusan

## Hasil Tes

- R score
- I score
- A score
- S score
- E score
- C score
- Holland Code
- Tipe dominan

## Rekomendasi

- Jurusan
- Profesi
- Bidang usaha

## Rencana

- Kuliah / Bekerja / Wirausaha

Jika kuliah:

- Kampus
- Prodi

---

# 22. Struktur Database

Minimal tabel:

### users

Data login siswa/admin.

### students

Data siswa.

### career_questions

Data pertanyaan tes.

Field:

```text
id
question
type_riasec
status
created_at
updated_at
```

### career_answers

Jawaban siswa.

```text
id
student_id
question_id
score
created_at
updated_at
```

### career_results

Hasil akhir tes.

```text
id
student_id
realistic_score
investigative_score
artistic_score
social_score
enterprising_score
conventional_score
dominant_type
holland_code
completed_at
```

### career_recommendations

Mapping rekomendasi.

```text
id
riasec_code
category
name
description
status
```

Category:

```text
jurusan
profesi
usaha
```

### student_plans

Rencana setelah lulus.

```text
id
student_id
plan_type
campus
study_program
work_field
business_field
notes
submitted_at
```

`plan_type`:

```text
kuliah
bekerja
wirausaha
```

---

# 23. Struktur Menu Siswa

```text
Dashboard
│
├── Tes Minat Karier
│
├── Hasil Tes
│
├── Rekomendasi
│
├── Rencana Setelah Lulus
│
├── Pilihan Saya
│
└── Profil
```

---

# 24. Struktur Menu Admin

```text
Dashboard
│
├── Data Siswa
│
├── Hasil Tes Minat
│
├── Rencana Siswa
│
├── Rekomendasi Karier
│
├── Pertanyaan Tes
│
├── Data Kampus & Prodi
│
└── Laporan
```

---

# 25. Route Bahasa Indonesia

Route menggunakan istilah bahasa Indonesia.

Contoh:

```text
/login
/dashboard

/tes-minat
/tes-minat/mulai
/tes-minat/hasil

/rekomendasi

/rencana
/rencana/kuliah
/rencana/bekerja
/rencana/wirausaha

/pilihan-saya
/profil
/logout
```

Admin:

```text
/admin/dashboard
/admin/siswa
/admin/hasil-tes
/admin/rencana
/admin/rekomendasi
/admin/pertanyaan-tes
/admin/kampus-prodi
/admin/laporan
```

---

# 26. Prinsip UX

Aplikasi harus:

- Sederhana
- Mobile friendly
- Mudah digunakan siswa
- Tidak terlalu banyak menu
- Bahasa Indonesia
- Bootstrap 5
- Tidak membutuhkan Alpine.js
- Form bertahap
- Progress tes terlihat jelas
- Hasil mudah dipahami

---

# 27. Urutan Implementasi

Implementasi Laravel dilakukan bertahap:

### Tahap 1
Sistem login dan role.

### Tahap 2
Dashboard siswa.

### Tahap 3
Modul rencana setelah lulus.

### Tahap 4
Integrasi `prodi.json`.

### Tahap 5
Database pertanyaan RIASEC.

### Tahap 6
Halaman pengerjaan tes.

### Tahap 7
Perhitungan skor.

### Tahap 8
Halaman hasil RIASEC.

### Tahap 9
Mapping rekomendasi.

### Tahap 10
Dashboard admin dan statistik.

### Tahap 11
Export laporan.

### Tahap 12
Testing dan deployment.

---

# 28. Batasan Sistem

Aplikasi tidak dikembangkan menjadi sistem akademik lengkap.

Tidak termasuk:

- Nilai siswa
- Absensi
- Jadwal pelajaran
- E-learning
- Pembayaran
- PPDB
- SIAKAD
- Manajemen sekolah

Fokus hanya pada:

**Tes Minat Karier + Rencana Setelah Lulus + Pilihan Studi/Kerja/Wirausaha + Laporan Admin.**

---

# 29. Prinsip Penting Hasil Tes

Hasil tes digunakan sebagai **informasi pendukung eksplorasi karier**, bukan sebagai keputusan mutlak mengenai masa depan siswa.

Sistem tidak boleh menampilkan kalimat seperti:

> "Kamu harus menjadi programmer."

Sebaliknya:

> "Berdasarkan hasil minatmu, bidang Informatika merupakan salah satu bidang yang dapat kamu eksplorasi."

Dengan demikian siswa tetap mempunyai kebebasan menentukan pilihan akhirnya.

---

# 30. Output Akhir Sistem

Pada akhirnya setiap siswa mempunyai satu profil:

```text
┌─────────────────────────────────────────────┐
│              PROFIL KARIER SISWA            │
├─────────────────────────────────────────────┤
│                                             │
│ Nama          : Budi Santoso                │
│ Kelas         : XII RPL                     │
│                                             │
│ HOLLAND CODE  : I-C-R                       │
│                                             │
│ Dominan       : Investigative               │
│                                             │
│ Rencana       : Kuliah                      │
│                                             │
│ Kampus        : Universitas Lampung        │
│ Prodi         : Informatika                 │
│                                             │
│ Rekomendasi:                                │
│ • Informatika                               │
│ • Sistem Informasi                          │
│ • Data Analyst                              │
│ • Software Developer                        │
│                                             │
└─────────────────────────────────────────────┘
```

**Kesimpulan:** aplikasi sekarang menjadi sistem sederhana untuk mendapatkan **profil minat karier + rencana nyata siswa setelah lulus**, sehingga admin sekolah dapat melihat bukan hanya *berapa siswa ingin kuliah*, tetapi juga **minat karier mereka dan kecenderungan bidang yang diminati**.