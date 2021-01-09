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

    private function formListe() : string {
        $url_new_liste = $this->container->router->pathFor( 'newListe' ) ;
        $today = getdate();
        $jour = $today['mday'];
        $mois = $today['mon'];
        $annee = $today['year'];
        if ($mois < 10) {
            $mois = 0 . $mois;
        }
        if ($jour < 10) {
            $jour = 0 . $jour;
        }
        $html = <<<FIN
<form method="POST" action="$url_new_liste">
	<label>Titre:<br> <input type="text" name="titre" required/></label><br>
	<label>Description: <br><input type="text" name="description" required/></label><br>
	<label>Date d'expiration : <br><input type="date" name="date" 
	value="$annee-$mois-$jour" min="2020-01-01" max="2030-12-31" required></label><br>
	<label>Liste publique ? <input type="checkbox" name="public"></label><br><br>
	<button type="submit">Enregistrer la liste</button>
</form>	
FIN;
        return $html;
    }

    private function uneListeItems() : string {
        $l = $this->tab[0][0][0];
        $html1 = "<h2>Liste {$l['no']}</h2>";
        $html1 .= "<b>Titre:</b> {$l['titre']}<br>";
        $html1 .= "<b>Description:</b> {$l['description']}<br>";
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html1 .= "<b>Partagez votre liste : </b>$actual_link";
        $html2 = "";
        foreach ($this->tab[1] as $tableau){
            foreach ($tableau as $items){
                $url_item = $this->container->router->pathFor("aff_item", ['id_item' => $items['id'], 'token' => $l['token']]);
                $image = "../img/" . $items['img'];
                $html2 .= "<li> <a href='$url_item' <h3> Item </h3> </a>id: {$items['id']} | titre: {$items['nom']} | descr: {$items['descr']} | Image: <br> <img src=$image></li>";
                $html2 .= "<br>";
            }
        }
        //$html2 .= "<h4>Partager la liste ici :</h4> /{$items['token']}";
        $html2 = $html1 . "<ul> $html2 </ul>";
        return $html2;
    }

    private function uneListe() : string {
        $l = $this->tab[0];
        $html = "<h2>Liste {$l['no']}</h2>";
        $html .= "<b>Titre:</b> {$l['titre']}<br>";
        $html .= "<b>Description:</b> {$l['description']}<br>";
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html .= "<b>Partagez votre liste : </b>$actual_link";
        //$html .= "<b>Partage de liste: /myWishList/liste/{$l['token']}</b>";
        return $html;
    }

    public function render( int $select ) : string
    {
       $content = "";

        switch ($select) {
            case 0 :
            {
                $content .= $this->formListe();
                break;
            }
            case 1 :
            {
                $content .= $this->uneListeItems();
                break;
            }
            case 2 :
            {
                $content .= $this->uneListe();
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