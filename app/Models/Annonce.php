<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonces';
    protected $primaryKey = 'NoAnnonce';

    // Une annonce appartient à un utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'NoUtilisateur');
    }

    // Une annonce appartient à une catégorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'Categorie');
    }
}