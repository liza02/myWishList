<?php


namespace mywishlist\controls;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\VueListe;
use mywishlist\vue\VueItem;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;
use mywishlist\models\Message;

/**
 * Class ControleurItem
 * @package mywishlist\controls
 */
class ControleurItem {
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
        $message = Message::where('id_parent', '=', $item['id'])->where('type_parent', '=', 'item')->first();
        $itemEtListe = array([$item],[$liste],[$message]);

        if (isset($_SESSION['profile'])){
            if ($_SESSION['profile']['userid'] == $liste->user_id){
                if(isset($_SESSION['modificationOK'])){
                    $infoUser = $_SESSION['profile'];
                    $_SESSION = [];
                    $_SESSION['profile'] = $infoUser;
                    $vue = new VueItem($itemEtListe, $this->container);
                    $rs->getBody()->write( $vue->render(2));
                    return $rs;
                }else{
                    if (isset($_SESSION['cagnotteOK'])) {
                        $infoUser = $_SESSION['profile'];
                        $_SESSION = [];
                        $_SESSION['profile'] = $infoUser;
                        $vue = new VueItem($itemEtListe, $this->container);
                        $rs->getBody()->write( $vue->render(6));
                        return $rs;
                    }
                    else {
                        $vue = new VueItem($itemEtListe, $this->container);
                        $rs->getBody()->write( $vue->render(3));
                        return $rs;
                    }
                }
            }else{
                $url_erreurItem = $this->container->router->pathFor("erreurItem");
                return $rs->withRedirect($url_erreurItem);
            }
        }else{
            $url_erreurItem = $this->container->router->pathFor("erreurItem");
            return $rs->withRedirect($url_erreurItem);
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
        $liste = Liste::where('token','=',$args['token'])->first();
        if (isset($_SESSION['profile'])){
            if ($_SESSION['profile']['userid'] == $liste->user_id){
                $post = $rq->getParsedBody();
                $item = Item::find( $args['id_item']) ;

                $item->nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
                $item->descr = filter_var($post['description'], FILTER_SANITIZE_STRING);
                $item->tarif = filter_var($post['tarif'], FILTER_SANITIZE_STRING);
                $item->url = filter_var($post['url'], FILTER_SANITIZE_STRING);
                $urlIMG = filter_var($post['url_image'],FILTER_SANITIZE_STRING);
                if ( $urlIMG != "" ){
                    $item->img = $urlIMG;
                }else{
                    $file = $_FILES['image2'];
                    $fileName = $_FILES['image2']['name'];
                    $fileTmpName = $_FILES['image2']['tmp_name'];
                    $fileSize = $_FILES['image2']['size'];
                    $fileError = $_FILES['image2']['error'];

                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));

                    $allowed = array('jpg','jpeg','png');

                    if (in_array($fileActualExt, $allowed)){
                        if ($fileError === 0){
                            if ($fileSize < 1000000){
                                $fileNameNew = uniqid('',true).".".$fileActualExt;
                                $fileDestination = 'img/'.$fileNameNew;
                                move_uploaded_file($fileTmpName,$fileDestination);
                            }
                        }
                    }
                    $item->img = $fileNameNew;
                }
                $item->save();

                $_SESSION['modificationOK'] = true;
                $url_modif = $this->container->router->pathFor("aff_item_admin", ['token' => $args['token'], 'id_item' => $args['id_item']]);
                return $rs->withRedirect($url_modif);
            }else{
                $url_erreurItem = $this->container->router->pathFor("erreurItem");
                return $rs->withRedirect($url_erreurItem);
            }
        }else{
            $url_erreurItem = $this->container->router->pathFor("erreurItem");
            return $rs->withRedirect($url_erreurItem);
        }


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
        $m->id_parent = $args['id_item'];
        $m->type_parent = 'item';
        $m->message = $message;
        $m->auteur = $nomReservant;
        $m->save();

        $item->reserve = $nomReservant;
        $item->save();
        $_SESSION['modificationOK'] = true;
        $url_reservation = $this->container->router->pathFor("aff_item", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_reservation);
    }

    /**
     * POST
     * Suppression Item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function supprimerItem(Request $rq, Response $rs, $args) : Response {
        $item = Item::find($args['id_item']);
        $item->delete();
        $_SESSION['suppressionOK'] = true;
        $url_affListe = $this->container->router->pathFor("aff_maliste", ['token' => $args['token']]);
        return $rs->withRedirect($url_affListe);
    }

    /**
     * GET
     * Activer la cagnotte sur un item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function creerCagnotte(Request $rq, Response $rs, $args): Response
    {
        $item = Item::find($args['id_item']);
        $item->cagnotteActive = 'true';
        $item->save();
        $_SESSION['cagnotteOK'] = true;
        $url_affListe = $this->container->router->pathFor("aff_item_admin", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_affListe);
    }

    /**
     * GET
     * Affichage du formulaire de participation à la cagnotte
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function participerCagnotte(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id_item']);
        $liste = Liste::where('token','=',$args['token'])->first();
        $itemEtListe = array([$item],[$liste],[$args['token']]);
        $vue = new VueItem($itemEtListe, $this->container);
        $rs->getBody()->write( $vue->render(7));
        return $rs;
    }

    /**
     * POST
     * Enregistrement de la participation à la cagnotte
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function formCagnotte(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $contribution = filter_var($post['valeur'], FILTER_SANITIZE_STRING);

        $item = Item::find( $args['id_item']) ;

        if ($item->cagnotte + $contribution <= $item->tarif) {
            $item->cagnotte = $item->cagnotte + $contribution;
        }
        else {
            $item->cagnotte = $item->tarif;
        }
        $item->save();


        $url_reservation = $this->container->router->pathFor("aff_item", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_reservation);
    }

    /**
     * POST
     * Suppression Item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function supprimerImage(Request $rq, Response $rs, $args) : Response {
        $item = Item::find($args['id_item']);
        $item->img = NULL;
        $item->save();
        $_SESSION['modificationOK'] = true;
        $url_modif = $this->container->router->pathFor("aff_item_admin", ['token' => $args['token'], 'id_item' => $args['id_item']]);
        return $rs->withRedirect($url_modif);
    }

    /**
     * GET
     * Affichage erreur pour item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function erreurItem(Request $rq, Response $rs, $args) : Response {
        $vue = new VueItem([], $this->container);
        $rs->getBody()->write( $vue->render(8));
        return $rs;
    }

}