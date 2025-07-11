<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the messages that are unread for the user.
     * This now correctly uses 'where' for a boolean 'read_at_receiver' field.
     */
    public function unreadMessages()
    {
        // Modifié pour utiliser 'where(false)' car 'read_at_receiver' est un booléen
        return $this->hasMany(Message::class, 'receiver_id')
                    ->where('read_at_receiver', false); // <--- C'est la LIGNE CLÉ modifiée
    }

    /**
     * Get all conversations the user is a part of (as sender or receiver).
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id')
                    ->orWhere('receiver_id', $this->id);
    }
}
