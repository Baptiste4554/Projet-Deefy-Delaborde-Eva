<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class AccueilAction extends Action {

    public function execute(): string {
        return <<<HTML
        <form method="POST" action="Main.php?action=accueil">
            <link rel="stylesheet" href="src/css.css">
            <nav>
                <ul>
                    <li><a href="?action=deconnection">Déconnexion</a></li>
                    <li><a href="?action=playlist">Afficher toutes les playlists</a></li>
                    <li><a href="?action=add-playlist">Ajouter une playlist</a></li>
                    <li><a href="?action=add-track">Ajouter une piste</a></li>
                </ul>
            </nav>
        </form>
        HTML;
    }
}
