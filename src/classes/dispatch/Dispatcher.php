<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\DisplayPlaylistIDAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\AccueilAction;
use iutnc\deefy\action\DeconnectionAction;

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
            case 'displayplaylist':
                $actionObjet = new DisplayPlaylistIDAction();
                break;
            case 'playlist':
                $actionObjet = new DisplayPlaylistAction();
                break;
            case 'add-playlist':
                $actionObjet = new addPlaylistAction();
                break;
            case 'add-track':
                $actionObjet = new AddPodcastTrackAction();
                break;
            case 'accueil';
                $actionObjet = new AccueilAction();
                break;
            case 'signin';
                $actionObjet = new SigninAction();
                break;
            case 'add-user';
                $actionObjet = new AddUserAction();
                break;
            case 'deconnection';
                $actionObjet = new DeconnectionAction();
                break;
            default:
                $actionObjet = new DefaultAction();
                break;
        }   
        $html = $actionObjet->execute();
        $this->renderPage($html);
    }

    private function renderPage(String $html) : void {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='utf-8'>
            <title>deefy</title>
            <link rel="stylesheet" href="src/css.css">
        </head>
        <body>
            <h1>Deefy App</h1>
            $html
        </body>
        </html>
        HTML;
    }
}