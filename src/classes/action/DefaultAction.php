<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class DefaultAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            return "<div>Bienvenue !</div>";
        } else {
            return "<div>Action par défaut dans le POST</div>";
        }
    }
}