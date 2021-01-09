<?php


namespace mywishlist\controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\MaVue;
use mywishlist\vue\VueAccueil;
use mywishlist\vue\VueCompte;
use mywishlist\vue\VueItem;
use mywishlist\vue\VueListe;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;

class ControleurItem
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function afficherItem(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id_item'] ) ;
        $liste = Liste::where('token','=',$args['token'])->get();
        $aray = array([$item],[$liste],$args['token']);
        $vue = new VueItem( [ $aray ] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 0 ) ) ;
        return $rs;
    }

    public function reserverItem(Request $rq, Response $rs, $args) : Response {
        //TODO
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $vue = new MaVue( [], $this->container);
        $rs->getBody()->write($vue->render((8)));
        return $rs;

    }
}