<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class DefaultAction extends Action {

    public function execute(): string {
        return <<<HTML
        <h2>Bienvenue sur Deefy</h2>
        <p>Vous etes sur la page d'accueil par défaut. Le système de dispatching vous permet de naviguer vers différentes fonctionnalités en fonction de l'action spécifiée dans l'URL.</p>
        <p>Voici les actions disponibles que vous pouvez utiliser :</p>
        <ul>
            <li><strong>?action=playlist</strong> : Affiche la liste des playlists.</li>
            <li><strong>?action=add-playlist</strong> : Permet d'ajouter une nouvelle playlist.</li>
            <li><strong>?action=add-track</strong> : Permet d'ajouter une chanson ou un podcast.</li>
        </ul>
        </body>
        </html>
        HTML;
    }
}