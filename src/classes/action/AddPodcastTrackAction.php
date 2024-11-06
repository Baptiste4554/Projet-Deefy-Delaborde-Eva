<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;

class AddPodcastTrackAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            return $this->renderForm();
        } else {
            return $this->verifAjoutTrack();
        }
    }

    private function renderForm(): string {
        return <<<HTML
        <form action="?action=add-track" method="post" enctype="multipart/form-data">
          <link rel="stylesheet" href="src/css.css">
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
        HTML;
    }

    private function verifAjoutTrack(): string {
        if (!isset($_SESSION["Playlist"])) {
            return $html= <<<HTML
            <div><p>Il n'y a pas de playlist enregistrée</p></div><nav><a href="?action=accueil">Retour</a></nav>
            HTML;
        }

        $titre= filter_var($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);
        $chemin= filter_var($_POST["chemin"], FILTER_SANITIZE_SPECIAL_CHARS);
        $auteur= filter_var($_POST["auteur"], FILTER_SANITIZE_SPECIAL_CHARS);
        $date= filter_var($_POST["date"], FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($_POST["genre"], FILTER_SANITIZE_SPECIAL_CHARS);
        $duree= filter_var($_POST["duree"], FILTER_VALIDATE_INT);

        $upload_dir = __DIR__ . "/audio/";
        $file_name = uniqid();
        $tmp = $_FILES["fichier"]["tmp_name"];

        if ($this->verifMp3($_FILES["fichier"])) {
            $dest = $upload_dir . $file_name . ".mp3";
            if (move_uploaded_file($tmp, $dest)) {
                $html = "<div>téléchargement ok</div>";
            } else {
                $html = "<div>téléchargement erreur</div>";
            }
        } else {
            $html = "<div>téléchargement erreur: mp3 requis</div>";
        }

        $repo = DeefyRepository::getInstance();
        $trackID= $repo->saveTrack($titre, $genre, $duree, $file_name . ".mp3", $auteur, $date, 'P');

        $playlist = $_SESSION["Playlist"];
        $playlist->ajouterPiste(new PodcastTrack($titre, $chemin, $auteur, $date, $genre, $duree));
        $_SESSION["Playlist"] = $playlist;
        $renderer = new AudioListRenderer($playlist);
        $playlistId= $_SESSION['playlistId'];
        $repo->addTrackToPlaylist($playlistId, $trackID);

        $html .= $renderer->render();
        return $html .= "<nav><a href=\"?action=accueil\">Retour</a></nav>";
    }

    private function verifMp3(array $file): bool {
        return ($file["error"] === UPLOAD_ERR_OK)
            && ($file["type"] === "audio/mpeg")
            && (str_ends_with($file["name"], '.mp3'));
    }
}