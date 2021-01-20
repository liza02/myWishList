<?php


namespace mywishlist\vue;

use mywishlist\models\Message;

/**
 * Class VueListe
 * @package mywishlist\vue
 */
class VueListe {

    private $tab;
    private $container;
    private $today;

    /**
     * VueListe constructor.
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
     * Methode qui retourne nos listes non expirées
     * @return string
     */
    public function afficherMesListes() : string{
        // pour afficher 3 blocs par ligne, on compte les blocs
        $count_bloc_line = 0;
        $html = "<h3>Mes Listes :</h3><br>";
        $html.= "<div class=\"blocs_listes\">";
        $html .="<div class=\"card-deck blocs_listes\">";
        foreach($this->tab as $liste){
            if ($count_bloc_line == 3) {
                // si 3 blocs sont deja affichés, ou fait une nouvelle ligne
                $html .="</div>";
                $html .="<div class=\"card-deck blocs_listes\">";
                $count_bloc_line=0;
            }
            $date = date('Y-m-d',strtotime($liste['expiration']));
            // on verifie que la liste n'est pas expirée
            if ($date >= $this->today) {
                // si la liste expire aujourd'hui on l'écris explicitement
                if ($date == $this->today) {
                    $date = "Aujourd'hui";
                }
                else {
                    $date = date('d/m/Y',strtotime($liste['expiration']));
                }
                // on place le badge publique ou privé
                if ($liste['public'] == "true"){
                    $public = "<span class=\"badge badge-success\">PUBLIQUE</span>";
                } else {
                    $public = "<span class=\"badge badge-secondary\">PRIVÉE</span>";
                }
                // si la description est trop longue on la coupe et termine par "..."
                // dans l'affichage détaillé de la liste la description n'est pas coupé
                if (strlen($liste['description']) >= 120) {
                    $description = substr($liste['description'], 0, 120) . "...";
                } else {
                    $description = $liste['description'];
                }

                $token = $liste['token'];
                $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
                $url_supprimer = $this->container->router->pathFor("supprimerListe", ['token' => $token]);
                $url_mofifier = $this->container->router->pathFor("modifierListe", ['token' => $token]);
                $html .= <<<FIN
                <div class="card border-info mb-3" >
                    <div class="card-header text-center">
                        <p>{$liste['titre']}  {$public}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Description: $description</p>
                        <div class="text-center">
                            <a type="submit" class="btn btn-primary" href="$url_liste" role="button"> Accéder</a>
                            <a type="submit" class="btn btn-warning" href="$url_mofifier" role="button"><span class="fa fa-pencil"></span> Modifier</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmationSupp_{$liste['titre']}"><span class="fa fa-trash fa-lg"></span> Supprimer</button>
                        </div>
                        
                        <!-- Modal pour demander si on veut supprimer -->
                        <div class="modal fade" id="confirmationSupp_{$liste['titre']}" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="confirmation">Etes-vous sûr de vouloir supprimer cette liste ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                {$liste['titre']}
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <a type="button" href="$url_supprimer" class="btn btn-danger">Supprimer</a>
                              </div>
                            </div>
                          </div>
                        </div>

                        
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Date d'expiration : $date</small>
                    </div>
                </div>
                FIN;
                $count_bloc_line++;
            }
        }
        $html .= "</div>";
        $html .= "</div>";
        if ($html == "<h3>Mes Listes :</h3><br><div class=\"blocs_listes\"><div class=\"card-deck blocs_listes\"></div></div>") {
            $html .= "<p> Vous n'avez pas de liste pour l'instant...</p>";
        }
        return $html;
    }

