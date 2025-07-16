<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonces';
    protected $primaryKey = 'NoAnnonce';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'NoUtilisateur',
        'Parution',
        'Categorie',
        'Titre',
        'DescriptionAbregee',
        'DescriptionComplete',
        'Prix',
        'Photo',
        'MiseAJour',
        'Etat',
        'DateFin',
    ];

    protected $casts = [
        'Parution' => 'datetime',
        'MiseAJour' => 'datetime',
        'DateFin' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'NoUtilisateur', 'id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'Categorie', 'NoCategorie');
    }

    // Relation pour les messages liés à cette annonce
    public function messages()
    {
        return $this->hasMany(Message::class, 'annonce_id', 'NoAnnonce');
    }

    // --- NOUVELLE RELATION POUR LES FAVORIS ---
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'annonce_id', 'user_id', 'NoAnnonce', 'id');
    }

    // --- NOUVELLE RELATION POUR LES COMMENTAIRES ---
    public function comments()
    {
        return $this->hasMany(Comment::class, 'annonce_id', 'NoAnnonce');
    }
}
