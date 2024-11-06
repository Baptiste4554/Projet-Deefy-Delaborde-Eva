<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

class PlayList extends AudioList {

    public function ajouterPiste(AudioTrack $piste): void {
        $this->liste[] = $piste;
        $this->nbPiste++;
        $this->duree += $piste->duree;
    }

    public function supprimerPiste(int $indice): void {
        if ($indice >= 0 && $indice < $this->nbPiste) {
            $this->duree -= $this->liste[$indice]->duree;
            unset($this->liste[$indice]);
            $this->nbPiste--;
        }
    }

    public function ajoutListePiste(array $liste): void {
        foreach($liste as $piste) {
            if (!$this->pisteExiste($piste)) {
                $this->ajouterPiste($piste);
            }
        }
    }
    private function pisteExiste(AudioTrack $piste): bool {
        foreach ($this->liste as $existingPiste) {
            if ($existingPiste->titre === $piste->titre && $existingPiste->nom === $piste->nom) {
                return true;
            }
        }
        return false;
    }
}