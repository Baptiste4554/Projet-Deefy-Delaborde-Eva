<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        $repository = DeefyRepository::getInstance();
        $playlists = $repository->getAllPlaylists();
        $_SESSION['playlists'] = $playlists;


        $html = "<h2>Liste des playlists :</h2><ul>";
        foreach ($playlists as $playlist) {
            $html .= "<li>" . htmlspecialchars($playlist['nom']) ."   -ID: ".htmlspecialchars($playlist['id']). "</li>";
        }

        $html .= "</ul>";
        return $html;
    }
}
