<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ajout de la colonne 'role'
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
     * Un utilisateur (User) peut avoir plusieurs annonces.
     */
    public function annonces()
    {
        // Spécifiez la clé étrangère 'NoUtilisateur' dans la table 'annonces'
        // et la clé locale 'id' dans la table 'users'.
        return $this->hasMany(Annonce::class, 'NoUtilisateur', 'id');
    }

    /**
     * Vérifie si l'utilisateur a le rôle 'admin'.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur a le rôle 'user'.
     */
    public function isUser()
    {
        return $this->role === 'user';
    }
}
