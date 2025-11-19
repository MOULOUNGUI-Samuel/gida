<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operation extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
    'user_id',
    'cash_register_session_id',
    'type',
    'montant',
    'description',
    'source_account_id',
    'source_cash_register_id',
    'destination_account_id',
    'destination_cash_register_id',
    'related_loan_id',
    'statut',
    'cancellation_user_id',
    'heure_annulation',
    'date_operation'
];

protected $casts = [
    'date_operation' => 'datetime',
    'heure_annulation' => 'datetime'
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function cashRegisterSession(): BelongsTo
    {
        return $this->belongsTo(CashRegisterSession::class, 'cash_register_session_id');
    }
    public function sourceAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'source_account_id');
    }
    public function sourceCashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'source_cash_register_id');
    }
    public function destinationAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'destination_account_id');
    }
    public function destinationCashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'destination_cash_register_id');
    }
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'related_loan_id');
    }
    public function cancellationUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancellation_user_id');
    }
}
