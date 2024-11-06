<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class DeconnectionAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {

            $html = <<<HTML
            <form action="?action=deconnection" method="post">
              <link rel="stylesheet" href="src/css.css">
              <div>
                <p>Êtes-vous sur de vouloir vous déconnecter ?</p>
              </div>
              <div>
                <input type="submit" value="Valider" />
              </div>
              <nav>
                <a href="?action=accueil">Retour</a>
              </nav>
            </form>
            HTML;
        } else {
          session_destroy();
        $html = <<<HTML
        <p>Déconnexion réussie.</p>
        <nav>
          <a href="?action=a">Retour à Menu</a>
        </nav>
        HTML;
        }
        return $html;
    }
}
