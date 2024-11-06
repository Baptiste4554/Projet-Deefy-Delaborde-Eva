<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        $repository = DeefyRepository::getInstance();
        $playlists = $repository->getAllPlaylists();
        $_SESSION['playlists'] = $playlists;


        $html = "<link rel=\"stylesheet\" href=\"src/css.css\"> <h2>Liste des playlists :</h2><ul>";
        foreach ($playlists as $playlist) {
            $html .= "<li>" . htmlspecialchars($playlist['nom']) . "</li>". "<a href=\"?action=displayplaylist&id=".htmlspecialchars($playlist['id'])."\">Afficher</a>";
        }

        $html .= "</ul>";
        return $html .= "<nav><a href=\"?action=accueil\">Retour</a></nav>";
    }
}
