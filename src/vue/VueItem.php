<?php


namespace mywishlist\vue;


class VueItem
{
    private $tab;
    private $container;

    /**
     * VueItem constructor.
     * @param $tab
     * @param $container
     */
    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    /**
     * Affichage d'un item en tant que participant
     * @return string
     */
    private function affichageItemParticipant() : string {
        $i = $this->tab[0][0];
        $i = $this->tab[0][0];
        $l = $this->tab[1][0];
        $image = "../../img/" . $i['img'];
        if ($i['reserve'] == "false"){
            $url_reservationItem = $this->container->router->pathFor("reserve_item", ['token' => $l['token'], 'id_item' => $i['id']]);
            $html = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">{$i['nom']}</h1>
            <p class="lead">{$i['descr']}</p>
            <p class="lead">Liste de référence : {$l['titre']}</p>
            <img src="$image" class="rounded mx-auto d-block" alt="{../../img/default.png}">
            <hr class="my-4">
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="$url_reservationItem" role="button">Réserver l'item</a>
            </p>
             <div class="alert alert-success" role=\"alert\">L'item n'est pas reservé</div>
        </div>
        FIN;
        }else{
            $html = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">{$i['nom']}</h1>
            <p class="lead">{$i['descr']}</p>
            <p class="lead">Liste de référence : {$l['titre']}</p>
            <hr class="my-4">
            <img src="$image" class="rounded mx-auto d-block" alt="{../../img/default.png}">
            <div class="alert alert-danger" role=\"alert\">L'item est réservé par : {$i['reserve']}</div>
        </div>
        FIN;
        }
        return $html;
    }

    /**
     * Afficher l item en tant que createur
     * @return string
     */
    private function affichageItemCreateur() : string{
        $i = $this->tab[0][0];
        $l = $this->tab[1][0];
        $image = "../../img/" . $i['img'];
        if ($i['reserve'] == "false"){
            $url_modification = $this->container->router->pathFor("modifierItem", ['token' => $l['token'], 'id_item' => $i['id']]);
            $html = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">{$i['nom']}</h1>
            <p class="lead">{$i['descr']}</p>
            <p class="lead">Liste de référence : {$l['titre']}</p>
            <img src="$image" class="rounded mx-auto d-block" alt="{../../img/default.png}">
            <hr class="my-4">
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="$url_modification" role="button">Modifier l'item</a>
            </p>
             <div class="alert alert-success" role=\"alert\">L'item n'est pas reservé</div>
        </div>
        FIN;
        }else{
            $html = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">{$i['nom']}</h1>
            <p class="lead">{$i['descr']}</p>
            <p class="lead">Liste de référence : {$l['titre']}</p>
            <hr class="my-4">
            <img src="$image" class="rounded mx-auto d-block" alt="{../../img/default.png}">
            <div class="alert alert-danger" role=\"alert\">L'item est réservé</div>
        </div>
        FIN;
        }
        return $html;
    }

    /**
     * Formulaire de réservation
     * @return string
     */
    public function formReservation() : string{

        return "reservation";
    }

    /**
     * Formulaire de modification
     * @return string
     */
    public function formModification() : string{
        return "modification";
    }

    /**
     * RENDER
     * @param int $select
     * @return string
     */
    public function render( int $select ) : string
    {
        $content = "";
        $path="";
        $current_page="";
        $pathIntermediaire ="";
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
        switch ($select) {
            // message de reservation en plus
            case 0 :
            {

            }
            // afficher l'item en tant que participant
            case 1 :
            {
                $path = "../../";
                $token = $this->tab[1][0]['token'];
                $url_participer = $this->container->router->pathFor('participer');
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_participer\">Participer</a></li>";
                $url_listeActive = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_listeActive\">{$this->tab[1][0]['titre']}</a></li>";
                $current_page = $this->tab[0][0]['nom'];
                $content .= $this->affichageItemParticipant();
                break;
            }
            // message de mofification de liste
            case 2 :
            {

            }
            // afficher l'item en tant que createur
            case 3 :
            {
                $path = "../../";
                $token = $this->tab[1][0]['token'];
                $url_meslistes = $this->container->router->pathFor('afficherMesListes');
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_meslistes\">Mes Listes</a></li>";
                $url_listeActive = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_listeActive\">{$this->tab[1][0]['titre']}</a></li>";
                $current_page = $this->tab[0][0]['nom'];
                $content .= $this->affichageItemCreateur();
                break;
            }
            // affichage reservation item
            case 4 :
            {
                $path = "../../../";
                $token = $this->tab[1][0]['token'];
                $url_participer = $this->container->router->pathFor('participer');
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_participer\">Participer</a></li>";
                $url_listeActive = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_listeActive\">{$this->tab[1][0]['titre']}</a></li>";
                $url_participationItem = $this->container->router->pathFor("aff_item", ['id_item' => $this->tab[0][0]['id'], 'token' => $token]);
                $pathIntermediaire .="<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_participationItem\">{$this->tab[0][0]['nom']}</a></li>";
                $current_page = "Reservation";
                $content .= $this->formReservation();
                break;

            }
            // affichage modification item
            case 5 :{
                $path = "../../../";
                $token = $this->tab[1][0]['token'];
                $url_meslistes = $this->container->router->pathFor('afficherMesListes');
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_meslistes\">Mes Listes</a></li>";
                $url_listeActive = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_listeActive\">{$this->tab[1][0]['titre']}</a></li>";
                $url_meslistesItem = $this->container->router->pathFor("aff_item_admin", ['id_item' => $this->tab[0][0]['id'], 'token' => $token]);
                $pathIntermediaire .="<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_meslistesItem\">{$this->tab[0][0]['nom']}</a></li>";
                $current_page = "Modification";
                $content .= $this->formModification();
                break;
            }
        }
        $url_accueil = $this->container->router->pathFor('racine');
        $url_participer = $this->container->router->pathFor('participer');
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
        $content
    </div>
    
</body>
</html>
FIN;
        return $html;
    }
}