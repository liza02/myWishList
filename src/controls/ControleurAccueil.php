<?php


namespace mywishlist\controls;

use mywishlist\vue\VueListe;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\MaVue;
use mywishlist\vue\VueAccueil;
use mywishlist\vue\VueCompte;
use mywishlist\vue\VueItem;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;

class ControleurAccueil
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
//        session_destroy();
//        $_SESSION = [];
    }

    public function accueil(Request $rq, Response $rs, $args) : Response {
        $ensListes = Liste::where('public','=','true')->get();
        $vue = new VueAccueil( $ensListes->toArray() , $this->container ) ;
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }

    public function item(Request $rq, Response $rs, $args) : Response {
        $vue = new VueItem([], $this->container);
        $rs->getBody()->write( $vue->render(0));
        return $rs;
    }
    public function list(Request $rq, Response $rs, $args) : Response {
        $listl = Liste::all();
        $vue = new VueListe($listl->toArray(), $this->container);
        $rs->getBody()->write( $vue->render(0));
        return $rs;
    }
}