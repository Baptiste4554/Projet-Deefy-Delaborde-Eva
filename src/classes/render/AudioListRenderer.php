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
        switch ($selector) {
            case Renderer::COMPACT:
                return $this->renderAsCompact();
            case Renderer::LONG:
                return $this->renderAsLong();
            default:
                return $this->renderAsCompact();
        }
    }

    protected function renderAsCompact(): string {
        $resultat= "<h2>{$this->audioList->nom}</h2>";
        $resultat.= "<ul>";
        foreach ($this->audioList->liste as $track) {
            $resultat.= "<li>{$track->titre}</li>";
        }
        $resultat.= "</ul>";
        return $resultat;
    }

    protected function renderAsLong(): string {
        $resultat= "<h2>{$this->audioList->nom}</h2>";
        $resultat.= "<ul>";
        foreach ($this->audioList->liste as $track) {
            $resultat.= "<li>{$track->titre} - {$track->nom}<br>";
            $resultat.= "<audio controls><source src=\"webS3/projet/Projet-Deefy-Delaborde-Eva/src/classes/action/audio/{$track->nom}\" type=\"audio/mpeg\"></audio></li>";
        }
        $resultat.= "</ul>";
        $resultat.= "<p>Nombre de pistes : {$this->audioList->nbPiste}</p>";
        $resultat.= "<p>Durée totale : {$this->audioList->duree} secondes</p>";
        return $resultat;
    }
}