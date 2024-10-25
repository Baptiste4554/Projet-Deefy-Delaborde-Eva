<?php
declare (strict_types=1);

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack {
    protected string $artiste;
    protected string $album;
    protected int $annee;
    protected int $numPiste;

    public function __construct(string $titre, string $nom, string $artiste = '', string $album = '', int $annee = 0, int $numPiste = 0, string $genre = '', int $duree = 0) {
        parent::__construct($titre, $nom, $genre, $duree);
        $this->artiste = $artiste;
        $this->album = $album;
        $this->annee = $annee;
        $this->numPiste = $numPiste;
    }

    public function setArtiste(string $artiste): void {
        $this->artiste = $artiste;
    }

    public function setAlbum(string $album): void {
        $this->album = $album;
    }

    public function setAnnee(int $annee): void {
        $this->annee = $annee;
    }

    public function setNumPiste(int $numPiste): void {
        $this->numPiste = $numPiste;
    }
}