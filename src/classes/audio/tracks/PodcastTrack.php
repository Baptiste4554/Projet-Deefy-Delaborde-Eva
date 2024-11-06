<?php

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack {
    protected string $auteur;
    protected string $date;

    public function __construct(string $titre, string $nom, string $auteur = '', string $date = '', string $genre = '', int $duree = 0) {
        parent::__construct($titre, $nom, $genre, $duree);
        $this->auteur = $auteur;
        $this->date = $date;
    }

    public function setAuteur(string $auteur): void {
        $this->auteur = $auteur;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }
}