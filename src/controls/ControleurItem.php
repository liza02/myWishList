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

    /**
     * ControleurItem constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherItemParticipant(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        $rs->getBody()->write( $vue->render(1));
        return $rs;
        //3
    }

    public function afficherItemCreateur(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        $rs->getBody()->write( $vue->render(3));
        return $rs;
    }

    public function modifierItem(Request $rq, Response $rs, $args) : Response{
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        $rs->getBody()->write( $vue->render(5));
        return $rs;
    }

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function reserverItem(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        $rs->getBody()->write( $vue->render(4));
        return $rs;
    }
}