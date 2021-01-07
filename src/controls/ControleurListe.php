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

/*
 * A utiliser en mettant un selecteur
 */

    public function afficherItemsListe(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::where('token','=',$args['token'])->get();
        $vue = new VueListe( [ $liste[0]->toArray() ] , $this->container );
        $rs->getBody()->write( $vue->render(2));
        return $rs;
    }

    public function formListe(Request $rq, Response $rs, $args) : Response {
        // pour afficher le formulaire liste
        $vue = new VueListe( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 0) ) ;
        return $rs;
    }


    public function newListe(Request $rq, Response $rs, $args) : Response {
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
        if ($public) {
            $l->public = "true";
        }
        else {
            $l->public = "false";
        }
        //TODO public
        $l->save();
        //redirection sur afficher
        $url_listes = $this->container->router->pathFor("aff_liste", ['token' => $token]);
        return $rs->withRedirect($url_listes);
    }
}