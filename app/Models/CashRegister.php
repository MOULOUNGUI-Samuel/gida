<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashRegister extends Model
{
    use HasFactory, HasUuids;
   protected $fillable = [
    'user_id',
    'nom',
    'type',
    'solde',
    'seuil_min',
    'seuil_max'
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
