<?php
declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;

class AudioListRenderer implements Renderer {

    protected AudioList $audioList;

    public function __construct(AudioList $audioList) {
        $this->audioList = $audioList;
    }
    public function render(int $selector = -1): string {
        $output = "<h2>{$this->audioList->nom}</h2>";
        $output .= "<ul>";
        foreach ($this->audioList->liste as $track) {
            $output .= "<li>";
            $output .= "$track->titre - $track->nom";
            $output .= "</li>";
        }
        $output .= "</ul>";
        $output .= "<p>Nombre de pistes : {$this->audioList->nbPiste}</p>";
        $output .= "<p>Durée totale : {$this->audioList->duree} secondes</p>";
        return $output;
    }
}