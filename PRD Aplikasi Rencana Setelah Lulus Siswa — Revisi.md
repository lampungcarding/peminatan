# PRD — Aplikasi Rencana Setelah Lulus Siswa

## 1. Tujuan Aplikasi

Aplikasi digunakan untuk mendata **rencana siswa setelah lulus sekolah**.

Setiap siswa memilih salah satu rencana:

- **Kuliah**
- **Bekerja**
- **Berwirausaha**

Jika memilih **Kuliah**, siswa wajib menentukan:

- Perguruan Tinggi
- Program Studi
- Jenjang
- Akreditasi

Data perguruan tinggi dan program studi untuk pilihan **Kuliah** diperoleh dari endpoint KIP Kuliah:

```text
POST https://kip-kuliah.kemdiktisaintek.go.id/prodijson
```

---

# 2. Alur Utama

```text
                    LOGIN SISWA
                         │
                         ▼
                RENCANA SETELAH LULUS
                         │
          ┌──────────────┼──────────────┐
          │              │              │
          ▼              ▼              ▼
       KULIAH         BEKERJA      BERWIRAUSAHA
          │
          ▼
   PILIH KAMPUS
          │
          ▼
    PILIH PRODI
          │
          ▼
      KONFIRMASI
          │
          ▼
        SUBMIT
```

---

# 3. Halaman Siswa

Setelah login, siswa melihat:

```text
+--------------------------------------------------+
|        RENCANA SETELAH LULUS                    |
+--------------------------------------------------+
|                                                  |
| Nama: Ahmad Fauzan                               |
|                                                  |
| Apa rencana kamu setelah lulus?                  |
|                                                  |
|   ○ Kuliah                                       |
|                                                  |
|   ○ Bekerja                                      |
|                                                  |
|   ○ Berwirausaha                                 |
|                                                  |
+--------------------------------------------------+
```

Pilihan dibuat menggunakan Bootstrap Radio Button atau Card.

---

# 4. Pilihan KULIAH

Jika siswa memilih:

> **Kuliah**

maka sistem menampilkan form tambahan:

```text
+--------------------------------------------------+
| RENCANA: KULIAH                                  |
+--------------------------------------------------+
|                                                  |
| Perguruan Tinggi                                 |
| [ Cari perguruan tinggi...                  ]    |
|                                                  |
| Hasil pencarian:                                 |
|                                                  |
| ○ Universitas Lampung                            |
| ○ Institut Teknologi Sumatera                    |
| ○ Universitas Gadjah Mada                        |
|                                                  |
| Program Studi                                    |
| [ Pilih program studi                       ▼ ]  |
|                                                  |
| Jenjang: S1                                     |
| Akreditasi: Baik Sekali                         |
|                                                  |
+--------------------------------------------------+
```

### Perguruan Tinggi

Data diambil dari:

```text
/prodijson
```

Siswa dapat mencari berdasarkan nama perguruan tinggi.

### Program Studi

Setelah perguruan tinggi dipilih, sistem menampilkan program studi yang sesuai.

Contoh:

```text
Universitas Lampung

Program Studi:

○ Teknik Informatika
○ Sistem Informasi
○ Manajemen
○ Akuntansi
○ Teknik Sipil
```

---

# 5. Pilihan BEKERJA

Jika siswa memilih:

> **Bekerja**

maka form kuliah **tidak ditampilkan**.

Sistem cukup menyimpan:

```text
rencana = bekerja
```

Tampilan:

```text
+--------------------------------------------------+
| RENCANA: BEKERJA                                 |
+--------------------------------------------------+
|                                                  |
| Kamu memilih untuk bekerja setelah lulus.        |
|                                                  |
| [ LANJUTKAN ]                                    |
|                                                  |
+--------------------------------------------------+
```

Jika ingin sedikit lebih informatif, dapat ditambahkan:

```text
Bidang pekerjaan yang diminati
[____________________________]
```

Namun field ini **opsional** dan tidak menjadi bagian wajib pada versi awal.

---

# 6. Pilihan BERWIRAUSAHA

Jika siswa memilih:

> **Berwirausaha**

maka form kuliah tidak ditampilkan.

Sistem menyimpan:

```text
rencana = berwirausaha
```

Tampilan:

```text
+--------------------------------------------------+
| RENCANA: BERWIRAUSAHA                            |
+--------------------------------------------------+
|                                                  |
| Kamu memilih untuk berwirausaha setelah lulus.   |
|                                                  |
| [ LANJUTKAN ]                                    |
|                                                  |
+--------------------------------------------------+
```

