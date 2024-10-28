<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\AuthnException;
use PDOException;
use Exception;

class AuthnProvider {

    public static function signin(string $email, string $password): void {
        try {
            $repo = DeefyRepository::getInstance();
            $query = "SELECT password FROM user WHERE email = :email";
            $stmt = $repo->getPDO()->prepare($query);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch();

            if (!$result || !password_verify($password, $result['password'])) {
                throw new AuthnException("Identifiant ou mot de passe incorrect.");
            }

            $_SESSION['user'] = $email;

        } catch (PDOException $e) {
            throw new AuthnException("Erreur lors de l'accès à la base de données.");
        }
    }
}
