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

    public function __construct($container) {
        $this->container = $container;
    }

    public function afficherMesListes(Request $rq, Response $rs, $args) : Response{
        $nb = Liste::where('user_id','=',$_SESSION['profile']['userid'])->count();
        if ($nb != 0){
            $mesListes=Liste::where("user_id","=",$_SESSION['profile']['userid'])->get();

            $vue = new VueListe($mesListes->toArray(), $this->container);
            $rs->getBody()->write( $vue->render(0));
        }else{
            $vue = new VueListe([], $this->container);
            $rs->getBody()->write( $vue->render(1));
        }

        return $rs;
    }

    public function afficherUneListe(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::where('token','=',$args['token'])->get();
        $item = Item::where('liste_id','=',$liste[0]['no'])->get();
        $listeItem = array([$liste],[$item]);
        //var_dump($listeItem[1]);
        $vue = new VueListe($listeItem, $this->container);
        $rs->getBody()->write( $vue->render(3));
        return $rs;
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
        $rs->getBody()->write( $vue->render( 2) ) ;
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
        $url_listes = $this->container->router->pathFor("aff_liste", ['token' => $token]);
        return $rs->withRedirect($url_listes);
    }

    public function supprimerListeConfirmation (Request $rq, Response $rs, $args) : Response {
        //TODO
    }

    public function supprimerListe (Request $rq, Response $rs, $args) : Response {
        //TODO
    }
}