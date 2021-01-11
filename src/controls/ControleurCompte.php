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

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function inscription(Request $rq, Response $rs, $args) : Response {
        $vue = new VueCompte( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render(3)) ;
        return $rs;
    }

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function enregistrerInscription(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $login = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($post['prenom'], FILTER_SANITIZE_STRING);
        $vue = new VueCompte( [ 'login' => $login ] , $this->container ) ;
        try {
            Authentication::createUser($nom, $prenom,$login, $pass);
            $rs->getBody()->write( $vue->render(4)) ;
        }
        catch (\Exception $e) {
            $rs->getBody()->write( $vue->render(2)) ;
        }
//        $url_listes = $this->container->router->pathFor("compte", ['nom' => $n]);
//        return $rs->withRedirect($url_listes);
        return $rs;
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function connexion(Request $rq, Response $rs, $args) : Response {
        if (isset($_SESSION['test'])){
            if($_SESSION['test']=='test'){
                $vue = new VueCompte( [ 'res' => $_SESSION['res'] ] , $this->container ) ;
                $rs->getBody()->write( $vue->render(0));
                session_destroy();
                $_SESSION = [];
                return $rs;
            }
            //autre cas (avec les inscriptions)
        }else{
            $vue = new VueCompte([], $this->container);
            $rs->getBody()->write( $vue->render(1));
            return $rs;
        }
    }

    /**
     * POST
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function testConnexion(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $login = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;
        $res = Authentication::authenticate($login, $pass);
        if ($res){
            $url_compte = $this->container->router->pathFor("compte");
            return $rs->withRedirect($url_compte);
        }else{
            $_SESSION['test']='test';
            $_SESSION['res']=$res;
            $url_connexion = $this->container->router->pathFor("connexion");
            return $rs->withRedirect($url_connexion);
        }
    }

    public function afficherCompte(Request $rq, Response $rs, $args) : Response {
        $vue = new VueCompte( [ 'res' => true ] , $this->container ) ;
        $rs->getBody()->write( $vue->render(5));
        return $rs;
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $vue = new VueCompte( [], $this->container);
        $rs->getBody()->write($vue->render(7));
        return $rs;

    }
}