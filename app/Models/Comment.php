<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'annonce_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function annonce()
    {
        // Spécifiez 'NoAnnonce' comme clé locale si votre modèle Annonce n'utilise pas 'id' par défaut
        return $this->belongsTo(Annonce::class, 'annonce_id', 'NoAnnonce');
    }
}
