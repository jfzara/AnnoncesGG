<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Laravel devinera que la table est 'users' et que la clé primaire est 'id'.
    // Pas besoin de protected $table = 'users'; ni protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // N'oubliez pas d'ajouter 'role' ici
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
        // On indique à Laravel que la clé étrangère dans la table 'annonces' qui lie à l'utilisateur est 'NoUtilisateur',
        // et que la clé locale sur la table 'users' (celle-ci) est 'id'.
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
