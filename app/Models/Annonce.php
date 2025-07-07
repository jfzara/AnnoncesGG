<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonces';          // Nom de votre table
    protected $primaryKey = 'NoAnnonce';    // Votre clé primaire
    public $incrementing = true;            // Par défaut à true si c'est un auto-incrément
    protected $keyType = 'int';             // Par défaut à int si c'est un entier

    // Définir les attributs qui peuvent être massivement assignés
    protected $fillable = [
        'NoUtilisateur',         // Clé étrangère vers l'utilisateur
        'Parution',
        'Categorie',
        'DescriptionAbregee',
        'DescriptionComplete',
        'Prix',
        'Photo',
        'MiseAJour',
        'Etat',
        // 'created_at' et 'updated_at' sont gérés automatiquement par Model si non spécifié comme timestamps personnalisés.
    ];

    // Vous n'avez pas besoin de spécifier les timestamps ici si les noms sont 'created_at' et 'updated_at' par défaut.
    // Si 'Parution' et 'MiseAJour' sont vos timestamps de Laravel, il faudrait le configurer :
    // const CREATED_AT = 'Parution';
    // const UPDATED_AT = 'MiseAJour';
    // Pour l'instant, je vais considérer created_at et updated_at comme vos timestamps de Laravel, et Parution/MiseAJour comme des colonnes standards.


    /**
     * Une annonce appartient à un utilisateur (votre relation existante, mais vers le modèle User).
     */
    public function user() // Nom de la relation: "user" pour coller à Auth::user()
    {
        // On précise que la clé étrangère sur la table 'annonces' est 'NoUtilisateur'
        // et que la clé locale sur la table 'users' est 'id' (clé primaire du modèle User).
        return $this->belongsTo(User::class, 'NoUtilisateur', 'id');
    }

    /**
     * Une annonce appartient à une catégorie (votre relation existante).
     */
    public function categorie()
    {
        // Spécifiez la clé étrangère 'Categorie' sur 'annonces'
        // et la clé locale 'NoCategorie' sur la table 'categories' (à confirmer si c'est bien 'NoCategorie' pour la PK de catégorie)
        return $this->belongsTo(Categorie::class, 'Categorie', 'NoCategorie');
    }
}