Opsional:

```text
Bidang usaha yang diminati
[____________________________]
```

Tetapi untuk versi awal dapat ditiadakan agar aplikasi tetap sederhana.

---

# 7. Konfirmasi Sebelum Submit

Sebelum menyimpan, siswa melihat ringkasan.

### Jika Kuliah

```text
+--------------------------------------------------+
| KONFIRMASI RENCANA                               |
+--------------------------------------------------+
|                                                  |
| Nama                                             |
| Ahmad Fauzan                                     |
|                                                  |
| Rencana Setelah Lulus                            |
| Kuliah                                           |
|                                                  |
| Perguruan Tinggi                                 |
| Universitas Lampung                              |
|                                                  |
| Program Studi                                    |
| Teknik Informatika                               |
|                                                  |
| Jenjang                                          |
| S1                                               |
|                                                  |
| Akreditasi                                       |
| Baik Sekali                                      |
|                                                  |
| [ KEMBALI ]       [ KIRIM PILIHAN ]              |
+--------------------------------------------------+
```

### Jika Bekerja

```text
Rencana Setelah Lulus
Bekerja
```

### Jika Berwirausaha

```text
Rencana Setelah Lulus
Berwirausaha
```

---

# 8. Database

Gunakan satu tabel utama:

## pilihan_setelah_lulus

```text
id
user_id
rencana
perguruan_tinggi_id_external
nama_perguruan_tinggi
program_studi_id_external
nama_program_studi
jenjang
akreditasi
submitted_at
created_at
updated_at
```

### Nilai `rencana`

```text
kuliah
bekerja
berwirausaha
```

Untuk `bekerja` dan `berwirausaha`, field:

```text
perguruan_tinggi_id_external
nama_perguruan_tinggi
program_studi_id_external
nama_program_studi
jenjang
akreditasi
```

boleh `NULL`.

Contoh data:

### Siswa memilih Kuliah

```text
rencana: kuliah
nama_perguruan_tinggi: Universitas Lampung
nama_program_studi: Teknik Informatika
jenjang: S1
akreditasi: Baik Sekali
```

### Siswa memilih Bekerja

```text
rencana: bekerja
nama_perguruan_tinggi: NULL
nama_program_studi: NULL
jenjang: NULL
akreditasi: NULL
```

### Siswa memilih Berwirausaha

```text
rencana: berwirausaha
nama_perguruan_tinggi: NULL
nama_program_studi: NULL
jenjang: NULL
akreditasi: NULL
```

---

# 9. Admin

Dashboard admin menampilkan statistik:

```text
+------------------------------------------------------+
| DASHBOARD RENCANA SISWA                              |
+------------------------------------------------------+
|                                                      |
| Total Siswa     Kuliah     Bekerja     Wirausaha     |
|     350           210        95           45         |
|                                                      |
+------------------------------------------------------+
```

Admin dapat melihat tabel:

```text
Nama | NISN | Rencana | Kampus | Prodi | Waktu
```

Contoh:

```text
Ahmad | 12345 | Kuliah | UNILA | Teknik Informatika
Budi  | 12346 | Bekerja | - | -
Citra | 12347 | Berwirausaha | - | -
```

---

# 10. Filter Admin

Admin dapat melakukan filter berdasarkan:

### Rencana

```text
Semua
Kuliah
Bekerja
Berwirausaha
```

### Jika Kuliah

Tambahan filter:

```text
Perguruan Tinggi
Program Studi
Jenjang
```

---

# 11. Statistik

Dashboard dapat menampilkan:

```text
Total siswa
├── Kuliah
├── Bekerja
└── Berwirausaha
```

Contoh:

```text
350 siswa

Kuliah       60%
Bekerja      27%
Wirausaha    13%
```

Tidak perlu statistik yang kompleks.

---

# 12. Integrasi KIP Kuliah

Integrasi hanya digunakan apabila siswa memilih:

```text
Kuliah
```

Alur:

```text
Siswa pilih KULIAH
        ↓
Laravel
        ↓
KipKuliahService
        ↓
POST /prodijson
        ↓
JSON
        ↓
Cari Perguruan Tinggi
        ↓
Pilih Program Studi
```

Jika siswa memilih:

```text
Bekerja
```

atau:

```text
Berwirausaha
```

maka **tidak perlu request ke KIP Kuliah**.

---

# 13. Service

Tetap gunakan:

```text
app/Services/KipKuliahService.php
```

