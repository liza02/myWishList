<?php


namespace mywishlist\controls;

use mywishlist\models\User;
use mywishlist\vue\VueListe;
use mywishlist\vue\VueParticipant;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\MaVue;
use mywishlist\vue\VueAccueil;
use mywishlist\vue\VueCompte;
use mywishlist\vue\VueItem;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;

class ControleurAccueil
{
    private $container;

    /**
     * ControleurAccueil constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
//        session_destroy();
//        $_SESSION = [];
    }

    /**
     * GET
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function accueil(Request $rq, Response $rs, $args) : Response {
        $ensListes = Liste::where('public','=','true')->get();
//        $vue = new VueAccueil( $ensListes->toArray() , $this->container ) ;
//        $rs->getBody()->write($vue->render(0));
        $arrayUser = array();
        foreach ($ensListes as $liste) {
            $arrayUser[] = User::where('id','=',$liste['user_id'])->first();
        }
        $listeItem = array([$liste],[$arrayUser]);

        $vue = new VueAccueil($listeItem, $this->container);
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }

}