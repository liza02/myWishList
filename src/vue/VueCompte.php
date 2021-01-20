<?php


namespace mywishlist\vue;

/**
 * Class VueCompte
 * @package mywishlist\vue
 */
class VueCompte {

    private $tab;
    private $container;

    /**
     * VueCompte constructor.
     * @param $tab
     * @param $container
     */
    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    /**
     * Formulaire d'inscription
     * @return string
     */
    private function formInscription() : string {
        // fonction pour enregistrer le formulaire
        $url_enregistrerInscription = $this->container->router->pathFor( 'enregistrerInscription' ) ;
        // proposition de redirection vers une connexion si on possède deja un compte
        $url_redirConnexion = $this->container->router->pathFor('connexion');
        $html = <<<FIN
        <div class="card card_form">
            <div class="card-header text-center">
                Inscrivez vous !
            </div>
            <div class="card-body">
                <form method="POST" action="$url_enregistrerInscription">
                    <div class="form-group">
                        <label for="form_nom" >Nom</label>
                        <input type="text" class="form-control" id="form_nom" placeholder="Rzepka" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="form_prenom" >Prénom</label>
                        <input type="text" class="form-control" id="form_prenom" placeholder="Thomas" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="form_login" >Login</label>
                        <input type="text" class="form-control" id="form_login" placeholder="thomasRz" name="login" required>
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Mot de passe</label>
                        <input type="password" class="form-control" id="form_pass" placeholder="Mot de passe" name="pass" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary text-center">Enregistrer le login</button>
                    </div>
                 
                </form>    
            </div>
            <div class="card-footer text-center" > 
                Déjà un compte ? <a href="$url_redirConnexion"> Se connecter </a>
            </div>
        </div>
        FIN;
        return $html;
    }

    /**
     * Formulaire de connexion
     * @return string
     */
    private function formConnexion() : string {
        // fonction pour envoyer le formulaire de connexion, et tester si id et mdp sont corrects
        $url_testConnexion = $this->container->router->pathFor( 'testConnexion' ) ;
        // redirection vers le formulaire d'inscription si on ne possède pas encore de compte
        $url_redirInscription = $this->container->router->pathFor('inscription');
        $html = <<<FIN
        <div class="card card_form">
            <div class="card-header text-center">
                Connectez vous !
            </div>
            <div class="card-body">
                <form method="POST" action="$url_testConnexion">
                    <div class="form-group">
                        <label for="form_login" >Login</label>
                        <input type="text" class="form-control" id="form_login" placeholder="thomasRz" name="login" required>
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Mot de passe</label>
                        <input type="password" class="form-control" id="form_nom" placeholder="Mot de passe" name="pass" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form> 
            </div>
            <div class="card-footer text-center" > 
                Pas encore de compte ? <a href="$url_redirInscription"> En créer un </a>
            </div>
        </div>   
        FIN;
        return $html;
    }

    /**
     * Afficage des information du compte
     * @return string
     */
    public function afficherInformations() : string{
        $url_modifier = $this->container->router->pathFor('modifierCompte');
        $url_changemdp = $this->container->router->pathFor('changerMotDePasse');
        $url_deconnexion = $this->container->router->pathFor('deconnexion');
        $url_supprimerCompte = $this->container->router->pathFor('supprimerCompte');
        $html = "";
        $nom = $this->tab['nom'];
        $prenom = $this->tab['prenom'];
        $login = $this->tab['login'];
        if ($this->tab['email'] != null) {
            $email = $this->tab['email'];
        } else {
            $email = "Pas encore d'email enregistré";
        }
        $supprimer = "<button type=\"button\" class=\"btn btn-secondary\" data-toggle=\"modal\" data-target=\"#confirmationSupp_{$login}\"><span class=\"fa fa-trash fa-lg\"></span> Supprimer le compte</button>";
//        $email = "Pas encore d'email enregistré";
        $html = <<<FIN
        <div class="card card_form" xmlns="http://www.w3.org/1999/html">
            <div class="card-header text-center">
                Mes informations
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group row">
                        <label for="form_prenom" class="col-sm-2 col-form-label">   Prénom :</label>
                        <div class="col-sm-4">
                            <input readonly type="text" class="form-control" id="form_prenom" placeholder="{$prenom}" name="prenom" required>
                        </div>
                        <label for="form_nom" class="col-sm-2 col-form-label">   Nom :</label>
                        <div class="col-sm-4">
                            <input readonly type="text" class="form-control" id="form_nom" placeholder="{$nom}" name="nom" required>
                        </div>
                    </div>
                        
                    <div class="form-group row">
                        <label for="form_login" class="col-sm-2 col-form-label">Login :</label>
                        <div class="col-sm-10">
                            <input readonly type="text" class="form-control" id="form_login" placeholder="{$login}" name="login" required>
                        </div>
                    </div>
                    
                   <div class="form-group row">
                        <label for="form_login" class="col-sm-2 col-form-label">Email :</label>
                        <div class="col-sm-10">
                            <input readonly type="text" class="form-control" id="form_login" placeholder="{$email}" name="login" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <a type="submit" class="btn btn-primary" href="$url_modifier" role="button">Modifier mes informations</a>
                        <a type="submit" class="btn btn-warning" href="$url_changemdp" role="button">Changer mon mot de passe</a>
                    </div>
                </form> 
            </div>
        </div>  
        <div class="text-center deconnexion">
            <a href='$url_deconnexion' class="btn btn-danger text-center">Déconnexion</a> 
            $supprimer
        </div>
        <div class="modal fade" id="confirmationSupp_{$login}" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="confirmation">Etes-vous sûr de vouloir supprimer ce compte ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                Login : {$login}
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <a type="button" href="$url_supprimerCompte" class="btn btn-danger">Supprimer</a>
                              </div>
                            </div>
                          </div>
                        </div> 
        
        FIN;
        return $html;
    }

