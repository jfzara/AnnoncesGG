<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'NoCategorie';

    // Une catégorie a plusieurs annonces
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'Categorie');
    }
}