<?php


namespace mywishlist\vue;


class VueAccueil
{
    private $tab;
    private $container;

    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    public function listesPublique() : string{
        $html = "";
        foreach($this->tab as $liste){
            $date = date('d/m/Y',strtotime($liste['expiration']));
            $html .= "<li class='listepublique'>{$liste['titre']} <br>
                          Date d'expiration : $date </li>";
            $token = $liste['token'];
            $url_liste = $this->container->router->pathFor("aff_liste", ['token' => $token]);
            $html .= "<a class=accesliste href=$url_liste>Accéder a la liste</a>";
        }
        $html = "<h3>Listes Publiques</h3><ul>$html</ul>";
        return $html;
    }

    public function render( int $select ) : string
    {
        if (isset($_SESSION['profile']['username'])){
            $content = "Connecté en tant que : "  . $_SESSION['profile']['username'] . "<br>";
        }else{
            $content = "Non connecté<br>";
        }
        switch ($select) {
            case 0 :
            {
                $content .= $this->listesPublique();
                $url_accueil = $this->container->router->pathFor('racine');
                $url_compte = $this->container->router->pathFor('compte');
                $url_item = $this->container->router->pathFor('item');
                $url_liste = $this->container->router->pathFor('liste');
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
				<li><a class="bouton" href="$url_accueil">Accueil</a></li>
				<li><a class="bouton" href="$url_compte">Mon compte</a></li>
				<li><a class="bouton" href="$url_item">Page Item</a></li>
				<li><a class="bouton" href="$url_liste">Creer Liste</a></li>
			</ul>
		</nav>
		$content;
  </body>
</html>
FIN;
            }
        }
        return $html;
    }
}