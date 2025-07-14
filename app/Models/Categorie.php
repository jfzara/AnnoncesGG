<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'NoCategorie';

    protected $fillable = [
        'Description', // Correspond à votre colonne 'Description' pour le nom de la catégorie
    ];

    /**
     * Une catégorie a plusieurs annonces.
     */
    public function annonces()
    {
        // La clé étrangère sur la table 'annonces' est 'Categorie'.
        // La clé locale sur la table 'categories' est 'NoCategorie'.
        return $this->hasMany(Annonce::class, 'Categorie', 'NoCategorie');
    }
}
