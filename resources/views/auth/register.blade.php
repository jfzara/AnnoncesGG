<form method="POST" action="{{ route('register') }}">
    @csrf
    <div>
        <label for="Nom">Nom :</label>
        <input type="text" name="Nom" id="Nom" required>
    </div>
    <div>
        <label for="Prenom">Prénom :</label>
        <input type="text" name="Prenom" id="Prenom" required>
    </div>
    <div>
        <label for="Courriel">Courriel :</label>
        <input type="email" name="Courriel" id="Courriel" required>
    </div>
    <div>
        <label for="MotDePasse">Mot de Passe :</label>
        <input type="password" name="MotDePasse" id="MotDePasse" required>
    </div>
    <div>
        <label for="MotDePasse_confirmation">Confirmer le Mot de Passe :</label>
        <input type="password" name="MotDePasse_confirmation" id="MotDePasse_confirmation" required>
    </div>
    <div>
        <label for="NoTelMaison">Téléphone Maison :</label>
        <input type="text" name="NoTelMaison" id="NoTelMaison" placeholder="(999) 999-9999P | N">
    </div>
    <div>
        <label for="NoTelTravail">Téléphone Travail :</label>
        <input type="text" name="NoTelTravail" id="NoTelTravail" placeholder="(999) 999-9999 #9999P | N">
    </div>
    <div>
        <label for="NoTelCellulaire">Téléphone Cellulaire :</label>
        <input type="text" name="NoTelCellulaire" id="NoTelCellulaire" placeholder="(999) 999-9999P | N">
    </div>
    <button type="submit">S'inscrire</button>
</form>