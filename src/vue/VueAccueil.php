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
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
    <!-- Bootstrap CSS File -->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Libraries CSS Files -->
    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="$url_accueil">
            <img src="img/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            MYWISHLIST
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="$url_accueil">Accueil</a></li>
                <li><a href="$url_item">Page Item</a></li>
                <li><a href="$url_liste">Creer Liste</a></li>
                <li><a href="$url_compte">Mon compte</a></li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
$content;
</body>
</html>
FIN;
            }
        }
        return $html;
    }
}