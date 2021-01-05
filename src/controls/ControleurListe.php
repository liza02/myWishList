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

class ControleurListe
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }


    public function afficherListe(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::find( $args['no'] ) ;
        $vue = new MaVue( [ $liste->toArray() ] , $this->container ) ;
        $rs->getBody()->write( $vue->render(2));
        return $rs;
    }


    public function afficherItemsListe(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::find($args['no']);
        $item = Item::where('liste_id','=',$liste->no)->get();
        $vue = new MaVue( [ $item->toArray() ] , $this->container );
        $rs->getBody()->write( $vue->render(11));
        return $rs;
    }

    public function formListe(Request $rq, Response $rs, $args) : Response {
        // pour afficher le formulaire liste
        $vue = new VueListe( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 1) ) ;
        return $rs;
    }


    public function newListe(Request $rq, Response $rs, $args) : Response {
        // pour enregistrer 1 liste.....
        $post = $rq->getParsedBody() ;
        $titre       = filter_var($post['titre']       , FILTER_SANITIZE_STRING) ;
        $description = filter_var($post['description'] , FILTER_SANITIZE_STRING) ;
        $date = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $l = new Liste();
        $l->titre = $titre;
        $l->description = $description;
        $l->token = bin2hex(random_bytes(10));
        $l->expiration = $date;
        //ajouter la condition s'il manque un titre ou une description
        $l->save();
        $url_listes = $this->container->router->pathFor( 'liste' ) ;
        return $rs->withRedirect($url_listes);
    }
}