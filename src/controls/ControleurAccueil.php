<?php


namespace mywishlist\controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\VueAccueil;

use \mywishlist\models\Liste;
use mywishlist\models\User;

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
     * Affichage des listes publiques dans l'accueil
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function accueil(Request $rq, Response $rs, $args) : Response {
        $ensListes = Liste::where('public','=','true')->get();
        $lesListes = $ensListes->toArray();
        $arrayUser = array();
        foreach ($lesListes as $liste) {
            $arrayUser[] = User::where('id','=',$liste['user_id'])->first();
        }
        $listeItem = array([$lesListes],[$arrayUser]);
        $ensCreateur = User::all();
        $arrayListe = array();
        foreach ($ensCreateur as $createur){
            if (Liste::where('user_id','=',$createur['id'])->count() > 0){
                $arrayListe[] = Liste::where('user_id','=',$createur['id'])->get()->toArray();
            }else{
                $arrayListe[] = 'vide';
            }
        }
        $vue = new VueAccueil([$listeItem, $ensCreateur, $arrayListe], $this->container);
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }

}