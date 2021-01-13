<?php


namespace mywishlist\controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\MaVue;
use mywishlist\vue\VueAccueil;
use mywishlist\vue\VueCompte;
use mywishlist\vue\VueItem;
use mywishlist\vue\VueListe;
use mywishlist\vue\VueParticipant;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;
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

    public function afficherListeParticipant(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::where('token','=',$args['token'])->get();
        $item = Item::where('liste_id','=',$liste[0]['no'])->get();
        $listeItem = array([$liste],[$item]);
        //var_dump($listeItem[1]);
        $vue = new VueParticipant($listeItem, $this->container);
        $rs->getBody()->write( $vue->render(2));
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
            $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
            return $rs->withRedirect($url_liste);
        }
        else {
            $_SESSION['listeExiste']=false;
            $url_connexion = $this->container->router->pathFor("participer");
            return $rs->withRedirect($url_connexion);
        }

    }
}