    /**
     * Méthode qui affiche non listes expirées
     * @return string
     */
    public function afficherListesExpirees() : string{
        $count_bloc_line = 0;
        $html = "<h3>Mes Listes expirées :</h3><br>";
        $html.= "<div class=\"blocs_listes\">";
        $html .="<div class=\"card-deck blocs_listes\">";
        foreach($this->tab as $liste){
            if ($count_bloc_line == 3) {
                // si 3 blocs sont deja affichés, ou fait une nouvelle ligne
                $html .="</div>";
                $html .="<div class=\"card-deck blocs_listes\">";
                $count_bloc_line=0;
            }
            $date = date('Y-m-d',strtotime($liste['expiration']));
            if ($date < $this->today) {
                $date = date('d/m/Y',strtotime($liste['expiration']));
                $token = $liste['token'];
                if ($liste['public'] == "true"){
                    $public = "<span class=\"badge badge-success\">PUBLIQUE</span>";
                } else {
                    $public = "<span class=\"badge badge-secondary\">PRIVÉE</span>";
                }
                if (strlen($liste['description']) >= 120) {
                    $description = substr($liste['description'], 0, 120) . "...";
                } else {
                    $description = $liste['description'];
                }
                $url_liste = $this->container->router->pathFor("aff_maliste", ['token' => $token]);
                $url_supprimer = $this->container->router->pathFor("supprimerListe", ['token' => $token]);
                $html .= <<<FIN
                <div class="card border-info mb-3" >
                    <div class="card-header text-center">
                        <p>{$liste['titre']}  {$public}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Description: {$description}</p>
                        <div class="text-center">
                            <a type="submit" class="btn btn-primary" href="$url_liste" role="button">Accéder</a>
                             <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmationSupp_{$liste['titre']}"><span class="fa fa-trash fa-lg"></span> Supprimer</button>
                        </div>
                        
                        <!-- Modal pour demander si on veut supprimer -->
                        <div class="modal fade" id="confirmationSupp_{$liste['titre']}" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="confirmation">Etes-vous sûr de vouloir supprimer cette liste ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                {$liste['titre']}
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <a type="button" href="$url_supprimer" class="btn btn-danger">Supprimer</a>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Date d'expiration : $date</small>
                    </div>
                </div>
                FIN;
                $count_bloc_line++;
            }
        }
        $html .= "</div>";
        $html .= "</div>";
        if ($html == "<h3>Mes Listes expirées :</h3><br><div class=\"blocs_listes\"><div class=\"card-deck blocs_listes\"></div></div>") {
            $html .= "<p> Aucune liste n'est arrivée à expiration...</p>";
        }
        return $html;
    }

    /**
     * Méthode qui affiche le formulaire de création de liste
     * @return string
     */
    public function formCreerListe() : string {
        $url_new_liste = $this->container->router->pathFor( 'enregistrerListe' ) ;
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
        <div class="card" id="list_form">
            <div class="card-header text-center">
                Nouvelle liste
            </div>
            <div class="card-body">
                <form method="POST" action="$url_new_liste">
                    <div class="form-group">
                        <label for="form_login" >Titre</label>
                        <input type="text" class="form-control" id="form_login" placeholder="anniversaire, noël..." name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Description</label>
                        <input type="text" class="form-control" id="form_nom" placeholder="Pour qui est la liste, en quelle occasion... ?" name="description">
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Date d'expiration</label>
                        <input type="date" class="form-control" id="form_nom" placeholder="Mot de passe" 
                        name="date" value="$annee-$mois-$jour" min="$annee-$mois-$jour" max="2030-12-31" required>
                    </div>
                    
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="public" id="inlineRadio1" value="true">
                      <label class="form-check-label" for="inlineRadio1">Liste publique</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="public" id="inlineRadio2" value="false" checked>
                      <label class="form-check-label" for="inlineRadio2">Liste privée</label>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer la liste</button>
                    </div>
                </form> 
            </div>
        </div>   
        FIN;
        return $html;
    }

