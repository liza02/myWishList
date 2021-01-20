<?php


namespace mywishlist\controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use mywishlist\vue\VueListe;

use \mywishlist\models\Liste;
use \mywishlist\models\Item;

/**
 * Class ControleurListe
 * @package mywishlist\controls
 */
class ControleurListe {
    private $container;

    /**
     * ControleurListe constructor.
     * @param $container
     */
    public function __construct($container) {
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
    public function afficherMesListes(Request $rq, Response $rs, $args) : Response{
        $nb = Liste::where('user_id','=',$_SESSION['profile']['userid'])->count();
        if ($nb != 0){
            if (isset($_SESSION['modificationOK'])){
                $info = $_SESSION['profile'];
                $_SESSION = [];
                $_SESSION['profile'] = $info;
                $mesListes=Liste::where("user_id","=",$_SESSION['profile']['userid'])->get();
                $vue = new VueListe($mesListes->toArray(), $this->container);
                $rs->getBody()->write( $vue->render(0));
            }else{
                $mesListes=Liste::where("user_id","=",$_SESSION['profile']['userid'])->get();
                $vue = new VueListe($mesListes->toArray(), $this->container);
                $rs->getBody()->write( $vue->render(1));
            }
        }else{
            $vue = new VueListe([], $this->container);
            $rs->getBody()->write( $vue->render(2));
        }
        return $rs;
    }

    /**
     * GET
     * Affichage d'une liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherUneListe(Request $rq, Response $rs, $args) : Response{
        $liste = Liste::where('token','=',$args['token'])->first();
        if (isset($_SESSION['profile'])){
            if ($_SESSION['profile']['userid'] == $liste->user_id){
                if (isset($_SESSION['modificationOK'])){
                    $infoUser = $_SESSION['profile'];
                    $_SESSION = [];
                    $_SESSION['profile'] = $infoUser;
                    $liste = Liste::where('token','=',$args['token'])->get();
                    $item = Item::where('liste_id','=',$liste[0]['no'])->get();
                    $listeItem = array([$liste],[$item]);
                    $vue = new VueListe($listeItem, $this->container);
                    $rs->getBody()->write( $vue->render(4));
                    return $rs;
                }else{
                    if (isset($_SESSION['suppressionOK'])){
                        $infoUser = $_SESSION['profile'];
                        $_SESSION = [];
                        $_SESSION['profile'] = $infoUser;
                        $liste = Liste::where('token','=',$args['token'])->get();
                        $item = Item::where('liste_id','=',$liste[0]['no'])->get();
                        $listeItem = array([$liste],[$item]);
                        $vue = new VueListe($listeItem, $this->container);
                        $rs->getBody()->write( $vue->render(8));
                        return $rs;
                    }else{
                        $liste = Liste::where('token','=',$args['token'])->get();
                        $item = Item::where('liste_id','=',$liste[0]['no'])->get();
                        $listeItem = array([$liste],[$item]);
                        $vue = new VueListe($listeItem, $this->container);
                        $rs->getBody()->write( $vue->render(5));
                        return $rs;
                    }
                }
            }else {
                $url_erreurListe = $this->container->router->pathFor('erreurListe') ;
                return $rs->withRedirect($url_erreurListe);
            }
        }else{
            if (isset($_COOKIE['user_id'])) {
                if ($_COOKIE['user_id'] == $liste['user_id']){
                    $liste = Liste::where('token','=',$args['token'])->get();
                    $item = Item::where('liste_id','=',$liste[0]['no'])->get();
                    $listeItem = array([$liste],[$item]);
                    $vue = new VueListe($listeItem, $this->container);
                    $rs->getBody()->write( $vue->render(5));
                    return $rs;
                }else{
                    $url_erreurListe = $this->container->router->pathFor('erreurListe') ;
                    return $rs->withRedirect($url_erreurListe);
                }
            }else{
                $url_erreurListe = $this->container->router->pathFor('erreurListe') ;
                return $rs->withRedirect($url_erreurListe);
            }
        }
    }

    /**
     * GET
     * Affichage du formualaire de création de liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function creerListe(Request $rq, Response $rs, $args) : Response {
        // pour afficher le formulaire liste
        $vue = new VueListe( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render(3) ) ;
        return $rs;
    }

    /**
     * POST
     * Enregistrement des informations sur la nouvelle liste crée
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     * @throws \Exception
     */
    public function enregistrerListe(Request $rq, Response $rs, $args) : Response {
        // pour enregistrer 1 liste.....
        $post = $rq->getParsedBody() ;
        $titre       = filter_var($post['titre']       , FILTER_SANITIZE_STRING) ;
        $description = filter_var($post['description'] , FILTER_SANITIZE_STRING) ;
        $date = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $public = filter_var($post['public'], FILTER_SANITIZE_STRING);
        $l = new Liste();
        $l->titre = $titre;
        $l->description = $description;
        $token = bin2hex(random_bytes(10));
        $l->token = $token;
        $l->expiration = $date;
        $l->public = $public;
        $l->user_id =  $_SESSION['profile']['userid'];
        $l->save();
        //redirection sur afficher
        $url_listes = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
        return $rs->withRedirect($url_listes);
    }

    /**
     * GET
     * Affichage du formulaire de modification de liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modifierListe(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::where('token','=',$args['token'])->first();
        if (isset($_SESSION['profile'])){
            if ($_SESSION['profile']['userid'] == $liste->user_id){
                $vue = new VueListe($liste->toArray(), $this->container);
                $rs->getBody()->write( $vue->render(6));
                return $rs;
            } else{
                $url_erreurListe = $this->container->router->pathFor('erreurListe') ;
                return $rs->withRedirect($url_erreurListe);
            }
        }else{
            $url_erreurListe = $this->container->router->pathFor('erreurListe') ;
            return $rs->withRedirect($url_erreurListe);
        }


    }

    /**
     * POST
     * Enregistrement des modifications sur la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function enregistrerModificationListe(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $liste = Liste::where('token','=',$args['token'])->first();
        $titre       = filter_var($post['titre']       , FILTER_SANITIZE_STRING) ;
        $description = filter_var($post['description'] , FILTER_SANITIZE_STRING) ;
        $date = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $public = filter_var($post['public'], FILTER_SANITIZE_STRING);
        $liste->titre = $titre;
        $liste->description = $description;
        $liste->expiration = $date;
        $liste->public = $public;
        $liste->save();
        $_SESSION['modificationOK'] = true;
        $url_MesListes = $this->container->router->pathFor('afficherMesListes') ;
        return $rs->withRedirect($url_MesListes);
    }

    /**
     * GET
     * Affichage du formulaire d'ajout d'item dans une liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajoutItem(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::where('token','=',$args['token'])->first();
        $vue = new VueListe($liste->toArray(), $this->container);
        $rs->getBody()->write( $vue->render(7));
        return $rs;
    }

    /**
     * POST
     * enregistrement du nouvel item dans la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function enregistrerNouveauItemListe(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody();
        $liste = Liste::where('token','=',$args['token'])->first();
        $item = new Item();
        $item->liste_id = $liste['no'];
        $item->nom = filter_var($post['nom']       , FILTER_SANITIZE_STRING) ;
        $item->descr = filter_var($post['descr'] , FILTER_SANITIZE_STRING) ;
        $item->tarif = filter_var($post['tarif'], FILTER_SANITIZE_STRING);
        $item->url = filter_var($post['url'], FILTER_SANITIZE_STRING);

        $_SESSION['creationItemOK'] = true;
        $url_listes = $this->container->router->pathFor("aff_maliste", ['token' => $args['token']]);

        $urlIMG = filter_var($post['url_image'],FILTER_SANITIZE_STRING) ;
        if ( $urlIMG != "" ){
            $item->img = $urlIMG;
        }else{
            $file = $_FILES['image'];
            $fileName = $_FILES['image']['name'];
            $fileTmpName = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];
            $fileError = $_FILES['image']['error'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg','jpeg','png');

            if (in_array($fileActualExt, $allowed)){
                if ($fileError === 0){
                    if ($fileSize < 1000000){
                        $fileNameNew = uniqid('',true).".".$fileActualExt;
                        $fileDestination = 'img/'.$fileNameNew;
                        move_uploaded_file($fileTmpName,$fileDestination);
                    }
                }
            }
            $item->img = $fileNameNew;
        }
        $item->save();
        return $rs->withRedirect($url_listes);
    }

    /**
     * POST
     * Suppression de liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function supprimerListe (Request $rq, Response $rs, $args) : Response {
        $liste = Liste::where('token','=',$args['token'])->first();
        $liste->delete();
        $url_MesListes = $this->container->router->pathFor('afficherMesListes') ;
        return $rs->withRedirect($url_MesListes);
    }

    /**
     * GET
     * Affichage erreur liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function erreurListe(Request $rq, Response $rs, $args) : Response {
        $vue = new VueListe([], $this->container);
        $rs->getBody()->write( $vue->render(9));
        return $rs;
    }
}