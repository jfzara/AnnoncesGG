@component('mail::message')
# Nouveau message pour votre annonce !

Bonjour {{ $annonce->user->name }},

Vous avez reçu un nouveau message concernant votre annonce "**{{ $annonce->DescriptionAbregee }}**".

**De la part de :** {{ $expediteur->name }} ({{ $expediteur->email }})

**Sujet :** {{ $sujet }}

**Message :**
@component('mail::panel')
{{ $corpsMessage }}
@endcomponent

Vous pouvez répondre directement à cet e-mail, car l'adresse de l'expéditeur a été définie comme adresse de réponse.

Voir l'annonce : [{{ $annonce->DescriptionAbregee }}]({{ route('annonces.show', $annonce->NoAnnonce) }})

Merci d'utiliser notre plateforme !
{{ config('app.name') }}
@endcomponent
