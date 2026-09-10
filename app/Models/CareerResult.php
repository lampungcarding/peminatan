<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerResult extends Model
{
    use HasFactory;

    protected $table = 'career_results';

    protected $fillable = [
        'user_id',
        'realistic_score',
        'investigative_score',
        'artistic_score',
        'social_score',
        'enterprising_score',
        'conventional_score',
        'dominant_type',
        'holland_code',
        'secondary_types',
        'tf_score',
        'gm_score',
        'au_score',
        'se_score',
        'ec_score',
        'sv_score',
        'ch_score',
        'ls_score',
        'dominant_anchor',
        'dominant_anchor_name',
        'recommended_execution_path',
        'anchor_recommendation_title',
        'collaboration_narrative',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'realistic_score' => 'integer',
        'investigative_score' => 'integer',
        'artistic_score' => 'integer',
        'social_score' => 'integer',
        'enterprising_score' => 'integer',
        'conventional_score' => 'integer',
        'tf_score' => 'integer',
        'gm_score' => 'integer',
        'au_score' => 'integer',
        'se_score' => 'integer',
        'ec_score' => 'integer',
        'sv_score' => 'integer',
        'ch_score' => 'integer',
        'ls_score' => 'integer',
    ];

    public const DIMENSION_QUESTION_COUNTS = [
        'R' => 10,
        'I' => 5,
        'A' => 8,
        'S' => 8,
        'E' => 8,
        'C' => 9,
    ];

    public const DIMENSION_MAX_SCORES = [
        'R' => 50,
        'I' => 25,
        'A' => 40,
        'S' => 40,
        'E' => 40,
        'C' => 45,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dapatkan array 6 skor dimensi RIASEC.
     */
    public function getScoresMapAttribute(): array
    {
        $counts = self::DIMENSION_QUESTION_COUNTS;
        $maxScores = self::DIMENSION_MAX_SCORES;

        return [
            'R' => [
                'name' => 'Realistic',
                'label' => 'Praktis & Teknis',
                'score' => $this->realistic_score,
                'count' => $counts['R'],
                'max_score' => $maxScores['R'],
                'percentage' => $maxScores['R'] > 0 ? round(($this->realistic_score / $maxScores['R']) * 100, 1) : 0,
                'average' => $counts['R'] > 0 ? round($this->realistic_score / $counts['R'], 2) : 0,
                'color' => '#3b82f6', // Biru
            ],
            'I' => [
                'name' => 'Investigative',
                'label' => 'Analitis & Riset',
                'score' => $this->investigative_score,
                'count' => $counts['I'],
                'max_score' => $maxScores['I'],
                'percentage' => $maxScores['I'] > 0 ? round(($this->investigative_score / $maxScores['I']) * 100, 1) : 0,
                'average' => $counts['I'] > 0 ? round($this->investigative_score / $counts['I'], 2) : 0,
                'color' => '#06b6d4', // Cyan
            ],
            'A' => [
                'name' => 'Artistic',
                'label' => 'Kreativitas & Seni',
                'score' => $this->artistic_score,
                'count' => $counts['A'],
                'max_score' => $maxScores['A'],
                'percentage' => $maxScores['A'] > 0 ? round(($this->artistic_score / $maxScores['A']) * 100, 1) : 0,
                'average' => $counts['A'] > 0 ? round($this->artistic_score / $counts['A'], 2) : 0,
                'color' => '#ec4899', // Pink
            ],
            'S' => [
                'name' => 'Social',
                'label' => 'Sosial & Membantu',
                'score' => $this->social_score,
                'count' => $counts['S'],
                'max_score' => $maxScores['S'],
                'percentage' => $maxScores['S'] > 0 ? round(($this->social_score / $maxScores['S']) * 100, 1) : 0,
                'average' => $counts['S'] > 0 ? round($this->social_score / $counts['S'], 2) : 0,
                'color' => '#10b981', // Hijau
            ],
            'E' => [
                'name' => 'Enterprising',
                'label' => 'Bisnis & Memimpin',
                'score' => $this->enterprising_score,
                'count' => $counts['E'],
                'max_score' => $maxScores['E'],
                'percentage' => $maxScores['E'] > 0 ? round(($this->enterprising_score / $maxScores['E']) * 100, 1) : 0,
                'average' => $counts['E'] > 0 ? round($this->enterprising_score / $counts['E'], 2) : 0,
                'color' => '#f59e0b', // Oranye / Amber
            ],
            'C' => [
                'name' => 'Conventional',
                'label' => 'Terstruktur & Data',
                'score' => $this->conventional_score,
                'count' => $counts['C'],
                'max_score' => $maxScores['C'],
                'percentage' => $maxScores['C'] > 0 ? round(($this->conventional_score / $maxScores['C']) * 100, 1) : 0,
                'average' => $counts['C'] > 0 ? round($this->conventional_score / $counts['C'], 2) : 0,
                'color' => '#8b5cf6', // Ungu
            ],
        ];
    }

    /**
     * Dapatkan array 8 skor Career Anchors (Schein).
     */
    public function getAnchorScoresMapAttribute(): array
    {
        $max = 15;

        return [
            'TF' => [
                'code' => 'TF',
                'name' => 'Technical/Functional Competence',
                'label' => 'Keahlian Spesialis & Pakar Teknis',
                'score' => $this->tf_score,
                'max_score' => $max,
                'percentage' => round(($this->tf_score / $max) * 100, 1),
                'color' => '#3b82f6',
            ],
            'GM' => [
                'code' => 'GM',
                'name' => 'General Manager Competence',
                'label' => 'Kepemimpinan & Manajerial',
                'score' => $this->gm_score,
                'max_score' => $max,
                'percentage' => round(($this->gm_score / $max) * 100, 1),
                'color' => '#f59e0b',
            ],
            'AU' => [
                'code' => 'AU',
                'name' => 'Autonomy/Independence',
                'label' => 'Kemandirian & Kebebasan Kerja',
                'score' => $this->au_score,
                'max_score' => $max,
                'percentage' => round(($this->au_score / $max) * 100, 1),
                'color' => '#ec4899',
            ],
            'SE' => [
                'code' => 'SE',
                'name' => 'Security/Stability',
                'label' => 'Kestabilan Gaji & Rasa Aman',
                'score' => $this->se_score,
                'max_score' => $max,
                'percentage' => round(($this->se_score / $max) * 100, 1),
                'color' => '#10b981',
            ],
            'EC' => [
                'code' => 'EC',
                'name' => 'Entrepreneurial Creativity',
                'label' => 'Kreativitas Bisnis & Wirausaha',
                'score' => $this->ec_score,
                'max_score' => $max,
                'percentage' => round(($this->ec_score / $max) * 100, 1),
                'color' => '#8b5cf6',
            ],
            'SV' => [
                'code' => 'SV',
                'name' => 'Service/Dedication to a Cause',
                'label' => 'Pengabdian & Dampak Sosial',
                'score' => $this->sv_score,
                'max_score' => $max,
                'percentage' => round(($this->sv_score / $max) * 100, 1),
                'color' => '#06b6d4',
            ],
            'CH' => [
                'code' => 'CH',
                'name' => 'Pure Challenge',
                'label' => 'Tantangan Murni & Riset Inovasi',
                'score' => $this->ch_score,
                'max_score' => $max,
                'percentage' => round(($this->ch_score / $max) * 100, 1),
                'color' => '#ef4444',
            ],
            'LS' => [
                'code' => 'LS',
                'name' => 'Lifestyle',
                'label' => 'Keseimbangan Gaya Hidup & WFH',
                'score' => $this->ls_score,
                'max_score' => $max,
                'percentage' => round(($this->ls_score / $max) * 100, 1),
                'color' => '#64748b',
            ],
        ];
    }

    /**
     * Dapatkan Badge Format untuk Jalur Eksekusi Utama.
     */
    public function getExecutionPathBadgeAttribute(): array
    {
        return match ($this->recommended_execution_path) {
            'kuliah' => [
                'label' => '🎓 KULIAH (S1/D4)',
                'class' => 'bg-primary',
                'text' => 'Pendalaman Teori Akademik & Spesialisasi Pakar',
                'icon' => 'bi-mortarboard-fill',
            ],
            'berwirausaha' => [
                'label' => '🚀 BERWIRAUSAHA / FREELANCE',
                'class' => 'bg-warning text-dark',
                'text' => 'Membangun Kemandirian Profesional & Bisnis Mandiri',
                'icon' => 'bi-rocket-takeoff-fill',
            ],
            'bekerja' => [
                'label' => '💼 KERJA LANGSUNG (Industri / BUMN)',
                'class' => 'bg-success',
                'text' => 'Kepastian Finansial, Struktur Kerja Aman, & Industri',
                'icon' => 'bi-briefcase-fill',
            ],
            default => [
                'label' => '🎓 KULIAH / 💼 KERJA',
                'class' => 'bg-info text-dark',
                'text' => 'Jalur Adaptif Sesuai Minat',
                'icon' => 'bi-compass-fill',
            ],
        };
    }

    /**
     * Deskripsi naratif tipe dominan.
     */
    public function getDominantDescriptionAttribute(): string
    {
        return match (strtolower($this->dominant_type)) {
            'realistic' => 'Kamu memiliki ketertarikan kuat pada hal-hal praktis, teknis, aktivitas luar ruangan, dan suka bekerja langsung dengan perkakas, mesin, atau teknologi fisik.',
            'investigative' => 'Kamu menyukai aktivitas yang melibatkan pemecahan masalah rumit, logika, analisis mendalam, penelitian ilmiah, dan mencari tahu bagaimana suatu sistem bekerja.',
            'artistic' => 'Kamu memiliki daya imajinasi tinggi, senang mengekspresikan ide kreatif, menyukai seni, desain visual, multimedia, dan kebebasan dalam berkarya.',
            'social' => 'Kamu memiliki kepekaan antarpribadi yang tinggi, senang membantu sesama, mengajar, membimbing, dan bekerja sama dalam suasana kekeluargaan.',
            'enterprising' => 'Kamu berjiwa pemimpin, percaya diri dalam berbicara, senang meyakinkan orang lain, tertarik pada strategi bisnis, penjualan, dan mengelola proyek.',
            'conventional' => 'Kamu menyukai keteraturan, teliti terhadap data dan angka, terstruktur, sistematis, serta nyaman bekerja dengan prosedur yang terorganisir rapi.',
            default => 'Kamu memiliki profil minat karier yang seimbang dan adaptif.',
        };
    }

    /**
     * Kode 3 huruf Holland tanpa strip.
     */
    public function getCleanHollandCodeAttribute(): string
    {
        return str_replace(['-', ' '], '', $this->holland_code ?? '');
    }

    /**
     * Huruf pertama / kode dominan 1 karakter.
     */
    public function getDominantCodeAttribute(): string
    {
        return explode('-', $this->holland_code ?? 'R')[0] ?? 'R';
    }

    public function getScoreRAttribute(): int
    {
        return $this->realistic_score ?? 0;
    }

    public function getScoreIAttribute(): int
    {
        return $this->investigative_score ?? 0;
    }

    public function getScoreAAttribute(): int
    {
        return $this->artistic_score ?? 0;
    }

    public function getScoreSAttribute(): int
    {
        return $this->social_score ?? 0;
    }

    public function getScoreEAttribute(): int
    {
        return $this->enterprising_score ?? 0;
    }

    public function getScoreCAttribute(): int
    {
        return $this->conventional_score ?? 0;
    }
}
