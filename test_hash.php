<?php
// Exemple de mot de passe à tester
$motDePasse = 'Mdp5678'; // Remplacez par le mot de passe à tester

// Hachage de l'utilisateur (par exemple, le hachage pour 'utilisateur2@mail.com')
$hash = 'Mdp5678'; // Remplacez par le hachage de l'utilisateur 2

// Vérification du mot de passe
if (password_verify($motDePasse, $hash)) {
    echo "Le mot de passe est correct.";
} else {
    echo "Le mot de passe est incorrect.";
}
?>