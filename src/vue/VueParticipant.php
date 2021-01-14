<?php
namespace mywishlist\vue;

class VueParticipant
{
    private $tab; // tab array PHP
    private $container;
    private $today;

    /**
     * VueParticipant constructor
     * @param $tab
     * @param $container
     */
    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
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
        $this->today = $annee . "-" . $mois . "-" . $jour;
    }

    /**
     * Methode qui retourne les listes publiques non expirées
     * @return string
     */
    private function lesListes()
    {
        $html = "";
        foreach ($this->tab as $liste) {
            $date = date('Y-m-d',strtotime($liste['expiration']));
            if ($date >= $this->today) {
                $date = date('d/m/Y', strtotime($liste['expiration']));
                $html .= "<li class='listepublique'>{$liste['titre']} <br>
                          Date d'expiration : $date </li>";
                $token = $liste['token'];
                $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                $html .= "<a class=accesliste href=$url_liste>Accéder a la liste</a>";
            }
        }
        $url_accederListe = $this->container->router->pathFor("accederListe");
        $html = <<<FIN
<h3>Participer à une liste privée :</h3>
<div class="card card_form">
            <div class="card-header text-center">
                Participer à une liste privée !
            </div>
            <div class="card-body">
                <form method="POST" action="$url_accederListe">
                    <div class="form-group">
                        <label for="form_token" >URL de la liste :</label>
                        <input type="text" class="form-control" id="form_token" placeholder="nosecure1" name="tokenListe" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary text-center">Consulter la liste</button>
                    </div>
                 
                </form>    
            </div>
        </div>
<br>
<h3>Participer à une liste publique :</h3><ul>$html</ul>
FIN;
        return $html;
    }

    /**
     * Méthode qui affiche une liste en tant que participant
     * @return string
     */
    private function afficherListeParticipant() : string{
        $l = $this->tab[0][0][0];
        $u = $this->tab[2][0][0];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html_items = "";
        $html_infosListe = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">Liste : {$l['titre']}</h1>
            <p class="lead">{$l['description']}</p>
            <p class="lead">Propriétaire : {$u['prenom']} {$u['nom']} </p>
            <hr class="my-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Partagez la liste</span>
              </div>
              <input type="text" class="form-control" aria-label="url" value="{$actual_link}" id="myInput">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
              </div>
            </div>
        </div>
        FIN;

        foreach ($this->tab[1] as $tableau){
            $html_items .= "<div class=\"container\"> <div class=\"row\">";
            foreach ($tableau as $items){
                $url_item = $this->container->router->pathFor("aff_item", ['id_item' => $items['id'], 'token' => $l['token']]);
                $url_reservationItem = $this->container->router->pathFor("reserve_item", ['token' => $l['token'], 'id_item' => $items['id']]);
                $image = "../img/" . $items['img'];
                if (strlen($items['descr']) >= 80) {
                    $description = substr($items['descr'], 0, 80) . "...";
                } else {
                    $description = $items['descr'];
                }
                $html_items .= <<<FIN
                <div class="col-3 Itembox">
                    <div class="card h-100 mb-3 border-secondary">
                      <img class="card-img-top image_item" src="$image" onError="this.onerror=null;this.src='../img/default.png';">
                      <div class="card-body">
                        <h5 class="card-title">{$items['nom']}</h5>
                        <p class="card-text">{$description}</p>
                      </div>
                      <footer class="bouton_item text-center">
                           <a href="$url_item" class="btn btn-primary">Voir item</a>
                           <a type="submit" class="btn btn-warning" href="$url_reservationItem" role="button"> Réserver</a>
                      </footer>
                    </div>
                </div>
                FIN;
            }
            $html_items .= "</div></div>";
        }
        $html_items = $html_infosListe .  $html_items;
        return $html_items;
    }

    /**
     * Render
     * @param $select
     * @return string
     */
    public function render($select)
    {
        if (isset($_SESSION['profile']['username'])){
            $content = "<div id='connected'>Connecté en tant que : "  . $_SESSION['profile']['username'] . "</div>";
            $connected = "Mon Compte";
            $url_compte = $this->container->router->pathFor('afficherCompte');
            $url_liste = $this->container->router->pathFor('afficherMesListes');

        }else{
            $content = "<div id='not_connected'>Non connecté</div>";
            $connected = "Connexion";
            $url_compte = $this->container->router->pathFor('connexion');
            $url_liste = $this->container->router->pathFor('connexion');
        }
        $path="";
        $current_page="";
        $pathIntermediaire ="";
        $url_accueil = $this->container->router->pathFor('racine');
        $url_participer = $this->container->router->pathFor('participer');
        switch ($select) {
            case 0 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\">La token saisi ne correspond à aucune liste</div>";
            }
            // affichage des listes
            case 1 :
            {
                $content .= $this->lesListes();
                break;
            }
            // affichage d'une liste en tant que participant
            case 2 :
            {
                $path ="../";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_participer\">Participer</a></li>";
                $current_page = $this->tab[0][0][0]['titre'];
                $content .= $this->afficherListeParticipant();
                break;
            }
        }
        $html = <<<FIN
<!DOCTYPE html>
<html>
<head>
    <title>MyWishList</title>
    <link rel="stylesheet" href="{$path}css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="$url_accueil">
        <img src="{$path}img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        MYWISHLIST
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"> <a class="nav-link" href="$url_accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link active" href="$url_participer">Participer à une liste</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_liste">Gérer mes listes</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_compte">$connected</a></li>
            </ul>
        </div>
    </nav>
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item " aria-current="page"><a href="$url_accueil">Home</a></li>
            $pathIntermediaire
            <li class="breadcrumb-item active" aria-current="page">$current_page</li>
        </ol>
    </nav>

    <div>
        $content;
    </div>
    
</body>
</html>
FIN;
        return $html;
    }
}