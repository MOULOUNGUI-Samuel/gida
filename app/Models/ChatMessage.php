<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'is_bot',
        'ticket_id',
        'context_data'
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'context_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who sent this message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the support ticket this message belongs to
     */
    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }

    /**
     * Scope for bot messages
     */
    public function scopeBot($query)
    {
        return $query->where('is_bot', true);
    }

    /**
     * Scope for user messages
     */
    public function scopeUser($query)
    {
        return $query->where('is_bot', false);
    }

    /**
     * Scope for messages by ticket
     */
    public function scopeByTicket($query, $ticketId)
    {
        return $query->where('ticket_id', $ticketId);
    }

    /**
     * Get formatted timestamp for display
     */
    public function getTimestampAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Get sender name for display
     */
    public function getSenderNameAttribute()
    {
        if ($this->is_bot) {
            return 'Assistant GIDA';
        }
        
        return $this->user ? $this->user->name : 'Utilisateur';
    }

    /**
     * Check if message is from bot
     */
    public function isFromBot()
    {
        return $this->is_bot;
    }

    /**
     * Check if message is from user
     */
    public function isFromUser()
    {
        return !$this->is_bot;
    }
}
