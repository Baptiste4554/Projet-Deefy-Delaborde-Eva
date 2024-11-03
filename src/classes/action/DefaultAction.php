<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class DefaultAction extends Action {

    public function execute(): string {
        return <<<HTML
        <link rel="stylesheet" href="src/css.css">
        <nav>
                <ul>
                    <li><a href="?action=add-user">S'inscrire</a></li>
                    <li><a href="?action=signin">Se connecter</a></li>
                </ul>
        </nav>
        <h2>Bienvenue sur Deefy</h2>
        <p>Vous etes sur la page d'accueil par défaut. Une fois inscrit et connecter, le système de dispatching vous permet de naviguer vers différentes fonctionnalités.</p>
        <p>Voici les actions disponibles que vous pourrez utiliser :</p>
            <ul>
                <li>Afficher toutes les playlists de l'utilisateur</li>
                <li>Afficher les pistes d'une playlist</li>
                <li>Ajouter une plylist vide</li>
                <li>Ajouter une piste a la plylist créer</li>
            </ul>
        </body>
        </html>
        HTML;
    }
}