<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class Authz {

    public static function checkRole(int $expectedRole): void {
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();
        $stmt = $repo->getPDO()->prepare("SELECT role FROM user WHERE email = :email");
        $stmt->execute(['email' => $user['email']]);
        $role = $stmt->fetchColumn();

        if ($role !== $expectedRole) {
            throw new AuthnException("Accès refusé : rôle insuffisant.");
        }
    }

    public static function checkPlaylistOwner(int $playlistId): void {
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();
        $stmt = $repo->getPDO()->prepare("SELECT id, role FROM user WHERE email = :email");
        $stmt->execute(['email' => $user['email']]);
        $userData = $stmt->fetch();

        if ($userData['role'] === 100) {
            return;
        }

        $stmt = $repo->getPDO()->prepare("SELECT COUNT(*) FROM user2playlist WHERE id_user = :userId AND id_pl = :playlistId");
        $stmt->execute(['userId' => $userData['id'], 'playlistId' => $playlistId]);
        $isOwner = $stmt->fetchColumn();

        if (!$isOwner) {
            throw new AuthnException("Accès refusé : vous n'êtes pas le propriétaire de cette playlist.");
        }
    }
}