<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'diagnosa';

    protected $fillable = [
        'code',
        'nama_user',
        'gejala_dipilih',
        'cf_gejala',
        'penyakit_id',
        'hasil_cf',
        'hasil_keputusan',
    ];

    protected $casts = [
        'gejala_dipilih' => 'array', // otomatis decode JSON ke array
        'cf_gejala' => 'array', // otomatis decode JSON ke array
        'hasil_cf' => 'float',
    ];

    /**
     * Relasi ke model Penyakit
     */
    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'penyakit_id');
    }
}
