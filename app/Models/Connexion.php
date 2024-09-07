<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connexion extends Model
{
    use HasFactory;

    protected $table = 'connexions';
    protected $primaryKey = 'NoConnexion';

    // Une connexion appartient à un utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'NoUtilisateur');
    }
}
