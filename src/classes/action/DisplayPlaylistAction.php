<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        if (!isset($_SESSION['playlists']) || empty($_SESSION['playlists'])) {
            return "Aucune playlist disponible.";
        }

        $html = "<h2>Liste des playlists :</h2><ul>";
        foreach ($_SESSION['playlists'] as $playlist) {
            $html .= "<li>" . htmlspecialchars($playlist->nom) . "</li>"; 
        }

        $html .= "</ul>";
        return $html;
    }
}
