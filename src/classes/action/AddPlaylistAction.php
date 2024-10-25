<?php
namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;

class AddPlaylistAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            $html = <<<FIN
            <form action="?action=add-playlist" method="post">
              <div>
                <label for="name">Entrez nom playlist: </label>
                <input type="text" name="nom" id="name" required />
              </div>
              <div>
                <input type="submit" value="Valider" />
              </div>
            </form>
            FIN;
        } else {
           $nom = filter_var($_POST["nom"], FILTER_SANITIZE_SPECIAL_CHARS);
           $_SESSION['Playlist'] = new Playlist($nom, array());
           $renderer = new AudioListRenderer($_SESSION['Playlist']);
           $html = $renderer->render();
        }
        return $html;
    }
}
