<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthnException;

class DisplayPlaylistIDAction {
    public function execute(): string {
        $playlistId = (int)($_GET['id'] ?? 0);
        if ($playlistId > 0) {
            try {
                Authz::checkPlaylistOwner($playlistId);
                $repository = DeefyRepository::getInstance();
                $playlist = $repository->findPlaylistById($playlistId);
                if ($playlist) {
                    $renderer = new AudioListRenderer($playlist);
                    $html = $renderer->render(2);
                    return $html .= "<nav><a href=\"?action=playlist\">Retour</a></nav>";
                } else {
                    return "<p>Playlist not found.</p>";
                }
            } catch (AuthnException $e) {
                return "<p>Accès refusé : vous n'êtes pas le propriétaire de cette playlist.</p>
                <nav>
                    <a href=\"?action=accueil\">Retour</a>
                </nav>";
            }
        }
    }
}