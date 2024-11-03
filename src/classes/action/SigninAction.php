<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class SigninAction extends Action {
    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $html = <<<HTML
            <div clas ="tout">
                <div class="container">
                    <h2>Connexion</h2>
                    <form method="POST" action="Main.php?action=signin">
                        <link rel="stylesheet" href="src/css_connection.css">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" required>
                        <label for="passwd">Mot de passe :</label>
                        <input type="password" id="passwd" name="passwd" required>
                        <button type="submit">Se connecter</button>
                    </form>
                    <div class="footer">
                        <p>Pas encore de compte ? <a href="Main.php?action=add-user">Inscrivez-vous</a></p>
                    </div>
                </div>
            </div>
        HTML;
        } else {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['passwd'];

            try {
                $userId = AuthnProvider::signin($email, $password);
                $_SESSION['user_id'] = $userId;
                $_SESSION['user'] = serialize(['email' => $email]); 
                $html = <<<HTML
                <p>Vous êtes connecté</p>
                <a href="?action=accueil">Accueil</a>
                HTML;
            } catch (AuthnException $e) {
                $html = "Erreur au moment de l'authentification : " . $e->getMessage();
            }
        }
        return  $html;
    }
}