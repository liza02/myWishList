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

            }
        }
        $url_accueil = $this->container->router->pathFor('racine');
        $url_form_liste = $this->container->router->pathFor( 'formListe');
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