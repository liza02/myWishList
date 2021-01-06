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

    private function lesListes() : string {
        //var_dump($this->tab); // tableau de tableau, array de array
        $html = '';
        foreach($this->tab as $liste){
            $html .= "<li>{$liste['titre']}, {$liste['description']}</li>";
        }
        $html = "<ul>$html</ul>";
        return $html;
    }

    private function formListe() : string {
        $url_new_liste = $this->container->router->pathFor( 'newListe' ) ;
        $html = <<<FIN
<form method="POST" action="$url_new_liste">
	<label>Titre:<br> <input type="text" name="titre"/></label><br>
	<label>Description: <br><input type="text" name="description"/></label><br>
	<label>Date d'expiration : <br><input type="date" name="date" 
	value="2020-01-01" min="2020-01-01" max="2020-12-31"></label>
	<button type="submit">Enregistrer la liste</button>
</form>	
FIN;
        return $html;
    }

    private function uneListeItems() : string {
        $html1 = "<h2>Liste {$this->tab[0][0]['liste_id']} :</h2>";
        $html2 = "";
        foreach ($this->tab as $tableau){
            foreach ($tableau as $items){
                $image = "../img/" . $items['img'];
                $html2 .= "<li> <h3> Item </h3> id: {$items['id']} | titre: {$items['nom']} | descr: {$items['descr']} | Image: <br> <img src=$image></li>";
                $html2 .= "<br>";

                $html2 .= "<h4>Partager la liste ici :</h4> /{$items['token']}";
            }
        }
        $html2 = $html1 . "<ul> $html2 </ul>";
        return $html2;
    }

    public function render( int $select ) : string
    {
        switch ($select) {
            case 0 :
            {
                $content = $this->formListe();
                break;
            }
            case 1 :
            {
                $content = $this->uneListeItems();
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
			<ul>
				
			</ul>
		</nav>
    $content
  </body>
</html>
FIN;
        return $html;
    }
}