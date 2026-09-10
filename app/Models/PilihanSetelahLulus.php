<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihanSetelahLulus extends Model
{
    protected $table = 'pilihan_setelah_lulus';

    protected $fillable = [
        'user_id',
        'rencana',
        'perguruan_tinggi_id_external',
        'nama_perguruan_tinggi',
        'program_studi_id_external',
        'nama_program_studi',
        'jenjang',
        'akreditasi',
        'bidang_pekerjaan',
        'keterangan_pekerjaan',
        'bidang_usaha',
        'keterangan_usaha',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this pilihan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
