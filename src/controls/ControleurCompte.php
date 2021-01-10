<?php


namespace mywishlist\controls;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\MaVue;
use mywishlist\vue\VueAccueil;
use mywishlist\vue\VueCompte;
use mywishlist\vue\VueItem;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;
use \mywishlist\models\User;

class ControleurCompte {
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function formlogin(Request $rq, Response $rs, $args) : Response {
        $vue = new VueCompte( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render(1) ) ;
        return $rs;
    }

    public function nouveaulogin(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $login = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($post['prenom'], FILTER_SANITIZE_STRING);
        try {
            Authentication::createUser($nom, $prenom,$login, $pass);
        }
        catch (\Exception $e) {
            $login = 'existe déjà';
        }

        $vue = new VueCompte( [ 'login' => $login ] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 2 ) ) ;
        return $rs;
    }

    public function testform(Request $rq, Response $rs, $args) : Response {
        $vue = new VueCompte( [] , $this->container ) ;
        if (!$_SERVER['HTTP_CONNECTION']) {
            $rs->getBody()->write($vue->render(5));
        } else {
            $rs->getBody()->write( $vue->render(3) ) ;
        }
        return $rs;
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $vue = new VueCompte( [], $this->container);
        $rs->getBody()->write($vue->render(3));
        return $rs;

    }

    public function testpass(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $login = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;
        $res = Authentication::authenticate($login, $pass);
        $vue = new VueCompte( [ 'res' => $res ] , $this->container ) ;
        $rs->getBody()->write( $vue->render(4) ) ;
        return $rs;
    }
}