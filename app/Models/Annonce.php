<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonces';          // Nom de votre table
    protected $primaryKey = 'NoAnnonce';    // Votre clé primaire
    public $incrementing = true;            // C'est un auto-incrément
    protected $keyType = 'int';             // Le type de la clé primaire

    // Définir les attributs qui peuvent être massivement assignés.
    // CES NOMS CORRESPONDENT EXACTEMENT À VOS COLONNES DE DB.
    protected $fillable = [
        'NoUtilisateur',        // Clé étrangère vers l'utilisateur
        'Parution',             // Colonne `Parution`
        'Categorie',            // Colonne `Categorie`
        'Titre',                // <<< AJOUTÉ : Colonne `Titre` (c'était la colonne manquante)
        'DescriptionAbregee',   // Colonne `DescriptionAbregee`
        'DescriptionComplete',  // Colonne `DescriptionComplete`
        'Prix',                 // Colonne `Prix`
        'Photo',                // Colonne `Photo` (pour l'image)
        'MiseAJour',            // Colonne `MiseAJour`
        'Etat',                 // Colonne `Etat`
        'DateFin',              // Colonne `DateFin`
    ];

    // Indiquer à Eloquent de traiter ces colonnes comme des instances Carbon (dates)
    protected $casts = [
        'Parution' => 'datetime',
        'MiseAJour' => 'datetime',
        'DateFin' => 'datetime', // Assurez-vous que DateFin est casté en datetime
    ];

    /**
     * Une annonce appartient à un utilisateur.
     */
    public function user()
    {
        // L'annonce a une clé étrangère 'NoUtilisateur' qui référence la clé primaire 'id' de la table 'users'.
        return $this->belongsTo(User::class, 'NoUtilisateur', 'id');
    }

    /**
     * Une annonce appartient à une catégorie.
     */
    public function categorie()
    {
        // La clé étrangère sur la table 'annonces' est 'Categorie',
        // et la clé locale sur la table 'categories' est 'NoCategorie'.
        return $this->belongsTo(Categorie::class, 'Categorie', 'NoCategorie');
    }

    // Si vous souhaitez une méthode pour obtenir la description complète ou abrégée
    public function getDescriptionAttribute()
    {
        return $this->DescriptionComplete; // Ou DescriptionAbregee selon ce que vous utilisez le plus souvent
    }

    // Si vous souhaitez une méthode pour obtenir l'URL de l'image
    public function getImageUrlAttribute()
    {
        return $this->Photo; // Utilise la colonne 'Photo'
    }
}
