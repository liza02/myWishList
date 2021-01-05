<?php
declare(strict_types=1);

namespace mywishlist\controls;

use mywishlist\vue\VueAccueil;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \mywishlist\vue\MaVue;
use \mywishlist\models\Liste;
use \mywishlist\models\Item;
use mywishlist\models\User;

class MonControleur {
	private $container;
	
	public function __construct($container) {
		$this->container = $container;
	}
	public function accueil(Request $rq, Response $rs, $args) : Response {
        $vue = new VueAccueil( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 0 ) ) ;
        return $rs;
	}

	public function afficherListes(Request $rq, Response $rs, $args) : Response {
		// pour afficher la liste des listes de souhaits
		$listl = Liste::all() ;
		$vue = new MaVue( $listl->toArray() , $this->container ) ;
		$rs->getBody()->write( $vue->render( 1 ) ) ;
		return $rs;
	}
    public function afficherListe(Request $rq, Response $rs, $args) : Response {
        $liste = Liste::find( $args['no'] ) ;
        $vue = new MaVue( [ $liste->toArray() ] , $this->container ) ;
        $rs->getBody()->write( $vue->render(2));
        return $rs;
    }
	public function afficherItem(Request $rq, Response $rs, $args) : Response {
		$item = Item::find( $args['id'] ) ;
		$vue = new MaVue( [ $item->toArray() ] , $this->container ) ;
		$rs->getBody()->write( $vue->render( 3 ) ) ;
		return $rs;
	}

    public function reserverItem(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        //TODO
        return $rs;
    }

	public function afficherItemsListe(Request $rq, Response $rs, $args) : Response{
	    $liste = Liste::find($args['no']);
	    $item = Item::where('liste_id','=',$liste->no)->get();
	    $vue = new MaVue( [ $item->toArray() ] , $this->container );
	    $rs->getBody()->write( $vue->render(11));
	    return $rs;
    }
	public function formListe(Request $rq, Response $rs, $args) : Response {
		// pour afficher le formulaire liste
		$vue = new MaVue( [] , $this->container ) ;
		$rs->getBody()->write( $vue->render( 5 ) ) ;
		return $rs;
	}
    public function formlogin(Request $rq, Response $rs, $args) : Response {
        $vue = new MaVue( [] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 6 ) ) ;
        return $rs;
    }

    public function nouveaulogin(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $login       = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;

        $nb = User::where('login','=',$login)->count();
        if ($nb == 0) {
            $u = new User();
            $u->login = $login;
            $u->pass = password_hash($pass, PASSWORD_DEFAULT);
            $u->save();
        } else {
            $login = 'existe déjà';
        }

        $vue = new MaVue( [ 'login' => $login ] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 7 ) ) ;
        return $rs;
    }

    public function testform(Request $rq, Response $rs, $args) : Response {
        $vue = new MaVue( [] , $this->container ) ;
        if (!$_SERVER['HTTP_CONNECTION']) {
            $rs->getBody()->write($vue->render(10));
        } else {
            $rs->getBody()->write( $vue->render( 8 ) ) ;
        }
        return $rs;
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
	    session_destroy();
	    $_SESSION = [];
	    $vue = new MaVue( [], $this->container);
	    $rs->getBody()->write($vue->render((8)));
	    return $rs;

    }

    public function testpass(Request $rq, Response $rs, $args) : Response {
        $post = $rq->getParsedBody() ;
        $login = filter_var($post['login']       , FILTER_SANITIZE_STRING) ;
        $pass = filter_var($post['pass'] , FILTER_SANITIZE_STRING) ;
        $u = User::where('login','=',$login)->first();
        if(gettype($u) != 'NULL'){
            $res = password_verify($pass, $u->pass);
        }
        else{
            $res = false;
        }


        if ($res) $_SESSION['iduser'] = $u->id;

        $vue = new MaVue( [ 'res' => $res ] , $this->container ) ;
        $rs->getBody()->write( $vue->render( 9 ) ) ;
        return $rs;
    }

    public function newListe(Request $rq, Response $rs, $args) : Response {
        // pour enregistrer 1 liste.....
        $post = $rq->getParsedBody() ;
        $titre       = filter_var($post['titre']       , FILTER_SANITIZE_STRING) ;
        $description = filter_var($post['description'] , FILTER_SANITIZE_STRING) ;
        $date = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $l = new Liste();
        $l->titre = $titre;
        $l->description = $description;
        $l->token = bin2hex(random_bytes(10));
        $l->expiration = $date;
        //ajouter la condition s'il manque un titre ou une description
        $l->save();
        $url_listes = $this->container->router->pathFor( 'aff_listes' ) ;
        return $rs->withRedirect($url_listes);
    }
}