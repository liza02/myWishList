<?php


namespace mywishlist\vue;

use mywishlist\controls\ControleurAccueil;

/**
 * Class VueAccueil
 * @package mywishlist\vue
 */
class VueAccueil {

    private $tab;
    private $container;
    private $today;

    /**
     * VueAccueil constructor.
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
     * Affichage des listes publiques
     * @return string
     */
    public function listesPublique() : string{
        $html = "<h3>Listes Publiques</h3><div class=\"row\">";
        $increment_user = 0;
        // Boucle sur l'ensemble des listes
        foreach($this->tab[0][0][0] as $liste){
            // Récupération du user appartenant à la liste courante de la boucle
            $user = $this->tab[0][1][0][$increment_user];
            if ($user != null) {
                $createur = $user['prenom'];
            }
            else {
                $createur = 'Compte supprimé';
            }
            $date = date('Y-m-d',strtotime($liste['expiration']));
            if ($date >= $this->today) {
                $date = date('d/m/Y',strtotime($liste['expiration']));
                $token = $liste['token'];

                if (strlen($liste['description']) >= 80) {
                    $description = substr($liste['description'], 0, 80) . "...";
                } else {
                    $description = $liste['description'];
                }

                $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                $html .= <<<FIN
                <div class="col-3 box_list">
                    <div class="card h-100 border-light mb-3" >
                        <div class="card-header text-center">
                            <p>{$liste['titre']} </p>
                        </div>
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Créateur :  $createur</h6>
                            <p class="card-text">{$description}</p>
                        </div>
                        <footer class="bouton_footer text-center">
                                <a type="submit" class="btn btn-primary" href="$url_liste" role="button">Accéder</a>
                        </footer>
                        <div class="card-footer">
                            <small class="text-muted">Date d'expiration : $date</small>
                        </div>
                    </div>
                </div>
                FIN;
            }
            $increment_user++;
        }
        $html .= "</div>";
        $debut = $html . "<br><br><h3>Listes Publiques des créateurs</h3><div class=\"row\">";
        $users = $this->tab[1];
        $compteur = 0;
        foreach ($users as $user){
            $liste = $this->tab[2][$compteur];
            if (gettype($liste[0]) != 'string') {
                $createur = $user['nom'];
                $debut .= "<div class=\"col-3 box_list\">
                        <div class=card>
                        <div class=card-header text-center>
                            <p>Créateur :  $createur </p>
                        </div>
                        <ul class=list-group list-group-flush>";
                foreach ($liste as $l) {
                    $token = $l['token'];
                    $url_liste = $this->container->router->pathFor("afficherListeParticipant", ['token' => $token]);
                    $debut .= " <li class=list-group-item><a href=$url_liste>{$l['titre']}</a></li>
                                ";
                }
                $debut.= "</ul>
                      </div>
                     </div>";
            }
            $compteur++;
        }
        $debut .= "</div>";
        return $debut;
    }

    public function listeCreateur() : string{

    }
    /**
     * RENDER
     * @param int $select
     * @return string
     */
    public function render( int $select ) : string
    {
        if (isset($_SESSION['profile']['username'])){
            $content = "<div id='connected'>Connecté en tant que : "  . $_SESSION['profile']['username'] . "</div>";
            $connected = "Mon Compte";
            $url_compte = $this->container->router->pathFor('afficherCompte');
            $url_gererMesListe = $this->container->router->pathFor('afficherMesListes');

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
                $url_participer = $this->container->router->pathFor('participer');
                break;
            }
            case 1 :
                $content .= $this->listeCreateur();
                $url_accueil = $this->container->router->pathFor('racine');
                $url_participer = $this->container->router->pathFor('participer');
                break;
        }
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
                <li class="nav-item"><a class="nav-link" href="$url_participer">Participer à une liste</a></li>
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

    <div class="vueAccueil">
        $content
    </div>
    
</body>
</html>
FIN;
        return $html;
    }
}