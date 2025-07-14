<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\User;
use App\Mail\ContactAnnonceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact pour une annonce spécifique.
     */
    public function create(Annonce $annonce)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour contacter un annonceur.');
        }

        if (Auth::id() === $annonce->NoUtilisateur) {
            return redirect()->route('annonces.show', $annonce->NoAnnonce)->with('error', 'Vous ne pouvez pas contacter l\'auteur de votre propre annonce.');
        }

        return view('annonces.contact', compact('annonce'));
    }

    /**
     * Traite l'envoi du formulaire de contact et envoie l'e-mail.
     */
    public function send(Request $request, Annonce $annonce)
    {
        // --- DÉBOGAGE CIBLÉ (utilisez un dd() à la fois) ---

        // dd('Données du formulaire:', $request->all()); // Commenté/Supprimé

        $request->validate([
            'message' => 'required|string|min:10',
        ]);

        $expediteur = Auth::user();
        $destinataire = $annonce->user; // La relation 'user' sur le modèle Annonce est correcte

        // dd('Expéditeur:', $expediteur, 'Destinataire:', $destinataire); // Commenté/Supprimé

        if (!$destinataire || !$destinataire->email) {
            return back()->withInput()->with('error', 'Impossible de trouver l\'adresse e-mail de l\'auteur de cette annonce.');
        }

        try {
            $mailable = new ContactAnnonceMail(
                $annonce,
                $expediteur,
                $request->input('message')
            );

            // dd($mailable); // Commenté/Supprimé

            Mail::to($destinataire->email)->send($mailable);

            // dd('Email envoyé avec succès !'); // Commenté/Supprimé

            return redirect()->route('annonces.show', $annonce->NoAnnonce)->with('success', 'Votre message a été envoyé à l\'annonceur !');

        } catch (\Exception $e) {
            // AFFICHEZ L'ERREUR EXACTE ICI
            // dd('Erreur lors de l\'envoi du courriel : ' . $e->getMessage(), 'Trace:', $e->getTrace()); // Commenté/Supprimé

            // Laissez un message d'erreur pour l'utilisateur
            return back()->withInput()->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer. (Détails: ' . $e->getMessage() . ')');
        }
    }
}
