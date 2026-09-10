<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRecommendation extends Model
{
    use HasFactory;

    protected $table = 'career_recommendations';

    protected $fillable = [
        'riasec_code',
        'category',
        'name',
        'description',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForRiasec($query, string|array $codes)
    {
        if (is_array($codes)) {
            return $query->whereIn('riasec_code', $codes);
        }
        return $query->where('riasec_code', $codes);
    }
}
