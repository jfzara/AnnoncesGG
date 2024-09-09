<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    // Le nom de la table associée
    protected $table = 'utilisateurs';

    // Les attributs qui peuvent être remplis
    protected $fillable = [
        'Courriel', 
        'MotDePasse',
        'Nom', 
        'Prenom',
        'NoTelMaison', 
        'NoTelTravail', 
        'NoTelCellulaire',
        'Statut', 
        'NbConnexions'
    ];

    // Désactiver la gestion des timestamps
    public $timestamps = false;

    // Champ qui sera utilisé pour l'authentification (au lieu de 'email')
    public function getAuthIdentifierName()
    {
        return 'Courriel';
    }

    // Utiliser le champ 'MotDePasse' pour l'authentification
    public function getAuthPassword()
    {
        return $this->MotDePasse;
    }
}