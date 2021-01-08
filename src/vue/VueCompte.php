<?php


namespace mywishlist\vue;


class VueCompte
{
    private $tab;
    private $container;

    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    private function formlogin() : string {
        $url_nouveaulogin = $this->container->router->pathFor( 'nouveaulogin' ) ;
        $html = <<<FIN
<form method="POST" action="$url_nouveaulogin">
    <label>Login:<br> <input type="text" name="login"/></label><br>
    <label>Mot de passe: <br><input type="text" name="pass"/></label><br>
    <button type="submit">Enregistrer le login</button>
</form>    
FIN;
        return $html;
    }

    private function testform() : string {
        $url_testpass = $this->container->router->pathFor( 'testpass' ) ;
        $html = <<<FIN
<form method="POST" action="$url_testpass">
    <label>Login:<br> <input type="text" name="login"/></label><br>
    <label>Mot de passe: <br><input type="text" name="pass"/></label><br>
    <button type="submit">Tester le login</button>
</form>    
FIN;
        return $html;
    }

    public function render( int $select ) : string
    {
        switch ($select) {
            case 0 :
            {
                $content = "Page du COMPTE";
                break;
            }
            case 1 :
            {
                $content = $this->formlogin();
                break;
            }
            case 2 :
            {
                $content = 'Login <b>' . $this->tab['login'] . '</b> enregistré';
                break;
            }
            case 3 :
            {
                $content = $this->testform();
                break;
            }
            case 4 :
            {
                $res = ($this->tab['res']) ? 'OK' : 'KO';
                $content = 'Mot de passe <b>' . $res . '</b></br>';
                if ($res == 'OK') $content .= 'Connecté en tant que <b>' . $_SESSION['profile']['username'] . '</b>';
                break;
            }
            case 5 :
            {
                $url_deconnexion = $this->container->router->pathFor('deconnexion');
                $content = "<a href='$url_deconnexion'>Deconnexion</a>";
                break;
            }
        }
                $url_accueil = $this->container->router->pathFor('racine');
                $url_formlogin = $this->container->router->pathFor('formlogin');
                $url_testform = $this->container->router->pathFor('testform');
                $html = <<<FIN
<!DOCTYPE html>
<html>
  <head>
    <title>MyWishList</title>
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
		<h1><a href="$url_accueil">Wish List</a></h1>
		<nav>
			<ul>
				<li><a href="$url_formlogin">Nouveau login</a></li>
				<li><a href="$url_testform">Test login</a></li>
			</ul>
		</nav>
    $content
  </body>
</html>
FIN;
        return $html;
    }
}