<?php

namespace App\Http\Controllers;

use App\Models\Annonce; // Assurez-vous d'importer le modèle Annonce
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Pour envoyer des e-mails
use App\Mail\ContactAnnonceMail; // Nous allons créer ce Mailable

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact pour une annonce spécifique.
     * Accessible par tous.
     */
    public function create(Annonce $annonce)
    {
        // Passe l'annonce à la vue pour afficher ses détails dans le formulaire
        return view('annonces.contact', compact('annonce'));
    }

    /**
     * Envoie l'e-mail de contact à l'auteur de l'annonce.
     * Requiert une authentification pour l'expéditeur.
     */
    public function send(Request $request, Annonce $annonce)
    {
        // Validation des données du formulaire
        $request->validate([
            'sujet' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'sujet.required' => 'Le sujet est obligatoire.',
            'sujet.max' => 'Le sujet ne doit pas dépasser 255 caractères.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
        ]);

        // Assurez-vous que l'annonce a bien un utilisateur associé et que cet utilisateur a une adresse email
        if (!$annonce->user || !$annonce->user->email) {
            return back()->with('error', 'Impossible de trouver l\'auteur de cette annonce. Veuillez réessayer plus tard.');
        }

        // Envoi de l'e-mail
        try {
            Mail::to($annonce->user->email)->send(new ContactAnnonceMail(
                $annonce,
                $request->input('sujet'),
                $request->input('message'),
                auth()->user() // L'utilisateur connecté qui envoie le message
            ));

            return back()->with('success', 'Votre message a été envoyé à l\'auteur de l\'annonce !');

        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi de l'e-mail (problème de configuration SMTP, etc.)
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
        }
    }
}
