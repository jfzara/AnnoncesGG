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
        'role',
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
     * Obtenir le nombre de messages non lus pour cet utilisateur.
     * Cette méthode renvoie directement le compte.
     */
    public function unreadMessagesCount()
    {
        return $this->receivedMessages()
                     ->where('read_at_receiver', false)
                     ->count();
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

    // --- NOUVELLES RELATIONS POUR LES FAVORIS ---
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedAnnonces()
    {
        return $this->belongsToMany(Annonce::class, 'favorites', 'user_id', 'annonce_id', 'id', 'NoAnnonce');
    }

    // --- NOUVELLE RELATION POUR LES COMMENTAIRES ---
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Méthode d'aide pour vérifier le rôle
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
