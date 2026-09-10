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
     * Check if user is siswa.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
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

