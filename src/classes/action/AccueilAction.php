<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class AccueilAction extends Action {

    public function execute(): string {
        return <<<HTML
        <form method="POST" action="Main.php?action=accueil">
            <nav>
                <ul>
                    <li><a href="./Main.php">déconnection</a></li>
                    <li><a href="?action=playlist">Affiche toutes les playlists</a></li>
                    <li><a href="?action=displayplaylist">Affiche les pistes d'une playlist</a></li>
                    <li><a href="?action=add-playlist">Ajoute playlist</a></li>
                    <li><a href="?action=add-track">Ajoute piste</a></li>
                </ul>
            </nav>
        HTML;
    }
}