    /**
     * Formulaire de modification des informations du compte
     * @return string
     */
    public function modifierInformations() {
        $url_enregistrerModif = $this->container->router->pathFor( 'enregistrerModif' ) ;
        $html = "";
        $nom = $this->tab['nom'];
        $prenom = $this->tab['prenom'];
        $login = $this->tab['login'];
        if ($this->tab['email'] != null) {
            $email = $this->tab['email'];
        } else {
            $email = "";
        }
//        $email = "Pas encore d'email enregistré";
        $html = <<<FIN
        <div class="card card_form">
            <div class="card-header text-center">
                Modifiez vos informations !
            </div>
            <div class="card-body">
                <form method="POST" action="$url_enregistrerModif">
                    <div class="form-group row">
                        <label for="form_prenom" class="col-sm-2 col-form-label">   Prénom :</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="form_prenom" placeholder="Prénom" name="prenom" value="{$prenom}" required>
                        </div>
                        <label for="form_nom" class="col-sm-2 col-form-label">   Nom :</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="form_nom" placeholder="Nom" value="{$nom}" name="nom" required>
                        </div>
                    </div>
                        
                    <div class="form-group row">
                        <label for="form_login" class="col-sm-2 col-form-label">Login :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="form_login" placeholder="Login" value="{$login}" name="login" required>
                        </div>
                    </div>
                    
                   <div class="form-group row">
                        <label for="form_login" class="col-sm-2 col-form-label">Email :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="form_login" placeholder="Email" value="{$email}" name="email">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer mes informations</button>
                    </div>
                </form> 
            </div>
        </div>   
        
        FIN;
        return $html;
    }

