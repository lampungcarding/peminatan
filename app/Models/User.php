<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nisn',
        'nipd',
        'jk',
        'nik',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'kode_pos',
        'no_hp',
        'biodata_confirmed_at',
        'kelas',
        'binaan_kelas',
        'tempat_lahir',
        'tanggal_lahir',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'binaan_kelas' => 'array',
            'biodata_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is guru BK.
     */
    public function isGuruBk(): bool
    {
        return $this->role === 'guru_bk';
    }

    /**
     * Check if user is siswa.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Get array of binaan_kelas for Guru BK.
     */
    public function getKelasBinaanArrayAttribute(): array
    {
        $binaan = $this->binaan_kelas;
        if (is_array($binaan)) {
            return array_values(array_filter($binaan));
        }
        if (is_string($binaan) && !empty($binaan)) {
            $decoded = json_decode($binaan, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
            return array_map('trim', explode(',', $binaan));
        }
        return [];
    }

    /**
     * Get pilihan setelah lulus.
     */
    public function pilihanSetelahLulus()
    {
        return $this->hasOne(PilihanSetelahLulus::class);
    }

    /**
     * Hasil tes minat karier RIASEC.
     */
    public function careerResult()
    {
        return $this->hasOne(CareerResult::class);
    }

    /**
     * Jawaban tes minat karier siswa.
     */
    public function careerAnswers()
    {
        return $this->hasMany(CareerAnswer::class);
    }

    /**
     * Cek apakah sudah menyelesaikan tes minat.
     */
    public function getIsTesSelesaiAttribute(): bool
    {
        return $this->careerResult !== null;
    }

    /**
     * Cek apakah sudah mengisi rencana setelah lulus.
     */
    public function getIsRencanaSelesaiAttribute(): bool
    {
        return $this->pilihanSetelahLulus !== null;
    }

    /**
     * Cek apakah siswa sudah melakukan konfirmasi/validasi biodata diri.
     */
    public function getIsBiodataConfirmedAttribute(): bool
    {
        return $this->biodata_confirmed_at !== null;
    }

    /**
     * Cek apakah data lengkap (biodata terkonfirmasi, tes selesai & rencana selesai).
     */
    public function getIsDataLengkapAttribute(): bool
    {
        return $this->is_biodata_confirmed && $this->is_tes_selesai && $this->is_rencana_selesai;
    }

    /**
     * Label Jenis Kelamin lengkap.
     */
    public function getJenisKelaminTextAttribute(): string
    {
        if (strtoupper((string) $this->jk) === 'L') {
            return 'Laki-laki';
        }
        if (strtoupper((string) $this->jk) === 'P') {
            return 'Perempuan';
        }
        return '-';
    }

    /**
     * Tempat dan Tanggal Lahir terformat.
     */
    public function getTtlFormattedAttribute(): string
    {
        $parts = [];
        if (!empty($this->tempat_lahir) && $this->tempat_lahir !== '-') {
            $parts[] = $this->tempat_lahir;
        }
        if (!empty($this->tanggal_lahir) && $this->tanggal_lahir !== '-') {
            try {
                $parts[] = \Carbon\Carbon::parse($this->tanggal_lahir)->translatedFormat('d F Y');
            } catch (\Exception $e) {
                $parts[] = $this->tanggal_lahir;
            }
        }
        return !empty($parts) ? implode(', ', $parts) : '-';
    }

    /**
     * Tempat dan Tanggal Lahir ringkas (Tempat, dd-mm-yyyy).
     */
    public function getTtlRingkasAttribute(): string
    {
        $parts = [];
        if (!empty($this->tempat_lahir) && $this->tempat_lahir !== '-') {
            $parts[] = $this->tempat_lahir;
        }
        if (!empty($this->tanggal_lahir) && $this->tanggal_lahir !== '-') {
            try {
                $parts[] = \Carbon\Carbon::parse($this->tanggal_lahir)->format('d-m-Y');
            } catch (\Exception $e) {
                $parts[] = $this->tanggal_lahir;
            }
        }
        return !empty($parts) ? implode(', ', $parts) : '-';
    }

    /**
     * Alamat ringkas siswa.
     */
    public function getAlamatLengkapAttribute(): string
    {
        $parts = [];
        if (!empty($this->alamat) && $this->alamat !== '-') {
            $parts[] = $this->alamat;
        }
        if (!empty($this->rt) && $this->rt !== '0' && $this->rt !== '-') {
            $parts[] = 'RT ' . $this->rt;
        }
        if (!empty($this->rw) && $this->rw !== '0' && $this->rw !== '-') {
            $parts[] = 'RW ' . $this->rw;
        }
        if (!empty($this->kelurahan) && $this->kelurahan !== '-') {
            $parts[] = $this->kelurahan;
        }
        if (!empty($this->kecamatan) && $this->kecamatan !== '-') {
            $parts[] = $this->kecamatan;
        }
        if (!empty($this->kabupaten_kota) && $this->kabupaten_kota !== '-') {
            $parts[] = $this->kabupaten_kota;
        }
        return !empty($parts) ? implode(', ', $parts) : '-';
    }
}


