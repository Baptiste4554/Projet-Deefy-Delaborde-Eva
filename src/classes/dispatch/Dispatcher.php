<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;

class Dispatcher {

    private string $action;

    public function __construct() {
        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        } else {
            $this->action = 'default';
        }
    }

    public function run(): void {
        switch ($this->action) {
            case 'playlist':
                $actionObjet = new DisplayPlaylistAction();
                break;
            case 'add-playlist':
                $actionObjet = new addPlaylistAction();
                break;
            case 'add-track':
                $actionObjet = new AddPodcastTrackAction();
                break;
            default:
                $actionObjet = new DefaultAction();
                break;
        }
        $html = $actionObjet->execute();
        $this->renderPage($html);
    }

    private function renderPage(String $html) : void {
        echo <<<FIN
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='utf-8'>
            <title>Exemple</title>
        </head>
        <body>
            <h1>Deefy App</h1>
            <nav>
                <ul>
                    <li><a href="./Main.php">Accueil</a></li>
                    <li><a href="?action=playlist">Affiche playlist</a></li>
                    <li><a href="?action=add-playlist">Ajoute playlist</a></li>
                    <li><a href="?action=add-track">Ajoute piste</a></li>
                </ul>
            </nav>
            $html
        </body>
        </html>
        FIN;
    }
}