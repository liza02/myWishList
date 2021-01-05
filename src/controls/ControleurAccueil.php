<?php


namespace mywishlist\controls;


use mywishlist\vue\VueAccueil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControleurAccueil
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function accueil(Request $rq, Response $rs, $args) : Response {
        $vue = new VueAccueil( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 0 ) ) ;
        return $rs;
    }

    public function afficherListes(Request $rq, Response $rs, $args) : Response {
        // pour afficher la liste des listes de souhaits
        $listl = Liste::all() ;
        $vue = new VueAccueil( $listl->toArray() , $this->container ) ;
        $rs->getBody()->write( $vue->render( 1 ) ) ;
        return $rs;
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $vue = new VueAccueil( [], $this->container);
        $rs->getBody()->write($vue->render((8)));
        return $rs;

    }
}