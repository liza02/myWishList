<?php


namespace mywishlist\controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\VueParticipant;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;
use \mywishlist\models\User;
use \mywishlist\models\Message;

/**
 * Class ControleurParticipant
 * @package mywishlist\controls
 */
class ControleurParticipant {
    private $container;

    /**
     * ControleurParticipant constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * GET
     * Affichage des listes
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherListes(Request $rq, Response $rs, $args): Response
    {
        $ensListes = Liste::where('public','=','true')->get();
        $lesListes = $ensListes->toArray();
        $arrayUser = array();
        foreach ($lesListes as $liste) {
            $arrayUser[] = User::where('id','=',$liste['user_id'])->first();
        }
        $listeItem = array([$lesListes],[$arrayUser]);

        $vue = new VueParticipant($listeItem, $this->container);
        if (isset($_SESSION['listeExiste'])) {
            if (!$_SESSION['listeExiste']) {
                $rs->getBody()->write($vue->render(0));
                $_SESSION['listeExiste'] = true;
            } else {
                $rs->getBody()->write($vue->render(1));
            }
        } else {
            $rs->getBody()->write($vue->render(1));
        }

        return $rs;

    }

    /**
     * GET
     * Affichage d'une liste en tant que participant
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherListeParticipant(Request $rq, Response $rs, $args): Response
    {
        $liste = Liste::where('token', '=', $args['token'])->first();

        //Lorsque le créateur de la liste tente d'afficher la liste alors on le redirige vers /meslistes/{token}
        if (isset($_SESSION['profile'])) {
            if ($_SESSION['profile']['userid'] == $liste['user_id']) {
                $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $args['token']]);
                return $rs->withRedirect($url_liste);
            }

            else {
                $liste = Liste::where('token', '=', $args['token'])->get();
                $item = Item::where('liste_id', '=', $liste[0]['no'])->get();
                $user = User::where('id', '=', $liste[0]['user_id'])->get();
                $listeItem = array([$liste], [$item], [$user]);
                $vue = new VueParticipant($listeItem, $this->container);
                $rs->getBody()->write($vue->render(2));
                return $rs;
            }
        } else {
            if (isset($_COOKIE['user_id'])) {
                if ($_COOKIE['user_id'] == $liste['user_id']) {
                    $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $args['token']]);
                    return $rs->withRedirect($url_liste);
                }
                else {
                    $liste = Liste::where('token', '=', $args['token'])->get();
                    $item = Item::where('liste_id', '=', $liste[0]['no'])->get();
                    $user = User::where('id', '=', $liste[0]['user_id'])->get();
                    $listeItem = array([$liste], [$item], [$user]);
                    $vue = new VueParticipant($listeItem, $this->container);
                    $rs->getBody()->write($vue->render(2));
                    return $rs;
                }
            }else {
                $liste = Liste::where('token', '=', $args['token'])->get();
                $item = Item::where('liste_id', '=', $liste[0]['no'])->get();
                $user = User::where('id', '=', $liste[0]['user_id'])->get();
                $listeItem = array([$liste], [$item], [$user]);
                $vue = new VueParticipant($listeItem, $this->container);
                $rs->getBody()->write($vue->render(2));
                return $rs;
            }
        }
    }

    /**
     * POST
     * Donne l'acces à une liste via le token --> redirection
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function accederListe(Request $rq, Response $rs, $args): Response
    {
        $post = $rq->getParsedBody();
        $token = filter_var($post['tokenListe'], FILTER_SANITIZE_STRING);
        $url = explode("/", $token);
        $token = end($url);
        var_dump($token);
        $nb = Liste::where('token', '=', $token)->count();
        $liste = Liste::where('token', '=', $token)->first();
        if ($nb != 0) {
            if (isset($_SESSION['profile'])) {
                if ($_SESSION['profile']['userid'] == $liste['user_id']) {
                    $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
                } else {
                    $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                }
            } else {
                $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
            }
            return $rs->withRedirect($url_liste);
        } else {
            $_SESSION['listeExiste'] = false;
            $url_connexion = $this->container->router->pathFor("participer");
            return $rs->withRedirect($url_connexion);
        }

    }

    /**
     * GET
     * Affichage du formulaire pour ajouter un message à une liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajouterMessage(Request $rq, Response $rs, $args): Response
    {
        $liste = Liste::where('token', '=', $args['token'])->first();
        $listeEtToken = array($liste, $args['token']);
        $vue = new VueParticipant($listeEtToken, $this->container);
        $rs->getBody()->write($vue->render(3));
        return $rs;
    }

    /**
     * POST
     * Enregistrement du message pour la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajouterUnMessage(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $nomReservant = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $message = filter_var($post['message'], FILTER_SANITIZE_STRING);
        $liste = Liste::where('token','=',$args['token'])->first();

        $m = new Message();
        $m->id_parent = $liste['no'];
        $m->type_parent = 'liste';
        $m->message = $message;
        $m->auteur = $nomReservant;
        $m->save();

        $url_retourListe = $this->container->router->pathFor("afficherListeParticipant", ['token' => $args['token']]);
        return $rs->withRedirect($url_retourListe);
    }


}