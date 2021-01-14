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

class ControleurListe
{
    private $container;

    /**
     * ControleurListe constructor.
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
    public function afficherMesListes(Request $rq, Response $rs, $args) : Response{
        $nb = Liste::where('user_id','=',$_SESSION['profile']['userid'])->count();
        if ($nb != 0){
            if (isset($_SESSION['modificationOK'])){
                $info = $_SESSION['profile'];
                $_SESSION = [];
                $_SESSION['profile'] = $info;
                $mesListes=Liste::where("user_id","=",$_SESSION['profile']['userid'])->get();
                $vue = new VueListe($mesListes->toArray(), $this->container);
                $rs->getBody()->write( $vue->render(0));
            }else{
                $mesListes=Liste::where("user_id","=",$_SESSION['profile']['userid'])->get();
                $vue = new VueListe($mesListes->toArray(), $this->container);
                $rs->getBody()->write( $vue->render(1));
            }
        }else{
            $vue = new VueListe([], $this->container);
            $rs->getBody()->write( $vue->render(2));
        }
        return $rs;
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherUneListe(Request $rq, Response $rs, $args) : Response{
        if (isset($_SESSION['modificationOK'])){
            $infoUser = $_SESSION['profile'];
            $_SESSION = [];
            $_SESSION['profile'] = $infoUser;
            $liste = Liste::where('token','=',$args['token'])->get();
            $item = Item::where('liste_id','=',$liste[0]['no'])->get();
            $listeItem = array([$liste],[$item]);
            $vue = new VueListe($listeItem, $this->container);
            $rs->getBody()->write( $vue->render(5));
            return $rs;
        }else{
            $liste = Liste::where('token','=',$args['token'])->get();
            $item = Item::where('liste_id','=',$liste[0]['no'])->get();
            $listeItem = array([$liste],[$item]);
            $vue = new VueListe($listeItem, $this->container);
            $rs->getBody()->write( $vue->render(4));
            return $rs;
        }
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function creerListe(Request $rq, Response $rs, $args) : Response {
        // pour afficher le formulaire liste
        $vue = new VueListe( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render(3) ) ;
        return $rs;
    }


    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     * @throws \Exception
     */
    public function enregistrerListe(Request $rq, Response $rs, $args) : Response {
        // pour enregistrer 1 liste.....
        $post = $rq->getParsedBody() ;
        $titre       = filter_var($post['titre']       , FILTER_SANITIZE_STRING) ;
        $description = filter_var($post['description'] , FILTER_SANITIZE_STRING) ;
        $date = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $public = filter_var($post['public'], FILTER_SANITIZE_STRING);
        $l = new Liste();
        $l->titre = $titre;
        $l->description = $description;
        $token = bin2hex(random_bytes(10));
        $l->token = $token;
        $l->expiration = $date;
        $l->public = $public;
        $l->user_id =  $_SESSION['profile']['userid'];
        $l->save();
        //redirection sur afficher
        $url_listes = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
        return $rs->withRedirect($url_listes);
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modifierListe(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::where('token','=',$args['token'])->first();
        $vue = new VueListe($liste->toArray(), $this->container);
        $rs->getBody()->write( $vue->render(6));
        return $rs;
    }

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function enregistrerModificationListe(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $titre       = filter_var($post['titre']       , FILTER_SANITIZE_STRING) ;
        $description = filter_var($post['description'] , FILTER_SANITIZE_STRING) ;
        $date = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $public = filter_var($post['public'], FILTER_SANITIZE_STRING);
        $liste->titre = $titre;
        $liste->description = $description;
        $liste->expiration = $date;
        $liste->public = $public;
        $liste->save();
        $_SESSION['modificationOK'] = true;
        $url_MesListes = $this->container->router->pathFor('afficherMesListes') ;
        return $rs->withRedirect($url_MesListes);
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajoutItem(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::where('token','=',$args['token'])->first();
        $vue = new VueListe($liste->toArray(), $this->container);
        $rs->getBody()->write( $vue->render(7));
        return $rs;
    }

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function enregistrerNouveauItemListe(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $nom       = filter_var($post['nom']       , FILTER_SANITIZE_STRING) ;
        $descr = filter_var($post['descr'] , FILTER_SANITIZE_STRING) ;
        $tarif = filter_var($post['tarif'], FILTER_SANITIZE_STRING);
        $liste = Liste::where('token','=',$args['token'])->first();
        $item = new Item();
        $item->liste_id = $liste['no'];
        $item->nom = $nom;
        $item->descr = $descr;
        $item->tarif = $tarif;
        $item->save();
        $_SESSION['creationItemOK'] = true;
        $url_listes = $this->container->router->pathFor("aff_maliste", ['token' => $args['token']]);
        return $rs->withRedirect($url_listes);
    }

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function supprimerListe (Request $rq, Response $rs, $args) : Response {
        $liste = Liste::where('token','=',$args['token'])->first();
        $liste->delete();
        $url_MesListes = $this->container->router->pathFor('afficherMesListes') ;
        return $rs->withRedirect($url_MesListes);
    }
}