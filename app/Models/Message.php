<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'annonce_id',
        'sender_id',
        'receiver_id',
        'content',
        'read_at_sender',
        'read_at_receiver',
    ];

    protected $casts = [
        'read_at_sender' => 'boolean',
        'read_at_receiver' => 'boolean',
    ];

    public function annonce(): BelongsTo
    {
        // Spécifie la clé étrangère ('annonce_id' sur la table messages)
        // et la clé locale du modèle Annonce ('NoAnnonce')
        return $this->belongsTo(Annonce::class, 'annonce_id', 'NoAnnonce');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
