<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class Authz {


    public static function checkPlaylistOwner(int $playlistId): void {
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();
        $userData = $repo->getRoleByEmail($user['email']);

        if ($userData['role'] === 100) {
            return; // on fait rien si il est admin car il a acces a tout
        }
        $isOwner= $repo->etreProprioPlaylist($userData['id'], $playlistId);

        if (!$isOwner) {
            throw new AuthnException("Accès refusé : vous n'êtes pas le propriétaire de cette playlist.");
        }
    }
}