@component('mail::message')
# Nouveau message pour votre annonce sur TrouveTout ! {{-- Titre plus engageant --}}

Bonjour **{{ $annonce->user->name }}**,

Vous avez reçu un nouveau message très intéressant concernant votre annonce : "**{{ $annonce->DescriptionAbregee }}**".

<p style="text-align: center; margin: 20px 0;">
    <img src="{{ asset('images/email_icon.png') }}" alt="Icône de message" style="width: 80px; height: auto; display: block; margin: 0 auto;"> {{-- Icône visuelle --}}
</p>

---

### Détails du message :

* **De la part de :** **{{ $expediteur->name }}** ({{ $expediteur->email }})
* **Sujet :** *{{ $sujet }}*

<hr style="border: 0; height: 1px; background: #eee; margin: 20px 0;"> {{-- Séparateur --}}

### Contenu du message :

@component('mail::panel')
{{ $corpsMessage }}
@endcomponent

---

Vous pouvez répondre directement à cet e-mail pour communiquer avec l'expéditeur. L'adresse de réponse a été configurée pour vous faciliter la tâche.

@component('mail::button', ['url' => route('annonces.show', $annonce->NoAnnonce), 'color' => 'primary']) {{-- Bouton stylisé --}}
Voir votre annonce en ligne
@endcomponent

Si le bouton ci-dessus ne fonctionne pas, vous pouvez copier et coller ce lien dans votre navigateur :
[{{ route('annonces.show', $annonce->NoAnnonce) }}]({{ route('annonces.show', $annonce->NoAnnonce) }})

Merci de faire partie de la communauté **TrouveTout** et de contribuer à la réussite de vos annonces !

Cordialement,
L'équipe de **{{ config('app.name') }}**
@endcomponent
