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

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')
                    ->where('read_at_receiver', false);
    }

    // Relation pour les annonces publiées par cet utilisateur
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'NoUtilisateur', 'id');
    }

    // Relations pour les messages envoyés et reçus (pour une meilleure clarté)
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    // La relation 'conversations' a été supprimée car elle référençait un modèle 'Conversation'
    // qui n'était pas défini et compliquait inutilement la logique qui est mieux gérée dans le contrôleur.
}
