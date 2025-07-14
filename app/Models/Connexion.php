<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connexion extends Model
{
    use HasFactory;

    protected $table = 'connexions';
    protected $primaryKey = 'NoConnexion';

    public function utilisateur()
    {
        // Attention: Assurez-vous que le modèle 'Utilisateur' existe
        // Si votre modèle utilisateur principal est 'User', cela devrait être User::class
        return $this->belongsTo(Utilisateur::class, 'NoUtilisateur');
    }
}
