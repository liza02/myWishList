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
        $html = '';
        foreach($this->tab as $liste){
            $html .= "<li>{$liste['titre']}, {$liste['description']}</li>";
        }
        $html = "<ul>$html</ul>";
        return $html;
    }

    public function render( int $select ) : string
    {
        switch ($select) {
            case 0 :
            {
                $content = $this->listesPublique();
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
				<li><a class="bouton" href="$url_accueil">Page Accueil</a></li>
				<li><a class="bouton" href="$url_compte">Page Compte</a></li>
				<li><a class="bouton" href="$url_item">Page Item</a></li>
				<li><a class="bouton" href="$url_liste">Page Liste</a></li>
			</ul>
		</nav>
		<h3>Liste Publique</h3>
		$content;
  </body>
</html>
FIN;
            }
        }
        return $html;
    }
}