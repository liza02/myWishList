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
            $content = "<div id='connected'>Connecté en tant que : "  . $_SESSION['profile']['username'] . "</div>";
            $connected = "Mon Compte";
            $url_compte = $this->container->router->pathFor('afficherCompte');
            $url_gererMesListe = $this->container->router->pathFor('afficherGererMesListes');

        }else{
            $content = "<div id='not_connected'>Non connecté</div>";
            $connected = "Connexion";
            $url_compte = $this->container->router->pathFor('connexion');
            $url_gererMesListe = $this->container->router->pathFor('connexion');
        }
        switch ($select) {
            case 0 :
            {
                $content .= $this->listesPublique();
                $url_accueil = $this->container->router->pathFor('racine');
                $url_item = $this->container->router->pathFor('item');

                $html = <<<FIN
<!DOCTYPE html>
<html>
<head>
    <title>MyWishList</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="$url_accueil">
        <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        MYWISHLIST
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"> <a class="nav-link active" href="$url_accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_item">Participer à une liste</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_gererMesListe">Gérer mes listes</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_compte">$connected</a></li>
            </ul>
        </div>
    </nav>
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Home</li>
        </ol>
    </nav>

    <div>
        $content;
    </div>
    
</body>
</html>
FIN;
            }
        }
        return $html;
    }
}