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
use \mywishlist\models\User;
class ControleurParticipant
{
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

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherListeParticipant(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::where('token','=',$args['token'])->first();
        if (isset($_SESSION['profile'])){
            if ($_SESSION['profile']['userid'] == $liste['user_id']){
                $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $args['token']]);
                return $rs->withRedirect($url_liste);
            }else{
                $liste = Liste::where('token','=',$args['token'])->get();
                $item = Item::where('liste_id','=',$liste[0]['no'])->get();
                $user = User::where('id','=',$liste[0]['user_id'])->get();
                $listeItem = array([$liste],[$item],[$user]);
                $vue = new VueParticipant($listeItem, $this->container);
                $rs->getBody()->write( $vue->render(2));
                return $rs;
            }
        }else{
            $liste = Liste::where('token','=',$args['token'])->get();
            $item = Item::where('liste_id','=',$liste[0]['no'])->get();
            $user = User::where('id','=',$liste[0]['user_id'])->get();
            $listeItem = array([$liste],[$item],[$user]);
            $vue = new VueParticipant($listeItem, $this->container);
            $rs->getBody()->write( $vue->render(2));
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
    public function accederListe(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $token = filter_var($post['tokenListe']       , FILTER_SANITIZE_STRING) ;
        $url = explode("/", $token);
        $token = end($url);
        var_dump($token);
        $nb = Liste::where('token','=',$token)->count();
        $liste = Liste::where('token','=',$token)->first();
        if ($nb != 0) {
            if (isset($_SESSION['profile'])){
                if ($_SESSION['profile']['userid'] == $liste['user_id']){
                    $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
                }else{
                    $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                }
            }else{
                $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
            }
            return $rs->withRedirect($url_liste);
        }
        else {
            $_SESSION['listeExiste']=false;
            $url_connexion = $this->container->router->pathFor("participer");
            return $rs->withRedirect($url_connexion);
        }

    }
}