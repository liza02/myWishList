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
        $post = $rq->getParsedBody();
        $login = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($post['prenom'], FILTER_SANITIZE_STRING);
        $vue = new VueCompte( [ 'login' => $login ] , $this->container ) ;
        try {
            //redirection sur mon afficherCompte avec $_SESSION
            Authentication::createUser($nom, $prenom,$login, $pass);
            Authentication::authenticate($login, $pass);
            $_SESSION['inscriptionOK'] = true;
            $url_afficherCompte = $this->container->router->pathFor("afficherCompte");
            return $rs->withRedirect($url_afficherCompte);
        }
        catch (\Exception $e) {
            $rs->getBody()->write( $vue->render(2));
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
    public function connexion(Request $rq, Response $rs, $args) : Response {
        if (isset($_SESSION['connexionOK'])){
            if(!$_SESSION['connexionOK']){
                $vue = new VueCompte([] , $this->container ) ;
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
        $connexionOK = Authentication::authenticate($login, $pass);
        if ($connexionOK){
            $url_compte = $this->container->router->pathFor("afficherCompte");
            return $rs->withRedirect($url_compte);
        }else{
            $_SESSION['connexionOK']=false;
            $url_connexion = $this->container->router->pathFor("connexion");
            return $rs->withRedirect($url_connexion);
        }
    }

    public function afficherCompte(Request $rq, Response $rs, $args) : Response {
        // isset pour inscriptionOK et redirection sur compte
        //TODO where fonctionnel ? -> récupération des listes de l'utilisateur connecté
        $infosUser = User::where('login','=',$_SESSION['profile']['username'])->first();
        $vue = new VueCompte($infosUser->toArray(), $this->container ) ;
        if (isset($_SESSION['inscriptionOK'])) {
            if ($_SESSION['inscriptionOK']) {
                // on vient de s'inscrire
                $rs->getBody()->write( $vue->render(4));
                $info = $_SESSION['profile'];
                $_SESSION = [];
                $_SESSION['profile'] = $info;
                return $rs;
            }
            else {
                $rs->getBody()->write( $vue->render(5));
                return $rs;
            }
        }
        else if (isset($_SESSION['passwordOK'])) {
                if ($_SESSION['passwordOK']) {
                    $info = $_SESSION['profile'];
                    $_SESSION = [];
                    $_SESSION['profile'] = $info;
                    $vue = new VueCompte($infosUser->toArray(), $this->container);
                    $rs->getBody()->write($vue->render(7));
                    return $rs;
                } else {
                    $info = $_SESSION['profile'];
                    $_SESSION = [];
                    $_SESSION['profile'] = $info;
                    $vue = new VueCompte($infosUser->toArray(), $this->container);
                    $rs->getBody()->write($vue->render(5));
                    return $rs;
                }
            }
            else{
                $rs->getBody()->write($vue->render(5));
                return $rs;
            }

    }

    public function modifierCompte(Request $rq, Response $rs, $args) : Response  {
        $infosUser = User::where('login','=',$_SESSION['profile']['username'])->first();

        $vue = new VueCompte( $infosUser->toArray() , $this->container ) ;
        $rs->getBody()->write( $vue->render(6)) ;
        return $rs;
    }

    public function enregistrerModif(Request $rq, Response $rs, $args) : Response {
        $infoUser = User::where("id","=",$_SESSION['profile']['userid'])->first();
        $post = $rq->getParsedBody();
        $nouveauLogin = filter_var($post['login'], FILTER_SANITIZE_STRING);
        $nbNouveauLogin = User::where("login","=",$nouveauLogin)->count();
        $nouveauEmail = filter_var($post['email']);
        $nbNouveauEmail = User::where("email","=",$nouveauEmail)->count();
        $nouveauNom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $nouveauPrenom = filter_var($post['prenom'], FILTER_SANITIZE_STRING);
        if ($nbNouveauLogin > 0 && $nouveauLogin != $infoUser->login) {
            $vue = new VueCompte($infoUser->toArray(), $this->container);
            $rs->getBody()->write($vue->render(8));
            return $rs;
        }
        elseif ($nbNouveauEmail > 0 && $nouveauEmail != $infoUser->email) {
            $vue = new VueCompte($infoUser->toArray(), $this->container);
            $rs->getBody()->write($vue->render(9));
            return $rs;
        }
        else {
            $infoUser->nom = $nouveauNom;
            $infoUser->prenom = $nouveauPrenom;
            $infoUser->login = $nouveauLogin;
            $infoUser->email = $nouveauEmail;
            $infoUser->save();
            $vue = new VueCompte( $infoUser->toArray(), $this->container ) ;
            $_SESSION['profile']['username'] = $nouveauLogin;
            $rs->getBody()->write( $vue->render(7));
            return $rs;
        }
    }

    public function changerMotDePasse (Request $rq, Response $rs, $args) : Response  {
        $infosUser = User::where('login','=',$_SESSION['profile']['username'])->first();
        $vue = new VueCompte( $infosUser->toArray() , $this->container ) ;
        $rs->getBody()->write($vue->render(10));
        return $rs;
    }

    public function enregistrerMotDePasse(Request $rq, Response $rs, $args) : Response {
        $infosUser = User::where('login','=',$_SESSION['profile']['username'])->first();
        $post = $rq->getParsedBody();
        $ancienMDP = filter_var($post['ancienMDP'], FILTER_SANITIZE_STRING);
        $nouveauMDP = filter_var($post['nouveauMDP'], FILTER_SANITIZE_STRING);
        $confirmerMDP = filter_var($post['confirmerMDP'], FILTER_SANITIZE_STRING);
        $mdpOK = Authentication::authenticate($_SESSION['profile']['username'], $ancienMDP);
        if (!$mdpOK) {
            $vue = new VueCompte( $infosUser->toArray() , $this->container ) ;
            $rs->getBody()->write($vue->render(11)) ;
            return $rs;
        }
        else {
            if ($nouveauMDP != $confirmerMDP) {
                $vue = new VueCompte( $infosUser->toArray() , $this->container ) ;
                $rs->getBody()->write($vue->render(12)) ;
                return $rs;
            }
            else {
                $infosUser->pass = password_hash($nouveauMDP, PASSWORD_DEFAULT);
                $infosUser->save();
                $_SESSION['passwordOK'] = true;
                $url_enregisterModif = $this->container->router->pathFor('enregistrerModif');
                return $rs->withRedirect($url_enregisterModif);
            }
        }
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $url_accueil = $this->container->router->pathFor('racine');
        $vue = new VueCompte( [], $this->container);
        return $rs->withRedirect($url_accueil);

    }
}