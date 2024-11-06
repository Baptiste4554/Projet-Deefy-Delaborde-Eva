<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

class AlbumList extends AudioList {

    protected string $artiste;
    protected string $date;
    public function __construct(string $nom, array $liste) {
        parent::__construct($nom, $liste);
    }

    public function setArtiste(string $artiste): void {
        $this->artiste = $artiste;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }
}