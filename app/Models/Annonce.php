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

    // Définir les attributs qui peuvent être massivement assignés
    protected $fillable = [
        'NoUtilisateur',         // Clé étrangère vers l'utilisateur
        'Parution',
        'Categorie',             // Clé étrangère vers la catégorie
        'DescriptionAbregee',
        'DescriptionComplete',
        'Prix',
        'Photo',
        'MiseAJour',
        'Etat',
        'created_at',            // Ajout explicite même si par défaut
        'updated_at',            // Ajout explicite même si par défaut
    ];

    /**
     * Une annonce appartient à un utilisateur (votre relation existante).
     */
    public function user() // Le nom de la fonction est 'user' pour Laravel
    {
        // On précise que la clé étrangère sur la table 'annonces' est 'NoUtilisateur'
        // et que la clé locale sur la table 'users' est 'id' (clé primaire du modèle User).
        return $this->belongsTo(User::class, 'NoUtilisateur', 'id');
    }

    /**
     * Une annonce appartient à une catégorie.
     */
    public function categorie()
    {
        // Spécifiez la clé étrangère 'Categorie' sur 'annonces'
        // et la clé locale 'NoCategorie' sur la table 'categories'.
        return $this->belongsTo(Categorie::class, 'Categorie', 'NoCategorie');
    }
}
