<?php


namespace mywishlist\vue;


class VueListe
{
    private $tab;
    private $container;

    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    public function render( int $select ) : string
    {
        switch ($select) {
            case 0 :
            {
                $url_accueil = $this->container->router->pathFor('racine');
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
		</nav>
    'Page des LISTES'
  </body>
</html>
FIN;
            }
        }
        return $html;
    }
}