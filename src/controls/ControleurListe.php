<?php


namespace mywishlist\controls;


use mywishlist\vue\VueAccueil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControleurListe
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function afficherListes(Request $rq, Response $rs, $args) : Response {
        // pour afficher la liste des listes de souhaits
        $listl = Liste::all() ;
        $vue = new VueAccueil( $listl->toArray() , $this->container ) ;
        $rs->getBody()->write( $vue->render( 1 ) ) ;
        return $rs;
    }

    public function afficherListe(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::find( $args['no'] ) ;
        $vue = new VueAccueil( [ $liste->toArray() ] , $this->container ) ;
        $rs->getBody()->write( $vue->render(2));
        return $rs;
    }

    public function afficherItem(Request $rq, Response $rs, $args) : Response {
        $item = Item::find( $args['id'] ) ;
        $vue = new VueAccueil( [ $item->toArray() ] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 3 ) ) ;
        return $rs;
    }

    public function reserverItem(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        //TODO
        return $rs;
    }

    public function afficherItemsListe(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::find($args['no']);
        $item = Item::where('liste_id','=',$liste->no)->get();
        $vue = new VueAccueil( [ $item->toArray() ] , $this->container );
        $rs->getBody()->write( $vue->render(11));
        return $rs;
    }

    public function formListe(Request $rq, Response $rs, $args) : Response {
        // pour afficher le formulaire liste
        $vue = new VueAccueil( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 5 ) ) ;
        return $rs;
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $vue = new VueAccueil( [], $this->container);
        $rs->getBody()->write($vue->render((8)));
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
        $url_listes = $this->container->router->pathFor( 'aff_listes' ) ;
        return $rs->withRedirect($url_listes);
    }
}