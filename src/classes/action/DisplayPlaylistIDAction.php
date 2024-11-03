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
                    return $renderer->render();
                } else {
                    return "<p>Playlist not found.</p>";
                }
            } catch (AuthnException $e) {
                return "<p>Accès refusé : vous n'êtes pas le propriétaire de cette playlist.</p>
                <nav>
                    <a href=\"?action=accueil\">Retour</a>
                </nav>";
            }
        } else {
            return <<<HTML
            <form method="GET" action="Main.php">
                <link rel="stylesheet" href="src/css.css">
                <input type="hidden" name="action" value="displayplaylist">
                <label for="playlistId">Entrer un numéro de playlist (ID):</label>
                <input type="text" id="playlistId" name="id" required>
                <button type="submit">Afficher</button>
                <nav>
                    <a href="?action=accueil">Retour</a>
                </nav>
            </form>
            HTML;
        }
    }
}