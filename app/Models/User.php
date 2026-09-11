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
     * Cek apakah data lengkap (tes selesai & rencana selesai).
     */
    public function getIsDataLengkapAttribute(): bool
    {
        return $this->is_tes_selesai && $this->is_rencana_selesai;
    }
}