Tanggung jawab:

- Mengambil session/CSRF yang diperlukan
- Request `/prodijson`
- Pencarian perguruan tinggi
- Mengambil data program studi
- Menangani error
- Cache response

---

# 14. Route

Gunakan bahasa Indonesia.

```php
Route::middleware('auth')->group(function () {

    Route::get('/siswa', ...)
        ->name('siswa.dashboard');

    Route::get('/siswa/rencana', ...)
        ->name('siswa.rencana');

    Route::post('/siswa/rencana', ...)
        ->name('siswa.rencana.simpan');

    Route::post('/siswa/cari-kampus', ...)
        ->name('siswa.cari-kampus');

    Route::post('/siswa/cari-prodi', ...)
        ->name('siswa.cari-prodi');

    Route::get('/siswa/konfirmasi', ...)
        ->name('siswa.konfirmasi');

    Route::post('/siswa/submit', ...)
        ->name('siswa.submit');

});
```

Admin:

```php
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/', ...)
            ->name('admin.dashboard');

        Route::get('/rencana-siswa', ...)
            ->name('admin.rencana-siswa');

        Route::get('/rencana-siswa/export', ...)
            ->name('admin.rencana-siswa.export');

        Route::delete('/rencana-siswa/{id}/reset', ...)
            ->name('admin.rencana-siswa.reset');
    });
```

---

# 15. Validasi

Jika:

```text
rencana = kuliah
```

maka wajib:

```text
perguruan_tinggi
program_studi
```

Jika:

```text
rencana = bekerja
```

maka:

```text
perguruan_tinggi = NULL
program_studi = NULL
```

Jika:

```text
rencana = berwirausaha
```

maka:

```text
perguruan_tinggi = NULL
program_studi = NULL
```

---

# 16. Satu Siswa Satu Pilihan

Setiap siswa hanya boleh memiliki **satu rencana setelah lulus**.

Contoh:

```text
Ahmad
→ Kuliah
→ Universitas Lampung
→ Teknik Informatika
```

Tidak boleh:

```text
Ahmad
→ Kuliah

dan

Ahmad
→ Bekerja
```

Database menggunakan:

```text
user_id UNIQUE
```

Admin dapat melakukan **reset pilihan** apabila siswa ingin mengubah rencana.

---

# 17. Tampilan Setelah Submit

Setelah submit:

```text
+--------------------------------------------------+
|               DATA TERSIMPAN                     |
+--------------------------------------------------+
|                                                  |
| Terima kasih. Rencana setelah lulus kamu         |
| berhasil disimpan.                               |
|                                                  |
| Rencana: KULIAH                                  |
|                                                  |
| Universitas Lampung                              |
| Teknik Informatika                               |
|                                                  |
+--------------------------------------------------+
```

Untuk bekerja:

```text
Rencana: BEKERJA
```

Untuk wirausaha:

```text
Rencana: BERWIRAUSAHA
```

---

# 18. Teknologi

Tetap sederhana:

```text
Laravel 12
PHP 8.3
MySQL/MariaDB
Bootstrap 5
Blade
JavaScript Vanilla
```

Tidak perlu:

```text
Alpine.js
Vue
React
Livewire
Inertia
```

---

# 19. Batasan Aplikasi

Aplikasi hanya untuk:

> **Pendataan rencana siswa setelah lulus sekolah.**

Tidak mencakup:

- Pendaftaran kuliah
- Pendaftaran kerja
- Pendaftaran usaha
- SIAKAD
- PMB
- Seleksi perguruan tinggi
- SNPMB
- Pengajuan KIP Kuliah
- Transaksi
- Pembayaran
- Akademik

---

# 20. Alur Final

```text
                 LOGIN
                   │
                   ▼
        RENCANA SETELAH LULUS
                   │
        ┌──────────┼──────────┐
        │          │          │
        ▼          ▼          ▼
      KULIAH     BEKERJA   BERWIRAUSAHA
        │
        ▼
   CARI KAMPUS
        │
        ▼
   PILIH KAMPUS
        │
        ▼
    PILIH PRODI
        │
        ▼
     KONFIRMASI
        │
        ▼
       SUBMIT
        │
        ▼
      SELESAI
```

## Prinsip desain

**Sederhana, cepat, dan mudah digunakan siswa.**

Siswa hanya perlu menjawab satu pertanyaan utama:

> **"Setelah lulus, kamu ingin apa?"**

Kemudian sistem menampilkan form sesuai pilihan siswa.