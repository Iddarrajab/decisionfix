<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    protected $table = 'penyakit'; // karena default-nya "penyakits"

    protected $fillable = [
        'code',
        'penyakit',
        'solusi',
        'obat'
    ];

    public function aturan()
    {
        return $this->hasMany(Aturan::class);
    }
    // Di App\Models\Penyakit
    public function diagnosa()
    {
        return $this->hasMany(Diagnosa::class);
    }
}
