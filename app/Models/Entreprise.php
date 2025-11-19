<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    protected $table = 'entreprise';
    protected $fillable = ['matricule', 'code', 'nom','societe',];
    public $timestamps = true;

    /**
     * Get the users for the entreprise.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'entreprise_id');
    }

    /**
     * Get the demandes for the entreprise.
     */
    public function demandes(): HasMany
    {
        return $this->hasMany(Demandes::class, 'entreprise_id');
    }

    
}
