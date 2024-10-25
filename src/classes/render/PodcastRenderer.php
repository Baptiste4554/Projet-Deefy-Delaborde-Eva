<?php
declare (strict_types=1);

namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\PodcastTrack;

class PodcastRenderer extends AudioTrackRenderer {

    public function __construct(PodcastTrack $podcastTrack) {
        parent::__construct($podcastTrack);
    }

    protected function renderAsCompact() : string {
        return "<audio controls><source src=\"{$this->audioTrack->nom}\" type=\"audio/mpeg\"></audio>";
    }

    protected function renderAsLong() : string {
        $podcastTrack = $this->audioTrack;
        return "<audio controls><source src=\"{$podcastTrack->nom}\" type=\"audio/mpeg\"></audio><br>{$podcastTrack->titre} - {$podcastTrack->auteur} - {$podcastTrack->date}";
    }
}