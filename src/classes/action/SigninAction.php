<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\AuthnException;


class SigninAction extends Action {
    public function execute(): string {
        if ($this->http_method === 'GET'){
            // Affichage du formulaire de connexion
            $html = <<<FIN
            <form method="POST" action="Main.php?action=signin">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
                <label for="passwd">Mot de passe :</label>
                <input type="password" id="passwd" name="passwd" required>
                <button type="submit">Se connecter</button>
            </form>
            FIN;
        } else {
            // Traitement de la connexion
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['passwd'];

            try {
                AuthnProvider::signin($email, $password);
                $html = "Vous êtes authentifié avec succès. Bienvenue, $email!";
            } catch (AuthnException $e) {
                $html = "Erreur d'authentification : " . $e->getMessage();
            }
        }
        return  $html;

    }
}