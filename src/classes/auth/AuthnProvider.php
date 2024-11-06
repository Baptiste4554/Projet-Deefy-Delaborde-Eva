<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {
    

    // solidité mot de passe
    public static function checkPasswordStrength(string $pass, int $minimumLength = 10): bool {
        $length = (strlen($pass) >= $minimumLength);
        $digit = preg_match("#[\d]#", $pass);
        $special = preg_match("#[\W]#", $pass);
        $lower = preg_match("#[a-z]#", $pass);
        $upper = preg_match("#[A-Z]#", $pass);
        return $length && $digit && $special && $lower && $upper;
    }

    // enregister un nouveau utilisateur
    public static function register(string $email, string $password): void {
        if (!self::checkPasswordStrength($password)) {
            throw new AuthnException("Mot de passe invalide.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Email invalide.");
        }
        $repo = DeefyRepository::getInstance();
        $exists = $repo->emailExists($email);   
        if ($exists) {
            throw new AuthnException("Email déjà utilisé.");
        }
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $repo->insertUser($email, $hash, 1);
    }


    // authentification d'un utilisateur
    public static function signin(string $email, string $password): int {
        $repo = DeefyRepository::getInstance();
        $user = $repo->getUserByEmail($email);
    

        if (!$user || !password_verify($password, $user['passwd'])) {
            throw new AuthnException("Erreur d'authentification : identifiants invalides.");
        }
        $_SESSION['user'] = serialize(['email' => $email]);
    
        return (int) $user['id'];
    }
    // retourne l'utilisateur authentifie
    public static function getSignedInUser(): array {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Aucun utilisateur authentifié.");
        }
        $user =unserialize($_SESSION['user']);

        return $user;
    }
    

}
