<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;


class SigninAction extends Action {
    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $html = <<<HTML
            <form method="POST" action="Main.php?action=signin">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="passwd">Mot de passe :</label>
            <input type="password" id="passwd" name="passwd" required>
            <button type="submit">Se connecter</button>
        </form>
        HTML;
        } else {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['passwd'];

            try {
                AuthnProvider::signin($email, $password);
                $html = <<<HTML
                <p>Vous êtes connecté</p>
                <a href="?action=accueil">Accueil</a>
                HTML;
                $_SESSION['user'] = $email; 
            } catch (AuthnException $e) {
                $html = "Erreur d'authentification : " . $e->getMessage();
            }
        }
        return  $html;

    }
}