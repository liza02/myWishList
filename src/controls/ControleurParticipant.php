<?php


namespace mywishlist\controls;


use mywishlist\models\Liste;
use mywishlist\vue\VueParticipant;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControleurParticipant
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function afficherListes(Request $rq, Response $rs, $args) : Response {
        $ensListes = Liste::where('public','=','true')->get();
        $vue = new VueParticipant( $ensListes->toArray() , $this->container ) ;
        if (isset($_SESSION['listeExiste'])) {
            if(!$_SESSION['listeExiste']){
                $rs->getBody()->write( $vue->render(0));
                $_SESSION['listeExiste'] = true;
            }
            else {
                $rs->getBody()->write($vue->render(1));
            }
        }
        else {
            $rs->getBody()->write($vue->render(1));
        }

        return $rs;

    }

    public function accederListe(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $token = filter_var($post['tokenListe']       , FILTER_SANITIZE_STRING) ;
        $url = explode("/", $token);
        $token = end($url);
        var_dump($token);
        $nb = Liste::where('token','=',$token)->count();
        if ($nb != 0) {
            $url_liste = $this->container->router->pathFor("aff_liste", ['token' => $token]);
            return $rs->withRedirect($url_liste);
        }
        else {
            $_SESSION['listeExiste']=false;
            $url_connexion = $this->container->router->pathFor("participer");
            return $rs->withRedirect($url_connexion);
        }

    }
}