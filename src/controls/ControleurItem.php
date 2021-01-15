<?php


namespace mywishlist\controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\VueItem;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;
use mywishlist\models\Message;

/**
 * Class ControleurItem
 * @package mywishlist\controls
 */
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
     * Affichage de l'item en tant que participant
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherItemParticipant(Request $rq, Response $rs, $args) : Response {
        //isset($_Session['reservationOK']
        // if pour participant pas connecté $_SESSION PROFILE
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        if(isset($_SESSION['modificationOK'])){
            if(isset($_SESSION['profile'])){
                $infoUser = $_SESSION['profile'];
                $_SESSION = [];
                $_SESSION['profile'] = $infoUser;
            }else{
                $_SESSION = [];
            }
            $rs->getBody()->write( $vue->render(0));
            return $rs;
        }else{
            $rs->getBody()->write( $vue->render(1));
            return $rs;
        }
    }

    /**
     * GET
     * Affichage de l'item en tant que créateur de celle-ci
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherItemCreateur(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        if(isset($_SESSION['modificationOK'])){
            $infoUser = $_SESSION['profile'];
            $_SESSION = [];
            $_SESSION['profile'] = $infoUser;
            $rs->getBody()->write( $vue->render(2));
            return $rs;
        }else{
            $rs->getBody()->write( $vue->render(3));
            return $rs;
        }
    }

    /**
     * GET
     * Affichage du formulaire de modification
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
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
     * Enregistrement des modification de l'item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modifierUnItem(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $item = Item::find( $args['id_item']) ;

        $item->nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $item->descr = filter_var($post['description'], FILTER_SANITIZE_STRING);
        $item->tarif = filter_var($post['prix'], FILTER_SANITIZE_NUMBER_FLOAT);
        $item->url = filter_var($post['url'], FILTER_SANITIZE_STRING);
        $item->save();

        $_SESSION['modificationOK'] = true;
        $url_modif = $this->container->router->pathFor("aff_item_admin", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_modif);
    }

    /**
     * GET
     * Affichage du formulaire de réservation d'item
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
        $_SESSION['modificationOK'] = true;
        $rs->getBody()->write( $vue->render(4));
        return $rs;
    }

    /**
     * POST
     * Enregistrement de la réservation d'un item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function reserverUnItem(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $nomReservant = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $message = filter_var($post['message'], FILTER_SANITIZE_STRING);

        $item = Item::find( $args['id_item']) ;
        $liste = Liste::where('token','=',$args['token'])->first();

        $m = new Message();
        $m->id_parent = $args['token'];
        $m->type_parent = 'item';
        $m->message = $message;
        $m->auteur = $nomReservant;
        $m->save();

        $item->reserve = $nomReservant;
        $item->save();

        $url_reservation = $this->container->router->pathFor("aff_item", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_reservation);
    }
}