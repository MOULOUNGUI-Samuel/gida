<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory, HasUuids;
   protected $fillable = [
    'debtor_account_id',
    'numero_pret',
    'montant_accorde',
    'taux_interet_annuel',
    'date_demande',
    'date_approbation',
    'date_decaissement',
    'duree_en_mois',
    'statut'
];

protected $casts = [
    'date_demande' => 'date',
    'date_approbation' => 'date',
    'date_decaissement' => 'date'
];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debtor_account_id');
    }
    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
    }
}
