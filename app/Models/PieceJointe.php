<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    protected $table = 'piece_jointes';

    protected $fillable = [
        'demande_id',
        'nom_fichier',
        'chemin',
        'type_mime',
        'taille',
        'extension',
    ];

    public function demande()
    {
        return $this->belongsTo(Demandes::class, 'demande_id');
    }
}
