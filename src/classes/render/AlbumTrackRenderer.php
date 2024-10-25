<?php
declare (strict_types=1);

namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\AudioTrack;

class AlbumTrackRenderer extends AudioTrackRenderer {

    public function __construct(AudioTrack $albumTrack) {
        parent::__construct($albumTrack);
    }

    protected function renderAsCompact() : string {
        return "<audio controls><source src=\"{$this->audioTrack->nom}\" type=\"audio/mpeg\"></audio>";
    }

    protected function renderAsLong() : string {
        $albumTrack = $this->audioTrack;
        return "<audio controls><source src=\"{$albumTrack->nom}\" type=\"audio/mpeg\"></audio><br>{$albumTrack->titre} - {$albumTrack->artiste}";
    }
}