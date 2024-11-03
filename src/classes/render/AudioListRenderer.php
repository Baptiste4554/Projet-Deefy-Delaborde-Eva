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
        $output = "<h2>{$this->audioList->nom}</h2>";
        $output .= "<ul>";
        foreach ($this->audioList->liste as $track) {
            $output .= "<li>{$track->titre}</li>";
        }
        $output .= "</ul>";
        return $output;
    }

    protected function renderAsLong(): string {
        $output = "<h2>{$this->audioList->nom}</h2>";
        $output .= "<ul>";
        foreach ($this->audioList->liste as $track) {
            $output .= "<li>{$track->titre} - {$track->nom}</li>";
        }
        $output .= "</ul>";
        $output .= "<p>Nombre de pistes : {$this->audioList->nbPiste}</p>";
        $output .= "<p>Durée totale : {$this->audioList->duree} secondes</p>";
        return $output;
    }
}