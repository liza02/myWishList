<?php
namespace mywishlist\vue;
use mywishlist\models\Message;


class VueParticipant {

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
        $html = "<div class=\"row\">";
        $increment_user = 0;
        // Boucle sur toutes les listes publiques
        foreach($this->tab[0][0] as $liste){
            // Récupération du User courant sur la boucle
            $user = $this->tab[1][0][$increment_user];
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
                <div class="col-3 ">
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
        $url_accederListe = $this->container->router->pathFor("accederListe");
        $html = <<<FIN
<h3>Participer à une liste privée :</h3>
<div class="card card_listePvForm">
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
        // Liste dans l'array
        $l = $this->tab[0][0][0];
        // User dans l'array
        if (isset($this->tab[2][0][0])) {
            $u = $this->tab[2][0][0];
            $nomCreateur = $u['nom'];
            $prenomCreateur = $u['prenom'];
        }
        else {
            $nomCreateur = 'Compte supprimé';
            $prenomCreateur = '';
        }
        $url_message = $this->container->router->pathFor("afficherFormMessage", ['token' => $l['token']]);

        $messages = Message::where('id_parent', '=', $l['no'])->where('type_parent', '=', 'liste')->get()->toArray();
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html_items = "";
        $html_infosListe = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">Liste : {$l['titre']}</h1>
            <p class="lead">{$l['description']}</p>
            <p class="lead">Propriétaire : $nomCreateur $prenomCreateur</p>
            <hr class="my-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Partagez la liste</span>
              </div>
              <input readonly type="text" class="form-control" aria-label="url" value="{$actual_link}" id="myInput">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
              </div>
            </div>
            <p class="lead">
               <a class="btn btn-success btn-lg" href="$url_message" role="button">Ajouter un message</a>
            </p>
        </div>
        FIN;

        // Boucle sur les items de l'array
        foreach ($this->tab[1] as $tableau){
            $count_bloc_line = 0;
            $html_items .= "<div class=\"container\"> <div class=\"row\">";
            foreach ($tableau as $items){
                $url_item = $this->container->router->pathFor("aff_item", ['id_item' => $items['id'], 'token' => $l['token']]);
                $url_reservationItem = $this->container->router->pathFor("reserve_item", ['id_item' => $items['id'], 'token' => $l['token']]);
                $image = "../img/" . $items['img'];
                if (strlen($items['descr']) >= 80) {
                    $description = substr($items['descr'], 0, 80) . "...";
                } else {
                    $description = $items['descr'];
                }
                if ($items['reserve'] == "false"){
                    $bouton = "<a type=\"submit\" class=\"btn btn-warning\" href=\"$url_reservationItem\" role=\"button\"> Réserver</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-success\">DISPONIBLE</span></h7>";
                }else{
                    $bouton = "<a class=\"btn btn-secondary disabled\" href=\"$url_reservationItem\" role=\"button\" aria-disabled=\"true\">Réserver</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-secondary\">RÉSERVÉ</span></h7>";
                }
                if ($items['cagnotteActive'] == "true") {
                    $bouton = "<a class=\"btn btn-secondary disabled\" href=\"$url_reservationItem\" role=\"button\" aria-disabled=\"true\">Réserver</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-warning\">CAGNOTTE</span></h7>";
                    if ($items['cagnotte'] != $items['tarif']) {
                    }
                    else {
                        $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-secondary\">CAGNOTTE</span></h7>";
                    }

                }
                if ($l['expiration']<$this->today) {
                    $bouton = "<a class=\"btn btn-secondary disabled\" href=\"$url_reservationItem\" role=\"button\" aria-disabled=\"true\">Réserver</a>";
                }

                $tarif = "<h7><span class=\"badge badge-info\">{$items['tarif']}€</span></h7>";
                $html_items .= <<<FIN
                <div class="col-3 Itembox">
                    <div class="card h-100 mb-3 border-secondary">
                      <img class="card-img-top image_item" src="$image" onError="this.onerror=null;this.src='../img/default.png';">
                      <div class="card-body">
                        <h7 class="card-title"> {$isReserved} </h7>
                        <p class="card-text">{$description}</p>
                        <h4 class="card-text">$tarif</h4>
                      </div>
                      
                      <footer class="bouton_footer text-center">
                           <a href="$url_item" class="btn btn-primary">Voir item</a>
                           $bouton    
                           
                      </footer>
                    </div>
                </div>
                FIN;
                $count_bloc_line++;
            }
            $html_items .= "</div></div>";
        }

        //Ajout des messages à la page
        $html_messages ="";
        foreach ($messages as $message) {
            $html_messages .= <<<FIN
        <div class="card card_form">
            <div class="card-header">
               Message de {$message['auteur']} :
            </div>
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                    <footer class="blockquote-footer">{$message['message']}</footer>
                </blockquote>
            </div>
        </div>
        FIN;
        }

        $url_reservationItem = $this->container->router->pathFor("afficherFormMessage", ['token' => $l['token']]);
        $html_items = $html_infosListe .  $html_items . $html_messages . "<br>";
        return $html_items;
    }

    public function formMessage() : string{
        $l = $this->tab[0];
        $url_messageListe = $url_modification = $this->container->router->pathFor("formMessageListe", ['token' => $l['token']]);
        $html = <<<FIN
        <div class="card" id="list_form">
            <div class="card-header text-center">
                Ajouter un message sur '{$l['titre']}'
            </div>
            <div class="card-body">
                <form method="POST" action="$url_messageListe">
                    <div class="form-group">
                        <label for="form_nom" >Votre nom :</label>
                        <input type="text" class="form-control" id="form_login" placeholder="Jean, Paul, Gauthier..." name="nom" required>
                    </div>   
                    <div class="form-group">
                        <label for="form_message" >Votre message :</label>
                        <input type="text" class="form-control" id="form_message" placeholder="Message à ajouter.." name="message">
                    </div> 
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Publier le message</button>
                    </div>
                </form> 
            </div>
        </div>   
        FIN;
        return $html;
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
            // Erreur quand le token ne correspond à aucune liste
            case 0 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\">La token saisi ne correspond à aucune liste</div>";
            }
            // affichage des listes
            case 1 :
            {
                $current_page = "Participer";
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
            // affichage du formulaire pour le message sur la liste
            case 3 :
            {
                $path = "../../";
                $listeTitre = $this->tab[0]['titre'];
                $listeToken = $this->tab[1];
                $content .= $this->formMessage();
                $url_listeActive = $this->container->router->pathFor("afficherListeParticipant", ['token' => $listeToken]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_participer\">Participer</a></li>";
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_listeActive\">$listeTitre</a></li>";
                $current_page = "Message";
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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="{$path}js/main.js"></script>
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

    <div class="vueParticipant">
        $content
    </div>
    
</body>
</html>
FIN;
        return $html;
    }
}