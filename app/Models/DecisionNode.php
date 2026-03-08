<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionNode extends Model
{
    use HasFactory;

    protected $table = 'decision_nodes';

    protected $fillable = [
        'node_code',
        'parent_id',
        'gejala_id',
        'penyakit_id',
        'yes_branch',
        'no_branch',
        'is_leaf',
    ];

    // Relasi parent
    public function parent()
    {
        return $this->belongsTo(DecisionNode::class, 'parent_id');
    }

    // Relasi children
    public function children()
    {
        return $this->hasMany(DecisionNode::class, 'parent_id');
    }

    // Relasi gejala
    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'gejala_id');
    }

    // Relasi penyakit
    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'penyakit_id');
    }
}
