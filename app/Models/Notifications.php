<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    // Nom de la table (optionnel si le nom est bien au pluriel)
    protected $table = 'notifications';

    // Champs qu'on autorise pour le mass assignment (create, update)
    protected $fillable = [
        'id_demande',
        'id_user',
        'message',
        'type_notification',
        'read',
    ];

    
}
