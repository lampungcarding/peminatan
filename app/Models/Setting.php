<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Simpan atau perbarui nilai setting.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("app_setting_{$key}");
    }

    /**
     * Ambil semua default settings.
     */
    public static function getDefaults(): array
    {
        return [
            'nama_sekolah' => static::get('nama_sekolah', 'SMK Negeri 4 Bandar Lampung'),
            'nama_aplikasi' => static::get('nama_aplikasi', 'Portal Rencana Setelah Lulus'),
            'tahun_ajaran' => static::get('tahun_ajaran', '2026/2027'),
            'batas_pengisian' => static::get('batas_pengisian', date('Y-m-d', strtotime('+30 days'))),
            'status_pendataan' => static::get('status_pendataan', 'buka'),
            'pesan_pengumuman' => static::get('pesan_pengumuman', 'Silakan tentukan rencana masa depanmu setelah lulus dengan teliti. Bagi yang memilih Kuliah, pastikan program studi yang dipilih sesuai minat dan bakat.'),
            // KOP Surat Dokumen & Laporan
            'kop_instansi_atas' => static::get('kop_instansi_atas', 'PEMERINTAH PROVINSI LAMPUNG'),
            'kop_instansi_tengah' => static::get('kop_instansi_tengah', 'DINAS PENDIDIKAN DAN KEBUDAYAAN'),
            'kop_nama_sekolah' => static::get('kop_nama_sekolah', static::get('nama_sekolah', 'SMK NEGERI 4 BANDAR LAMPUNG')),
            'kop_alamat' => static::get('kop_alamat', 'Jl. Hos Cokroaminoto No. 102, Enggal, Kota Bandar Lampung'),
            'kop_kontak' => static::get('kop_kontak', 'Telp: (0721) 261450 • Website: www.smkn4bandarlampung.sch.id • Email: smkn4bl@gmail.com'),
            'kop_kode_pos' => static::get('kop_kode_pos', '35118'),
        ];
    }
}
