<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AturanGejala extends Model
{
    use HasFactory;

    protected $table = 'aturan_gejala';
    public $timestamps = false; // pivot biasanya tidak punya timestamps

    protected $fillable = ['aturan_id', 'gejala_id', 'cf'];

    public function aturan()
    {
        return $this->belongsTo(Aturan::class, 'aturan_id');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'gejala_id');
    }
}