    /**
     * Méthode qui affiche une liste en tant qu'admin de celle-ci
     * @return string
     */
    private function afficherUneListe() : string {
        // Recuperation de la liste dans l'array
        $l = $this->tab[0][0][0];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html_items = "";
        $date = date('Y-m-d',strtotime($l['expiration']));
        $url_modifier = $this->container->router->pathFor("modifierListe", ['token' => $l['token']]);
        $url_ajoutItem = $this->container->router->pathFor("ajoutItem", ['token' => $l['token']]);

        $ajoutItem = "<a class=\"btn btn-primary btn-lg\" href=\"$url_ajoutItem\" role=\"button\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i> Ajouter un item</a>";
        $modifierListe = "<a type=\"submit\" class=\"btn btn-warning\" href=\"$url_modifier\" role=\"button\"><span class=\"fa fa-pencil\"></span> Modifier</a>";
        $expired="";

        // si la liste est expirée on ne propose pas l'ajout d'items ou la suppression
        if ($date < $this->today) {
            $ajoutItem = "";
            $modifierListe = "";
            $expired = "<div style='color: red'> Cette liste est expirée !</div>";
        }

        // affichage des infos générales de la liste: titre, description, boutons
        $html_infosListe = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">Ma liste : {$l['titre']}</h1>
            <p class="lead">{$l['description']}</p>
            <hr class="my-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Partagez votre liste</span>
              </div>
              <input readonly type="text" class="form-control" aria-label="url" value="{$actual_link}" id="myInput">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
              </div>
            </div>
            <p class="lead">
               $ajoutItem
               $modifierListe
               $expired
            </p>
        </div>
        FIN;
        // affichage des items dans des cards dans un grid
        foreach ($this->tab[1] as $tableau){
            $count_bloc_line = 0;
            $html_items .= "<div class=\"container\"> <div class=\"row\">";
            foreach ($tableau as $items){
                $url_item = $this->container->router->pathFor("aff_item_admin", ['id_item' => $items['id'], 'token' => $l['token']]);
                $url_modifier = $this->container->router->pathFor("modifierItem", ['token' => $l['token'], 'id_item' => $items['id']]);
                $testType = explode("/",$items['img']);
                if (count($testType) > 1){
                    $image = $items['img'];
                }else{
                    $image = "../img/" . $items['img'];
                }
                if (strlen($items['descr']) >= 80) {
                    $description = substr($items['descr'], 0, 80) . "...";
                } else {
                    $description = $items['descr'];
                }
                if ($items['reserve'] == "false"){
                    $boutonmodification = "<a type=\"submit\" class=\"btn btn-warning\" href=\"$url_modifier\" role=\"button\"><span class=\"fa fa-pencil\"></span> Modifier</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-success\">DISPONIBLE</span></h7>";
                }else {
                    $boutonmodification = "<a class=\"btn btn-secondary disabled\" href=\"$url_modifier\" role=\"button\" aria-disabled=\"true\"><span class=\"fa fa-pencil\" ></span> Modifier</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-secondary\">RÉSERVÉ</span></h7>";
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
                           $boutonmodification
                      </footer>
                    </div>
                </div>
                FIN;
                $count_bloc_line++;
            }
            $html_items .= "</div></div>";
        }

        //Ajout des messages à la page
        $messages = Message::where('id_parent', '=', $l['no'])->where('type_parent', '=', 'liste')->get()->toArray();
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

        $html_items = $html_infosListe .  $html_items . $html_messages . "<br>";
        return $html_items;
    }

    private function afficherUneListeNonConnecte() : string {
        // Recuperation de la liste dans l'array
        $l = $this->tab[0][0][0];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html_items = "";
        $date = date('Y-m-d',strtotime($l['expiration']));
        $url_modifier = $this->container->router->pathFor("modifierListe", ['token' => $l['token']]);
        $url_ajoutItem = $this->container->router->pathFor("ajoutItem", ['token' => $l['token']]);

        $ajoutItem = "<a class=\"btn btn-primary btn-lg\" href=\"$url_ajoutItem\" role=\"button\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i> Ajouter un item</a>";
        $modifierListe = "<a type=\"submit\" class=\"btn btn-warning\" href=\"$url_modifier\" role=\"button\"><span class=\"fa fa-pencil\"></span> Modifier</a>";
        $expired="";

        // si la liste est expirée on ne propose pas l'ajout d'items ou la suppression
        if ($date < $this->today) {
            $ajoutItem = "";
            $modifierListe = "";
            $expired = "<div style='color: red'> Cette liste est expirée !</div>";
        }

        // affichage des infos générales de la liste: titre, description, boutons
        $html_infosListe = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">Ma liste : {$l['titre']}</h1>
            <p class="lead">{$l['description']}</p>
            <hr class="my-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Partagez votre liste</span>
              </div>
              <input readonly type="text" class="form-control" aria-label="url" value="{$actual_link}" id="myInput">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
              </div>
            </div>
            <p class="lead">
               $expired
            </p>
        </div>
        FIN;
        // affichage des items dans des cards dans un grid
        foreach ($this->tab[1] as $tableau){
            $count_bloc_line = 0;
            $html_items .= "<div class=\"container\"> <div class=\"row\">";
            foreach ($tableau as $items){
                $url_item = $this->container->router->pathFor("aff_item_admin", ['id_item' => $items['id'], 'token' => $l['token']]);
                $url_modifier = $this->container->router->pathFor("modifierItem", ['token' => $l['token'], 'id_item' => $items['id']]);
                $image = "../img/" . $items['img'];
                if (strlen($items['descr']) >= 80) {
                    $description = substr($items['descr'], 0, 80) . "...";
                } else {
                    $description = $items['descr'];
                }
                if ($items['reserve'] == "false"){
                    $boutonmodification = "<a type=\"submit\" class=\"btn btn-warning\" href=\"$url_modifier\" role=\"button\"><span class=\"fa fa-pencil\"></span> Modifier</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-success\">DISPONIBLE</span></h7>";
                }else {
                    $boutonmodification = "<a class=\"btn btn-secondary disabled\" href=\"$url_modifier\" role=\"button\" aria-disabled=\"true\"><span class=\"fa fa-pencil\" ></span> Modifier</a>";
                    $isReserved = "<h7><span class=\"nom_item\">{$items['nom']} </span><span class=\"badge badge-secondary\">RÉSERVÉ</span></h7>";
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
                      </footer>
                    </div>
                </div>
                FIN;
                $count_bloc_line++;
            }
            $html_items .= "</div></div>";
        }

        //Ajout des messages à la page
        $messages = Message::where('id_parent', '=', $l['no'])->where('type_parent', '=', 'liste')->get()->toArray();
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

        $html_items = $html_infosListe .  $html_items . $html_messages . "<br>";
        return $html_items;
    }

    /**
     * Modification de liste
     * @return string
     */
    public function modifierListe() : string{
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
        $url_enregistrerModificationListe = $this->container->router->pathFor("enregistrerModificationListe", ['token' => $this->tab['token']]);
        if ($this->tab['public'] == "true"){
            $html = <<<FIN
        <form method="POST" action="$url_enregistrerModificationListe">
            <div class="form-group">
                <label for="form_login" >Nouveau titre</label>
                <input type="text" class="form-control" id="form_login" placeholder="Nouveau titre :" value="{$this->tab['titre']}" name="titre" required>
            </div>
            <div class="form-group">
                <label for="form_login" >Nouvelle description</label>
                <input type="text" class="form-control" id="form_login" placeholder="Nouvelle description :" value="{$this->tab['description']}" name="description">
            </div>
            <div class="form-group">
                <label for="form_pass" >Nouvelle date d'expiration</label>
                <input type="date" class="form-control" id="form_nom" placeholder="Mot de passe" 
                name="date" value="{$this->tab['expiration']}" min="$annee-$mois-$jour" max="2030-12-31" required>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="public" id="inlineRadio1" value="true" checked>
              <label class="form-check-label" for="inlineRadio1">Liste publique</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="public" id="inlineRadio2" value="false">
              <label class="form-check-label" for="inlineRadio2">Liste privée</label>
            </div>
            <div class="text-center">
               <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>	
        FIN;
        }else{
            $html = <<<FIN
        <form method="POST" action="$url_enregistrerModificationListe">
            <div class="form-group">
                <label for="form_login" >Nouveau titre</label>
                <input type="text" class="form-control" id="form_login" placeholder="Nouveau titre" value="{$this->tab['titre']}" name="titre" required>
            </div>
            <div class="form-group">
                <label for="form_login" >Nouvelle description</label>
                <input type="text" class="form-control" id="form_login" placeholder="Nouvelle description" value="{$this->tab['description']}" name="description">
            </div>
            <div class="form-group">
                <label for="form_pass" >Nouvelle date d'expiration</label>
                <input type="date" class="form-control" id="form_nom" placeholder="Mot de passe" 
                name="date" value="{$this->tab['expiration']}" min="2020-01-01" max="2030-12-31" required>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="public" id="inlineRadio1" value="true">
              <label class="form-check-label" for="inlineRadio1">Liste publique</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="public" id="inlineRadio2" value="false" checked>
              <label class="form-check-label" for="inlineRadio2">Liste privée</label>
            </div>
            <div class="text-center">
               <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>	
        FIN;
        }

        return $html;
    }

    /**
     * Ajouter un item
     * @return string
     */
    public function ajouterUnItem() : string{
        $url_new_liste = $this->container->router->pathFor('enregistrerNouveauItemListe', ['token' => $this->tab['token']]);
        $html = <<<FIN
        <div class="card" id="list_form">
            <div class="card-header text-center">
                Nouvel Item
            </div>
            <div class="card-body">

        <div class="row">
            <div class="col container_img">
                <img id="imageResult" src="#" alt="image de l'item" onError="this.onerror=null;this.src='../../img/default.png';" class="img-fluid rounded shadow-sm">
            </div>
            <div class="col">
                <form method="POST" action="$url_new_liste" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="form_titre" >Titre</label>
                        <input type="text" class="form-control" id="form_titre" placeholder="bouteille d'eau, cerf volant..." name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="form_description" >Description</label>
                        <input type="text" class="form-control" id="form_description" placeholder="A quoi correspond cette item ?" name="descr">
                    </div>
                    <div class="form-group">
                        <label for="form_url" >URL</label>
                        <input type="text" class="form-control" id="form_url" placeholder="Où trouver mon item ?" name="url">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Prix</span>
                        </div>
                        <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="15.50" name="tarif">
                        <div class="input-group-append">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                    <label >Importez votre image !</label>
                    <!--             image       -->
                    <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
                        <input id="upload" type="file" onchange="readURL(this);" class="form-control border-0" name="image">
                        <label id="upload-label" for="upload" class="font-weight-light text-muted">Choisissez une image</label>
                        <div class="input-group-append">
                            <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i class="fa fa-cloud-upload mr-2 text-muted"></i><small class="text-uppercase font-weight-bold text-muted">Choose file</small></label>
                        </div>
                    </div>
                    
                    <!--             image URL -->
                    <div class="form-group">
                        <label for="form_url" > URL Image <b>(optionnel)</b></label>
                        <input type="text" id="url_image" class="form-control" id="form_url" placeholder="URL vers l'image" onchange="" name="url_image">
                    </div>
                    
                    <!--           /image         -->
                    <div class="text-center">
                        <button id="enregistrerItem" type="submit" class="btn btn-primary" onclick="copyFile();">Ajouter un item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    FIN;
        return "$html";
    }

    public function securite () : string{
        $url_redirConnexion = $this->container->router->pathFor('connexion');
        $html = <<<FIN
        <div class="card text-white bg-danger mb-3" style="max-width: 50rem;margin-right: auto;margin-left: auto">
            <div class="card-header text-center">
                <h4>ERREUR !</h4>
            </div>
            <div class="card-body text-center">
                <h4>Vous n'avez pas accès à cette page ! <a href="{$url_redirConnexion}">Connectez vous</a></h4>
            </div>
        </div>

        FIN;
        return $html;
    }


    /**
     * Render
     * @param int $select
     * @return string
     */
    public function render( int $select ) : string
    {
        if (isset($_SESSION['profile'])) {
            $content = "<div id='connected'>Connecté en tant que : "  . $_SESSION['profile']['username'] . "</div>";
            $url_MesListes = $this->container->router->pathFor('afficherMesListes') ;
            $url_compte= $this->container->router->pathFor('afficherCompte');
            $etatConnexion = 'Mon Compte';
        }
        else {
            $content = "<div id='not_connected'>Non connecté</div>";
            $url_MesListes = $this->container->router->pathFor('connexion') ;
            $url_compte= $this->container->router->pathFor('connexion');
            $etatConnexion = 'Connexion';
        }
        $current_page="";
        $pathIntermediaire ="";
        $path = "";
        $url_accueil= $this->container->router->pathFor('racine');
        $url_item= $this->container->router->pathFor('participer');


        $url_creerListe = $this->container->router->pathFor('creerListe') ;
        switch ($select) {
            // modification reussi
            case 0 :
            {
                $content .= "<div class=\"alert alert-success\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Modifications enregistrées !</div>";
            }
            // affichage des listes
            case 1 :
            {
                $current_page = "Mes Listes";
                $content .= $this->afficherMesListes();
                $content .= "<br><a href='$url_creerListe' class=\"btn btn-info \">Créer une liste</a><br><br>";
                $content .= $this->afficherListesExpirees();
                break;
            }
            // affichage des listes: pas de listes
            case 2 :
            {
                $current_page = "Mes Listes";
                $content .= "<h3>Mes Listes :</h3><br>";

                $content .= "<p> Vous n'avez pas de liste pour l'instant...</p>";
                $content .= "<br><a href='$url_creerListe' class=\"btn btn-info \">Créer une liste</a>";
                break;
            }
            // listes expirée
            case 3 :
            {
                $path = "../";
                $current_page = "Nouvelle liste";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";
                $content .= $this->formCreerListe();
                break;
            }
            // bandeau ajout d'item list
            case 4:
            {
                $content .= "<div class=\"alert alert-success\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Item ajouté !</div>";
            }
            // affichage d'une liste
            case 5:
            {
                $path = "../";
                $l = $this->tab[0][0][0];
                if (isset($_SESSION['profile'])) {
                    $content .= $this->afficherUneListe();
                }
                else {
                    $content .= $this->afficherUneListeNonConnecte();
                }
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";
                $current_page = $l['titre'];
                break;
            }
            // modifier la liste
            case 6 :
            {
                $path = "../../";
                $l = $this->tab['titre'];
                $content .= $this->modifierListe();
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";
                $url_liste =$this->container->router->pathFor('aff_maliste', ['token' => $this->tab['token']]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_liste\">{$this->tab['titre']}</a></li>";
                $current_page = "Modification";
                break;
            }
            case 7 :
            {
                $path = "../../";
                $l = $this->tab['titre'];
                $content .= $this->ajouterUnItem();
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";
                $url_liste =$this->container->router->pathFor('aff_maliste', ['token' => $this->tab['token']]);
                $pathIntermediaire .= "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_liste\">{$this->tab['titre']}</a></li>";
                $current_page = "Ajout d'item";
                break;
            }
            case 8:
            {
                $content .= "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Item supprimé !</div>";
                $path = "../";
                $l = $this->tab[0][0][0];
                $content .= $this->afficherUneListe();
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";
                $current_page = $l['titre'];
                break;
            }
            // gestion de la sécurité: si on essaye de mettre l'url de modification d'une liste dont on est
            // pas le créateur
            case 9 :
            {
                $path = "../";
                $current_page = "Oups!";
                $content .= $this->securite();
            }
        }
        $html = $html = <<<FIN
<!DOCTYPE html>
<html>
<head>
    <title>MyWishList</title>
    <link rel="stylesheet" href="{$path}css/style.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="{$path}js/main.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js">
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
                <li class="nav-item"><a class="nav-link" href="$url_item">Participer à une liste</a></li>
                <li class="nav-item"><a class="nav-link active" href="$url_MesListes">Gérer mes listes</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_compte">$etatConnexion</a></li>
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

    <div class ="vueListe">
        $content
    </div>
    
</body>
</html>
FIN;
        return $html;
    }
}