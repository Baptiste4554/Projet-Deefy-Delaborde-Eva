<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\AuthnException;

class AddUserAction extends Action {
    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'add-user') {
        $html = <<<HTML
        <form method="POST" action="Main.php?action=add-user">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="passwd">Mot de passe :</label>
            <input type="password" id="passwd" name="passwd" required>
            <label for="confirm_passwd">Confirmez le mot de passe :</label>
            <input type="password" id="confirm_passwd" name="confirm_passwd" required>
            <button type="submit">S'inscrire</button>
        </form>
        HTML;

        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'add-user') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['passwd'];
            $confirmPassword = $_POST['confirm_passwd'];

            try {
                if ($password !== $confirmPassword) {
                    throw new AuthnException("mot de passe incorrect.");
                }

                AuthnProvider::register($email, $password);
                $html = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } catch (AuthnException $e) {
                $html = "Erreur d'inscription : " . $e->getMessage();
            }
        }
        return  $html;

    }
}