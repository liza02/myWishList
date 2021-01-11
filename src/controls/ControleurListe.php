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

    public function afficherGererMesListes(Request $rq, Response $rs, $args) : Response{
        $listl = Liste::all();
        $vue = new VueListe($listl->toArray(), $this->container);
        $rs->getBody()->write( $vue->render(0));
        return $rs;
    }

    public function afficherItemsListe(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::where('token','=',$args['token'])->get();
        $item = Item::where('liste_id','=',$liste[0]['no'])->get();
        $listeItem = array([$liste],[$item]);
        //var_dump($listeItem[1]);
        $vue = new VueListe($listeItem, $this->container);
        $rs->getBody()->write( $vue->render(1));
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
        $l->save();
        //redirection sur afficher
        $url_listes = $this->container->router->pathFor("aff_liste", ['token' => $token]);
        return $rs->withRedirect($url_listes);
    }
}