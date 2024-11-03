<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;

class AddPodcastTrackAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            $html = <<<FIN
            <form action="?action=add-track" method="post" enctype="multipart/form-data">
              <div>
                <label for="titre">Entrez titre: </label>
                <input type="text" name="titre" id="titre" required />
                
                <label for="chemin">Entrez chemin: </label>
                <input type="text" name="chemin" id="chemin" required />
                
                <label for="auteur">Entrez auteur: </label>
                <input type="text" name="auteur" id="auteur" />
                
                <label for="date">Entrez date: </label>
                <input type="date" name="date" id="date" />
                
                <label for="genre">Entrez genre: </label>
                <input type="text" name="genre" id="genre" />
                
                <label for="duree">Entrez duree (en seconde): </label>
                <input type="text" name="duree" id="duree" />
                
                <label for="fichier">Ajouter fichier: </label>
                <input type="file" name="fichier" id="fichier" />
              </div>
              <div>
                <input type="submit" value="Valider" />
              </div>
            </form>
            FIN;
        } else {
            $titre = filter_var($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $chemin = filter_var($_POST["chemin"], FILTER_SANITIZE_SPECIAL_CHARS);
            $auteur = filter_var($_POST["auteur"], FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_var($_POST["date"], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST["genre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST["duree"], FILTER_VALIDATE_INT);

            $upload_dir = __DIR__ . "/audio/";
            $file_name = uniqid();
            $tmp = $_FILES["fichier"]["tmp_name"];

            if (($_FILES["fichier"]["error"] === UPLOAD_ERR_OK)
                && ($_FILES["fichier"]["type"] === "audio/mpeg")
                && (str_ends_with($_FILES["fichier"]["name"], '.mp3'))) {
                $dest = $upload_dir . $file_name . ".mp3";
                if (move_uploaded_file($tmp, $dest)) {
                    $html = "<div>Upload ok</div>";
                } else {
                    $html = "<div>Upload fail</div>";
                }
            } else {
                $html = "<div>Upload fail: mp3 requis</div>";
            }
            $repo = DeefyRepository::getInstance();
            $trackID = $repo->saveTrack($titre, $genre, $duree, $file_name . ".mp3", $auteur, $date, 'P');
            

            if (isset($_SESSION["Playlist"])) {
                $playlist = $_SESSION["Playlist"];
                $playlist->ajouterPiste(new PodcastTrack($titre, $chemin, $auteur, $date, $genre, $duree));
                unset($_SESSION["Playlist"]);
                $_SESSION["Playlist"] = $playlist;
                $renderer = new AudioListRenderer($_SESSION['Playlist']);
                $playlistId = $_SESSION['playlistId']; 
                $repo->addTrackToPlaylist($playlistId, $trackID);
                $html .= $renderer->render(); 
            } else {
                $html .= <<<FIN
                <div>
                    <p>Il n'y a pas de playlist enregistrée</p>
                </div>
                FIN;
            }
        }
        return $html .= "<nav><a href=\"?action=accueil\">Retour</a></nav>";
    }
}
