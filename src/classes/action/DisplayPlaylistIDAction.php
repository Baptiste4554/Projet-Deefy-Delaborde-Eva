<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistIDAction {
    public function execute(): string {
        $playlistId = (int)($_GET['id'] ?? 0);
        if ($playlistId > 0) {
            $repository = DeefyRepository::getInstance();
            $playlist = $repository->findPlaylistById($playlistId);
            if ($playlist) {
                $renderer = new AudioListRenderer($playlist);
                return $renderer->render();
            } else {
                return "<p>Playlist not found.</p>";
            }
        } else {
            return <<<HTML
            <form method="GET" action="Main.php">
                <input type="hidden" name="action" value="displayplaylist">
                <label for="playlistId">Entrer un numéro de playlist (ID):</label>
                <input type="text" id="playlistId" name="id" required>
                <button type="submit">Display Playlist</button>
            </form>
            HTML;
        }
    }
}