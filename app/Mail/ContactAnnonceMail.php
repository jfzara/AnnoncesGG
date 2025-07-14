<?php

namespace App\Mail;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAnnonceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $annonce;
    public $expediteur;
    public $messageContact;
    public $sujet; // Ajouté pour correspondre à votre vue
    public $corpsMessage; // Ajouté pour correspondre à votre vue

    /**
     * Create a new message instance.
     */
    public function __construct(Annonce $annonce, User $expediteur, string $messageContact)
    {
        $this->annonce = $annonce;
        $this->expediteur = $expediteur;
        $this->messageContact = $messageContact;

        // Définir le sujet et le corps du message pour la vue
        $this->sujet = 'Nouveau message pour votre annonce !'; // Vous pouvez personnaliser le sujet ici
        $this->corpsMessage = $messageContact; // Le contenu principal du message
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact pour votre annonce: ' . $this->annonce->Titre, // Utilisez le Titre de l'annonce ici
            replyTo: $this->expediteur->email, // Ajouté pour permettre la réponse directe
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.annonces.contact', // <<< CORRIGÉ ICI : le chemin complet vers votre vue
            with: [
                'annonce' => $this->annonce,
                'expediteur' => $this->expediteur,
                'messageContact' => $this->messageContact,
                'sujet' => $this->sujet,      // Passe le sujet à la vue
                'corpsMessage' => $this->corpsMessage, // Passe le corps du message à la vue
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
