<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'annonce_id',
        'sender_id',
        'receiver_id',
        'content',
        'read_at_sender',
        'read_at_receiver',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'read_at_sender' => 'boolean',
        'read_at_receiver' => 'boolean',
    ];

    /**
     * Chaque message appartient à une annonce.
     */
    public function annonce(): BelongsTo
    {
        return $this->belongsTo(Annonce::class, 'annonce_id');
    }

    /**
     * Chaque message a un expéditeur (User).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Chaque message a un destinataire (User).
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
