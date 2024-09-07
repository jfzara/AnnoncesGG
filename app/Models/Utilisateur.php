<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'NoUtilisateur';

    protected $fillable = [
        'Courriel', 'MotDePasse', 'Nom', 'Prenom', 'NoTelMaison', 'NoTelTravail', 'NoTelCellulaire', 'Statut'
    ];

    // Un utilisateur a plusieurs annonces
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'NoUtilisateur');
    }

    // Un utilisateur a plusieurs connexions
    public function connexions()
    {
        return $this->hasMany(Connexion::class, 'NoUtilisateur');
    }
}