    /**
     * Formulaire de modification du mot de passe
     * @return string
     */
    public function changerMotDePasse() :string{
        $url_enregistrerMdp = $this->container->router->pathFor( 'enregistrerMotDePasse' ) ;
        $html = <<<FIN
        <div class="card card_form">
            <div class="card-header text-center">
                Modifiez votre mot de passe
            </div>
            <div class="card-body">
                <form method="POST" action="$url_enregistrerMdp">
                    <div class="form-group">
                        <label for="form_login" >Ancien mot de passe</label>
                        <input type="password" class="form-control" id="form_login" placeholder="Ancien mot de passe" name="ancienMDP" required>
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="form_nom" placeholder=" Nouveau mot de passe" name="nouveauMDP" required>
                    </div>
                    <div class="form-group">
                        <label for="form_pass" >Confirmez le mot de passe</label>
                        <input type="password" class="form-control" id="form_nom" placeholder="Nouveau mot de passe" name="confirmerMDP" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
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
        $url_accueil = $this->container->router->pathFor('racine');
        $url_item = $this->container->router->pathFor('participer');
        $url_deconnexion = $this->container->router->pathFor('deconnexion');
        $content = "";
        $pathIntermediaire="";
        $path = "";

        // pas le même état si l'utilisateur est connecté ou non
        if (isset($_SESSION['profile']['username'])){
            // l'utilisateur est connecté
            // le bouton affiche Mon Compte
            $connected = "Mon Compte";
            // le bouton redirige vers l'affichage du compte
            $url_compte = $this->container->router->pathFor('afficherCompte');
            // le bouton pour accéder aux listes mène aux listes
            $url_liste = $this->container->router->pathFor('afficherMesListes');
        }else{
            // l'utilisateur n'est pas connecté
            // le bouton affiche Connexion
            $connected = "Connexion";
            // le bouton redirige vers le formulaire de connexion
            $url_compte = $this->container->router->pathFor('connexion');
            // le bouton pour accéder aux listes mène au formulaire de connexion, on ne peux pas accéder à ses listes si on est pas connecté
            $url_liste = $this->container->router->pathFor('connexion');
        }

        switch ($select) {
            //connexion echec: message d'erreur + réaffichage du formulaire de connexion
            case 0 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Mot de passe incorrect !</div>";
            }
            //connexion: formulaire de connexion
            case 1 :
            {
                $current_page = "Connexion";
                $content .= $this->formConnexion();
                break;
            }
            // inscription echec: message d'erreur + réaffichage du formulaire d'inscription
            case 2 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Echec de l'inscription ! Ce login existe déjà</div>";
            }
            //inscription: formulaire d'inscription
            case 3 :
            {
                $current_page = "Inscription";
                $content .= $this->formInscription();
                break;
            }
            //accès au compte apres inscription
            case 4 :
            {
                $content = "<div class=\"alert alert-success\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Inscription réussie ! Login <b> {$this->tab['login']} </b> enregistré</div>";
            }
            //accès au compte apres connexion
            case 5 :
            {
                $content .= 'Bienvenue dans votre espace personnel, <b>' . $this->tab['prenom'] . '.</b>';
                $content .= $this->afficherInformations();
                $current_page = "Espace personnel";
                break;
            }
            // modification des info du compte
            case 6 :
            {
                $path = "../";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_compte\">Espace personnel</a></li>";
                $content .= $this->modifierInformations();
                $current_page = "Modifier mon compte";
                break;
            }
            // modification succes
            case 7 :
            {
                $content = "<div class=\"alert alert-success\" role=\"alert\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Modifications enregistrées !</div>";
                $content .= 'Bienvenue dans votre espace personnel, <b>' . $this->tab['prenom'] . '.</b>';
                $content .= $this->afficherInformations();
                $current_page = "Espace personnel";
                break;
            }
            // modification echec login
            case 8 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Echec de la modification ! Le login existe déjà</div>";
                $content .= 'Bienvenue dans votre espace personnel, <b>' . $this->tab['prenom'] . '.</b>';
                $content .= $this->afficherInformations();
                $current_page = "Espace personnel";
                break;
            }
            // modication echec email
            case 9 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Echec de la modification ! L'email existe déjà</div>";
                $content .= 'Bienvenue dans votre espace personnel, <b>' . $this->tab['prenom'] . '.</b>';
                $content .= $this->afficherInformations();
                $current_page = "Espace personnel";
                break;
            }
            // modification mot de passe
            case 10 :
            {
                $path = "../";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_compte\">Espace personnel</a></li>";
                $content .= $this->changerMotDePasse();
                $current_page = "Modifier mon mot de passe";
                break;
            }
            // modification mot de passe echec ancienMDP incorrect
            case 11 :
            {
                $path = "../";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_compte\">Espace personnel</a></li>";
                $content.= "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> L'ancien mot de passe est incorrect !</div>";
                $content .= $this->changerMotDePasse();
                $current_page = "Modifier mon mot de passe";
                break;
            }
            // modification mot de passe echec confirmationMDP différent nouveauMDP
            case 12 :
            {
                $path = "../";
                $pathIntermediaire = "<li class=\"breadcrumb-item \" aria-current=\"page\"><a href=\"$url_compte\">Espace personnel</a></li>";
                $content.= "<div class=\"alert alert-danger\" role=\"alert\"<i class=\"fa fa-times\" aria-hidden=\"true\"></i> >Les deux mots de passe sont différents !</div>";
                $content .= $this->changerMotDePasse();
                $current_page = "Modifier mon mot de passe";
                break;
            }
        }
                $html = <<<FIN
<!DOCTYPE html>
<html>
<head>
    <title>MyWishList</title>
    <link rel="stylesheet" href="{$path}css/style.css">
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
                <li class="nav-item"><a class="nav-link" href="$url_item">Participer à une liste</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_liste">Gérer mes listes</a></li>
                <li class="nav-item"><a class="nav-link active" href="$url_compte">$connected</a></li>
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

            $content

</body>
   
</html>
FIN;
        return $html;
    }
}