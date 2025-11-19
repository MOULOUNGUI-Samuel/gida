<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets'; // si le nom de la table est différent
    protected $fillable = [
        'societe_assignee_id', 
        'user_id', 
        'status', 
        'priorite', 
        'title', 
        'description'
    ];
}
