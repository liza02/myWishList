<?php


namespace mywishlist\vue;


class VueItem
{
    private $tab;
    private $container;

    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    private function unItem() : string {
        $i = $this->tab[0][0][0];
        $l = $this->tab[0][1][0];
        $url_reserv = $this->container->router->pathFor( 'reserve_item', ['id_item' => $i['id'], 'token' => $l['token']] ) ;
        $html = "<h2>Item {$i['id']}</h2>";
        $html .= "<b>Nom:</b> {$i['nom']}<br>";
        $html .= "<b>Descr:</b> {$i['descr']}<br>";
        $image = "../../img/" . $i['img'];
        $html .= "<b>Image:</b> <br> <img src=$image><br>";
        $html .=  <<<FIN
<form method="POST" action="$url_reserv">
    <button type="submit">Reserver</button>
</form>    
FIN;
        return $html;
    }

    public function render( int $select ) : string
    {
        $content = "";
        switch ($select) {
            case 0 :
            {
                $content .= $this->unItem();
                break;
            }
        }
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
    $content
  </body>
</html>
FIN;
        return $html;
    }
}