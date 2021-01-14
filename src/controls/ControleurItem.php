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
        //isset($_Session['reservationOK']
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        $rs->getBody()->write( $vue->render(1));
        return $rs;
        //3
    }

    public function afficherItemCreateur(Request $rq, Response $rs, $args) : Response {
        //isset($_SESSION['modificatio']
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
    public function modifierUnItem(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $nomItem = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $descriptionItem = filter_var($post['description'], FILTER_SANITIZE_STRING);
        $prixItem = filter_var($post['prix'], FILTER_SANITIZE_NUMBER_FLOAT);

        $item = Item::find( $args['id_item']) ;

        $item->nom = $nomItem;
        $item->descr = $descriptionItem;
        $item->tarif = $prixItem;
        $item->save();

        $url_reservation = $this->container->router->pathFor("aff_item", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_reservation);
    }

    /**
     * GET
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

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function reserverUnItem(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $nomReservant = filter_var($post['nom'], FILTER_SANITIZE_STRING);

        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();

        $item->reserve = $nomReservant;
        $item->save();

        $url_reservation = $this->container->router->pathFor("aff_item", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_reservation);
    }
}