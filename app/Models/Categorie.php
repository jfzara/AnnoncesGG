<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'NoCategorie';

    // Les colonnes que vous pourriez vouloir assigner massivement
    protected $fillable = [
        'Description', // Correspond à votre colonne 'Description'
        // 'created_at', 'updated_at' sont gérés automatiquement
    ];

    /**
     * Une catégorie a plusieurs annonces.
     */
    public function annonces()
    {
        // La clé étrangère sur la table 'annonces' est 'Categorie' (votre colonne).
        // La clé locale sur la table 'categories' est 'NoCategorie' (votre clé primaire).
        // Laravel déduira la clé locale, mais la spécifier est plus explicite.
        return $this->hasMany(Annonce::class, 'Categorie', 'NoCategorie');
    }
}
