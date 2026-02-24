<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'ticket_id',
        'content',
        // TODO: Tambahkan 'user_id' setelah fitur auth diimplementasi
    ];

    /**
     * Relasi: Comment belongs to Ticket
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relasi: Comment belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
