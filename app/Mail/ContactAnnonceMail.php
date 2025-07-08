<?php

namespace App\Mail;

use App\Models\Annonce; // Importez le modèle Annonce
use App\Models\User;    // Importez le modèle User
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address; // Importez Address pour le from

class ContactAnnonceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $annonce;
    public $sujet;
    public $corpsMessage;
    public $expediteur; // L'utilisateur qui envoie le message

    /**
     * Crée une nouvelle instance de message.
     */
    public function __construct(Annonce $annonce, string $sujet, string $corpsMessage, User $expediteur)
    {
        $this->annonce = $annonce;
        $this->sujet = $sujet;
        $this->corpsMessage = $corpsMessage;
        $this->expediteur = $expediteur;
    }

    /**
     * Obtenez l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        // L'expéditeur du mail sera l'adresse configurée dans .env (MAIL_FROM_ADDRESS)
        // Mais le "Reply-To" sera l'e-mail de l'utilisateur qui a envoyé le message.
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')), // L'adresse d'envoi réelle
            replyTo: [
                new Address($this->expediteur->email, $this->expediteur->name), // L'adresse de réponse
            ],
            subject: 'Contact pour votre annonce : ' . $this->sujet, // Le sujet de l'e-mail reçu par l'auteur
        );
    }

    /**
     * Obtenez la définition du contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.annonces.contact', // Nous allons créer cette vue Markdown
            with: [
                'annonce' => $this->annonce,
                'sujet' => $this->sujet,
                'corpsMessage' => $this->corpsMessage,
                'expediteur' => $this->expediteur,
            ],
        );
    }

    /**
     * Obtenez les pièces jointes pour le message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
