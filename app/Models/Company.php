<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies'; // si le nom de la table est différent

    protected $fillable = [
        'nom',
        'code_entreprise',
        'matricule',
        'address',
        // autres colonnes
    ];

    // Relation avec les utilisateurs
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
