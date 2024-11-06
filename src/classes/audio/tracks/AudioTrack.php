<?php

namespace iutnc\deefy\audio\tracks;

abstract class AudioTrack {
    protected string $titre;
    protected string $nom;
    protected string $genre;
    protected int $duree;

    public function __construct(string $titre, string $nom, string $genre = '', int $duree = 0) {
        $this->titre = $titre;
        $this->nom = $nom;
        $this->genre = $genre;
        $this->duree = $duree;
    }
    public function __toString() : string {
        return json_encode($this);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get($attrName) {
        if (property_exists($this, $attrName)) {
            return $this->$attrName;
        }
        throw new \iutnc\deefy\exception\InvalidPropertyNameException("Propriété inexistante : $attrName");
    }
}