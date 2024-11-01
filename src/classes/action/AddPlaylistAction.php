<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;

class AddPlaylistAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {

            $html = <<<HTML
            <form action="?action=add-playlist" method="post">
              <div>
                <label for="name">Entrez nom playlist: </label>
                <input type="text" name="nom" id="name" required />
              </div>
              <div>
                <input type="submit" value="Valider" />
              </div>
            </form>
            HTML;
        } else {
            $nom = filter_var($_POST["nom"], FILTER_SANITIZE_SPECIAL_CHARS);

            $repo = DeefyRepository::getInstance();
            $playlistId = $repo->saveEmptyPlaylist($nom);

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                $repo->addUserToPlaylist($userId, $playlistId);
            }

            $_SESSION['playlistId'] = $playlistId;
            $playlist = new Playlist($nom, []);
            $_SESSION['Playlist'] = $playlist;

            $renderer = new AudioListRenderer($playlist);
            $html = $renderer->render();
        }
        return $html;
    }
}
