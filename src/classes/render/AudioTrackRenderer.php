<?php
declare (strict_types=1);

namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\AudioTrack;


abstract class AudioTrackRenderer implements Renderer {

    public AudioTrack $audioTrack;

    public function __construct(AudioTrack $audioTrack) {
        $this->audioTrack = $audioTrack;
    }

    public function render(int $selector) : string {
        switch ($selector) {
            case Renderer::COMPACT:
                return $this->renderAsCompact();
            case Renderer::LONG:
                return $this->renderAsLong();
            default:
                return $this->renderAsCompact();
        }
    }

    abstract protected function renderAsCompact() : string;
    abstract protected function renderAsLong() : string;
}