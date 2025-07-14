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

    // Ajout de la relation pour les messages liés à cette annonce
    public function messages()
    {
        return $this->hasMany(Message::class, 'annonce_id', 'NoAnnonce');
    }
}
