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
        $stmt = $repo->getPDO()->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $exists = $stmt->fetchColumn();
        if ($exists) {
            throw new AuthnException("Email déjà utilisé.");
        }
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $repo->getPDO()->prepare("INSERT INTO user (email, passwd, role) VALUES (:email, :passwd, :role)");
        $stmt->execute([
            'email' => $email,
            'passwd' => $hash,
            'role' => 1 
        ]);
    }


    // authentification d'un utilisateur
    public static function signin(string $email, string $password): int {
        $repo = DeefyRepository::getInstance();
        $stmt = $repo->getPDO()->prepare("SELECT id, passwd FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
    

        if (!$user || !password_verify($password, $user['passwd'])) {
            throw new AuthnException("Erreur d'authentification : identifiants invalides.");
        }

        $_SESSION['user'] = serialize(['email' => $email]);
    
        return (int) $user['id'];
    }

    public static function getSignedInUser(): array {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Aucun utilisateur authentifié.");
        }
        $user = unserialize($_SESSION['user']);
        if ($user === false || !is_array($user)) {
            throw new AuthnException("Erreur de désérialisation des données utilisateur.");
        }
        return $user;
    }
    

}
