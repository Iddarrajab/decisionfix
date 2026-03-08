<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    use HasFactory;

    protected $table = 'gejala';

    protected $fillable = ['code', 'gejala'];

    /**
     * Relasi Gejala ke Aturan melalui tabel pivot aturan_gejala
     */
    public function aturan()
    {
        return $this->belongsToMany(Aturan::class, 'aturan_gejala', 'gejala_id', 'aturan_id')
            ->withPivot('cf')
            ->withTimestamps(); // hanya jika pivot-nya pakai timestamps
    }
}
