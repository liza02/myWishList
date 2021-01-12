<?php


namespace mywishlist\vue;


class VueListe
{
    private $tab;
    private $container;
    private $today;

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

    public function afficherMesListes() : string{
        // pour afficher 3 blocs par ligne, on compte les blocs
        $count_bloc_line = 0;
        $html = "<h3>Mes Listes :</h3><br>";
        $html.= "<div class=\"blocs_listes\">";
        $html .="<div class=\"card-deck blocs_listes\">";
        $tableauListe = array();
        foreach($this->tab as $liste){
            $tableauListe[] = array($liste);
            if ($count_bloc_line == 3) {
                // si 3 blocs sont deja affichés, ou fait une nouvelle ligne
                $html .="</div>";
                $html .="<div class=\"card-deck blocs_listes\">";
                $count_bloc_line=0;
            }
            $date = date('Y-m-d',strtotime($liste['expiration']));
            if ($date >= $this->today) {
                if ($date == $this->today) {
                    $date = "Aujourd'hui";
                }
                else {
                    $date = date('d/m/Y',strtotime($liste['expiration']));
                }

                $token = $liste['token'];
                $url_liste = $this->container->router->pathFor("aff_liste", ['token' => $token]);
                $url_supprimer = $this->container->router->pathFor("supprimerListe", ['token' => $token]);
                $html .= <<<FIN
                <div class="card border-info mb-3" >
                    <div class="card-header text-center">
                        <p>{$liste['titre']}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Description: {$liste['description']}</p>
                        <div class="text-center">
                            <a type="submit" class="btn btn-primary" href="$url_liste" role="button">Accéder</a>
                            <a type="submit" class="btn btn-warning" href="#" role="button"><span class="fa fa-pencil"></span> Modifier</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmationSupp"><span class="fa fa-trash fa-lg"></span> Supprimer</button>
                        </div>
                        
                        <!-- Modal pour demander si on veut supprimer -->
                        <div class="modal fade" id="confirmationSupp" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
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
                var_dump($liste['titre']);
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
                $url_liste = $this->container->router->pathFor("aff_liste", ['token' => $token]);
                $url_supprimer = $this->container->router->pathFor("supprimerListe", ['token' => $token]);
                $html .= <<<FIN
                <div class="card border-info mb-3" >
                    <div class="card-header text-center">
                        <p>{$liste['titre']}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Description: {$liste['description']}</p>
                        <div class="text-center">
                            <a type="submit" class="btn btn-primary" href="$url_liste" role="button">Accéder</a>
                             <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmationSupp"><span class="fa fa-trash fa-lg"></span> Supprimer</button>
                        </div>
                        
                        <!-- Modal pour demander si on veut supprimer -->
                        <div class="modal fade" id="confirmationSupp" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
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
        if ($html == "<h3>Mes Listes expirées :</h3><br>") {
            $html .= "<p> Aucune liste n'est arrivée à expiration...</p>";
        }
        return $html;
    }

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
                        <input type="text" class="form-control" id="form_nom" placeholder="Pour qui est la liste, en quelle occasion... ?" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Date d'expiration</label>
                        <input type="date" class="form-control" id="form_nom" placeholder="Mot de passe" 
                        name="date" value="$annee-$mois-$jour" min="2020-01-01" max="2030-12-31" required>
                    </div>
                    
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="public" id="inlineRadio1" value="true">
                      <label class="form-check-label" for="inlineRadio1">Liste publique</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="public" id="inlineRadio2" value="false">
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

    private function afficherUneListe() : string {
        $l = $this->tab[0][0][0];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $html2 = "";
        $html1 = <<<FIN
        <div class="jumbotron">
            <h1 class="display-4 titre_liste">Ma liste : {$l['titre']}</h1>
            <p class="lead">{$l['description']}</p>
            <hr class="my-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Partagez votre liste</span>
              </div>
              <input type="text" class="form-control" aria-label="url" value="{$actual_link}" id="myInput">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyClipboard()">Copier</button>
              </div>
            </div>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="#" role="button">Ajouter un item</a>
            </p>
        </div>
        FIN;

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

    public function render( int $select ) : string
    {
        $content = "<div id='connected'>Connecté en tant que : "  . $_SESSION['profile']['username'] . "</div>";
        $current_page="";
        $pathIntermediaire ="";
        $path = "";
        $url_accueil= $this->container->router->pathFor('racine');
        $url_item= $this->container->router->pathFor('participer');
        $url_MesListes = $this->container->router->pathFor('afficherMesListes') ;
        $url_compte= $this->container->router->pathFor('afficherCompte');
        $url_creerListe = $this->container->router->pathFor('creerListe') ;
        switch ($select) {
            // affichage des listes
            case 0 :
            {
                $current_page = "Mes Listes";
                $content .= $this->afficherMesListes();
                $content .= "<br><a href='$url_creerListe' class=\"btn btn-info \">Créer une liste</a><br><br>";
                $content .= $this->afficherListesExpirees();
                break;
            }
            // affichage des listes: pas de listes
            case 1 :
            {
                $current_page = "Mes Listes";
                $content .= "<h3>Mes Listes :</h3><br>";

                $content .= "<p> Vous n'avez pas de liste pour l'instant...</p>";
                $content .= "<br><a href='$url_creerListe' class=\"btn btn-info \">Créer une liste</a>";
                break;
            }
            // listes expirée
            case 2 :
            {
                $path = "../";
                $current_page = "Nouvelle liste";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";

                $content .= $this->formCreerListe();
                break;
            }
            // affichage d'une liste
            case 3 :
            {
                $path = "../";
                $l = $this->tab[0][0][0];
                $content .= $this->afficherUneListe();
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_MesListes\">Mes Listes</a></li>";

                $current_page = $l['titre'];
                break;
            }
            // suppression liste
            case 4 :
            {
                //TODO
            }
        }
        $html = $html = <<<FIN
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
                <li class="nav-item"><a class="nav-link" href="$url_compte">Mon Compte</a></li>
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