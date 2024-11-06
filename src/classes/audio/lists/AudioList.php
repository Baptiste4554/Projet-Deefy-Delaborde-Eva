<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

abstract class AudioList {

    protected string $nom;
    protected int $nbPiste;
    protected int $duree;
    protected array $liste;

    public function __construct(string $nom, array $liste = [ ]) {
        $this->nom = $nom;
        $this->liste = $liste;
        $this->nbPiste = count($liste);
        $this->duree = 0;
        if ($this->nbPiste > 0) {
            foreach($liste as $track) {
                $this->duree += $track->duree;
            }
        }

    }
    /**
     * @throws InvalidPropertyNameException
     */
    public function __get($attrName) {
        if (property_exists($this, $attrName)) {
            return $this->$attrName;
        }
        throw new InvalidPropertyNameException("Propriété inexistante : $attrName");
    }
}