<?php


namespace mywishlist\vue;


use mywishlist\models\Message;

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
        $message = Message::where('id_parent', '=', $i['id'])->where('type_parent', '=', 'item')->first();
        $image = "../../img/" . $i['img'];
        $reservation = "";
        $isReserved = "<h5><span id='titre_item'>{$i['nom']}</span> <span class=\"badge badge-danger\">RÉSERVÉ par {$i['reserve']}</span></h5>";
        $html_message = "";
        if ($i['reserve'] != "false") {
            $html_message = "<h3>Message de la réservation :</h3><p>{$message['message']}</p>";
        }
        if ($i['reserve'] == "false"){
            $url_reservationItem = $this->container->router->pathFor("reserve_item", ['token' => $l['token'], 'id_item' => $i['id']]);
            $reservation = "<a class=\"btn btn-primary btn-lg\" href=\"$url_reservationItem\" role=\"button\">Réserver l'item</a>";
            $isReserved = "<h5><span id='titre_item'>{$i['nom']}</span> <span class=\"badge badge-success\">PAS ENCORE RÉSERVÉ</span></h5>";
            $html_message = "";
        }

        if ($i['url'] != "") {
            $url =$i['url'];
        } else {
            $url = "Aucun URL disponible";
        }
        $tarif = "<span class=\"badge badge-info\">{$i['tarif']}€</span>";
        $html = <<<FIN
        <div class="box_item">
        
            <div class="card flex-row">
                <div class="card-header bg-transparent border-0">
                    <img src="$image" onError="this.onerror=null;this.src='../../img/default.png';" >
                </div>
                <div class="card-body info_item px-5">
                    <h4 class="card-title">$isReserved</h4>
                    <p class="card-text">{$i['descr']}</p>
                    <p class="card-subtitle mb-2 text-muted">Liste de référence : {$l['titre']}</p>
                    <h2 class="card-text">$tarif</h2>
                    <br>
                    <label for="url" >Ou trouver cet article ?</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend"> 
                        <span class="input-group-text">URL</span>
                      </div>
                      <input readonly type="text" class="form-control" aria-label="url" value="{$url}" id="myInput">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
                      </div>
                    </div>
                    $reservation
                    $html_message
                </div>
               
            </div>
        </div>
        FIN;
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
        // item réservé (par défaut)
        $isReserved = "<h5><span id='titre_item'>{$i['nom']}</span> <span class=\"badge badge-danger\">RÉSERVÉ</span></h5>";
        $modification = "<a class=\"btn btn-warning btn-lg disabled\" href=\"#\" role=\"button\" aria-disabled=\"true\"><span class=\"fa fa-pencil\" ></span> Modifier l'item</a>";
        $supprimer = "<button type=\"button\" class=\"btn btn-lg btn-danger disabled\" data-toggle=\"modal\" data-target=\"#confirmationSupp_{$i['nom']}\"><span class=\"fa fa-trash fa-lg\"></span> Supprimer</button>";
        // on verifie si l'item n'est pas reservé
        if ($i['reserve'] == "false"){
            $url_modification = $this->container->router->pathFor("modifierItem", ['token' => $l['token'], 'id_item' => $i['id']]);
            $modification = "<a class=\"btn btn-warning btn-lg\" href=\"$url_modification\" role=\"button\"><span class=\"fa fa-pencil\" ></span> Modifier l'item</a>";
            $isReserved = "<h5><span id='titre_item'>{$i['nom']}</span> <span class=\"badge badge-secondary\">PAS ENCORE RÉSERVÉ</span></h5>";
            $supprimer = "<button type=\"button\" class=\"btn btn-lg btn-danger\" data-toggle=\"modal\" data-target=\"#confirmationSupp_{$i['nom']}\"><span class=\"fa fa-trash fa-lg\"></span> Supprimer</button>";
        }
        // on verifie si l'item possède un url pour l'acheter sur un site externe
        if ($i['url'] != "") {
            $url =$i['url'];
        } else {
            $url = "Aucun URL disponible";
        }
        $tarif = "<span class=\"badge badge-info\">{$i['tarif']}€</span>";
        $url_supprimer = $this->container->router->pathFor("supprimerItem", ['token' => $l['token'], 'id_item' => $i['id']]);
        $html = <<<FIN
        <div class="box_item">
        
            <div class="card flex-row">
                <div class="card-header bg-transparent border-0">
                    <img src="$image" onError="this.onerror=null;this.src='../../img/default.png';" >
                </div>
                <div class="card-body info_item px-5">
                    <h4 class="card-title">$isReserved</h4>
                    <p class="card-text">{$i['descr']}</p>
                    <h2 class="card-text">$tarif</h2>
                    <br>
                    <label for="url" >Ou trouver mon article ?</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend"> 
                        <span class="input-group-text">URL</span>
                      </div>
                      <input readonly type="text" class="form-control" aria-label="url" value="{$url}" id="myInput">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
                      </div>
                    </div>
                    $modification
                    $supprimer
                    <!-- Modal pour demander si on veut supprimer -->
                        <div class="modal fade" id="confirmationSupp_{$i['nom']}" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="confirmation">Etes-vous sûr de vouloir supprimer cet item ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                {$i['nom']}
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <a type="button" href="$url_supprimer" class="btn btn-danger">Supprimer</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                </div>
               
            </div>
        </div>
        FIN;
        return $html;
    }

    /**
     * Formulaire de réservation
     * @return string
     */
    public function formReservation() : string{
        $i = $this->tab[0][0];
        $l = $this->tab[1][0];
        $url_reserv_item = $url_modification = $this->container->router->pathFor("formReserveItem", ['token' => $l['token'], 'id_item' => $i['id']]);
        $html = <<<FIN
        <div class="card" id="list_form">
            <div class="card-header text-center">
                Réserver l'item '{$i['nom']}'
            </div>
            <div class="card-body">
                <form method="POST" action="$url_reserv_item">
                    <div class="form-group">
                        <label for="form_nom" >Votre nom :</label>
                        <input type="text" class="form-control" id="form_login" placeholder="Jean, Paul, Gauthier..." name="nom" required>
                    </div>   
                    <div class="form-group">
                        <label for="form_message" >Votre message (optionnel) :</label>
                        <input type="text" class="form-control" id="form_message" placeholder="Remarques éventuelles" name="message">
                    </div> 
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Réserver l'item</button>
                    </div>
                </form> 
            </div>
        </div>   
        FIN;
        return $html;
    }

    /**
     * Formulaire de modification
     * @return string
     */
    public function formModification() : string{
        $i = $this->tab[0][0];
        $l = $this->tab[1][0];
        $url_modif_item = $url_modification = $this->container->router->pathFor("formModifierItem", ['token' => $l['token'], 'id_item' => $i['id']]);
        $html = <<<FIN
        <div class="card" id="list_form">
            <div class="card-header text-center">
                Modifier l'item '{$i['nom']}'
            </div>
            <div class="card-body">
                <form method="POST" action="$url_modif_item">
                    <div class="form-group">
                        <label for="form_nom" >Titre</label>
                        <input type="text" class="form-control" id="form_login" placeholder="{$i['nom']}" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="form_description" >Description</label>
                        <input type="text" class="form-control" id="form_description" placeholder="{$i['descr']}" name="description">
                    </div>
                    <div class="form-group">
                        <label for="form_url" >URL</label>
                        <input type="text" class="form-control" id="form_url" placeholder="{$i['url']}" name="url">
                    </div>
                    <div class="form-group"> 
                        <label for="form_prix" >Prix</label>
                        <input type="text" class="form-control" id="form_prix" aria-label="Amount (to the nearest dollar)" placeholder="{$i['tarif']}" name="prix">
                    </div>
                        
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form> 
            </div>
        </div>   
        FIN;
        return $html;
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
        $url_accueil = $this->container->router->pathFor('racine');
        $url_participer = $this->container->router->pathFor('participer');
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
                $content .="<div class=\"alert alert-success\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Réservation enregistrée !</div>";
            }
            // afficher l'item en tant que participant
            case 1 :
            {
                $path = "../../";
                $linkactif = <<<FIN
<li class="nav-item"><a class="nav-link active" href="$url_participer">Participer à une liste</a></li>
<li class="nav-item"><a class="nav-link" href="$url_liste">Gérer mes listes</a></li>
FIN;
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
                $content = "<div class=\"alert alert-success\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Modifications enregistrée !</div>";
            }
            // afficher l'item en tant que createur
            case 3 :
            {
                $path = "../../";
                $linkactif = <<<FIN
<li class="nav-item"><a class="nav-link" href="$url_participer">Participer à une liste</a></li>
<li class="nav-item"><a class="nav-link active" href="$url_liste">Gérer mes listes</a></li>
FIN;

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
                $linkactif = <<<FIN
<li class="nav-item"><a class="nav-link active" href="$url_participer">Participer à une liste</a></li>
<li class="nav-item"><a class="nav-link" href="$url_liste">Gérer mes listes</a></li>
FIN;
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
                $linkactif = <<<FIN
<li class="nav-item"><a class="nav-link" href="$url_participer">Participer à une liste</a></li>
<li class="nav-item"><a class="nav-link active" href="$url_liste">Gérer mes listes</a></li>
FIN;
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
        $html = <<<FIN
<!DOCTYPE html>
<html>
<head>
    <title>MyWishList</title>
    <link rel="stylesheet" href="{$path}css/style.css">
    <script src="{$path}js/main.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
                $linkactif
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

    <div class="">
        $content
    </div>
    
</body>
</html>
FIN;

        return $html;
    }
}