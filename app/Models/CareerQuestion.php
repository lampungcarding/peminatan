<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerQuestion extends Model
{
    use HasFactory;

    protected $table = 'career_questions';

    protected $fillable = [
        'question',
        'section',
        'type_riasec',
        'type_anchor',
        'order_num',
        'status',
    ];

    /**
     * Scope untuk pertanyaan aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk Sesi 1 (RIASEC).
     */
    public function scopeRiasecSection($query)
    {
        return $query->where('section', 'riasec');
    }

    /**
     * Scope untuk Sesi 2 (Career Anchor).
     */
    public function scopeAnchorSection($query)
    {
        return $query->where('section', 'career_anchor');
    }

    /**
     * Dapatkan label dimensi lengkap.
     */
    public function getDimensionNameAttribute(): string
    {
        if ($this->section === 'career_anchor') {
            return match ($this->type_anchor) {
                'TF' => 'Technical/Functional Competence (TF)',
                'GM' => 'General Manager Competence (GM)',
                'AU' => 'Autonomy/Independence (AU)',
                'SE' => 'Security/Stability (SE)',
                'EC' => 'Entrepreneurial Creativity (EC)',
                'SV' => 'Service/Dedication to a Cause (SV)',
                'CH' => 'Pure Challenge (CH)',
                'LS' => 'Lifestyle (LS)',
                default => $this->type_anchor ?? 'Career Anchor',
            };
        }

        return match ($this->type_riasec) {
            'R' => 'Realistic (R)',
            'I' => 'Investigative (I)',
            'A' => 'Artistic (A)',
            'S' => 'Social (S)',
            'E' => 'Enterprising (E)',
            'C' => 'Conventional (C)',
            default => $this->type_riasec ?? 'RIASEC',
        };
    }

    /**
     * Relasi ke jawaban siswa.
     */
    public function answers()
    {
        return $this->hasMany(CareerAnswer::class, 'question_id');
    }
}

