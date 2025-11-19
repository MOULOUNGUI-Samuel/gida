<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegisterSession extends Model
{
    //
    use HasFactory, HasUuids;
    protected $fillable = [
    'cash_register_id',
    'opening_user_id',
    'heure_ouverture',
    'solde_initial',
    'closing_user_id',
    'heure_fermeture',
    'solde_final_theorique',
    'solde_final_reel',
    'difference',
    'justification'
];

protected $casts = [
    'heure_ouverture' => 'datetime',
    'heure_fermeture' => 'datetime'
];

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }
    public function openingUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opening_user_id');
    }
    public function closingUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closing_user_id');
    }
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }
}
