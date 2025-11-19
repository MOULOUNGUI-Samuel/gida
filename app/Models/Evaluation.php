<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'demande_id',
        'note',
        'commentaire',
        'ip_address',
        'user_agent'
    ];

    /**
     * Get the demande that owns the evaluation.
     */
    public function demande()
    {
        return $this->belongsTo(\App\Models\Demande::class);
    }
}
