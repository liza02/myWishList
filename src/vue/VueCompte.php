<?php


namespace mywishlist\vue;


class VueCompte
{
    private $tab;
    private $container;

    public function __construct($tab, $container){
        $this->tab = $tab;
        $this->container = $container;
    }

    private function formlogin() : string {
        $url_nouveaulogin = $this->container->router->pathFor( 'nouveaulogin' ) ;
        $url_compte = $this->container->router->pathFor('compte');
        $html = <<<FIN
        <div class="card card_form">
            <div class="card-header text-center">
                Inscrivez vous !
            </div>
            <div class="card-body">
                <form method="POST" action="$url_nouveaulogin">
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
                        <input type="password" class="form-control" id="form_nom" placeholder="Mot de passe" name="pass" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary text-center">Enregistrer le login</button>
                    </div>
                 
                </form>    
            </div>
            <div class="card-footer text-center" > 
                Déjà un compte ? <a href="$url_compte"> Se connecter </a>
            </div>
        </div>
        FIN;
        return $html;
    }

    private function testform() : string {
        $url_testpass = $this->container->router->pathFor( 'testpass' ) ;
        $url_formlogin = $this->container->router->pathFor('formlogin');
        $html = <<<FIN
        <div class="card card_form">
            <div class="card-header text-center">
                Connectez vous !
            </div>
            <div class="card-body">
                <form method="POST" action="$url_testpass">
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
                Pas encore de compte ? <a href="$url_formlogin"> En créer un </a>
            </div>
        </div>   
        FIN;
        return $html;
    }

    public function render( int $select ) : string
    {

        $url_accueil = $this->container->router->pathFor('racine');
        $url_item = $this->container->router->pathFor('item');
        $url_liste = $this->container->router->pathFor('liste');
        $url_deconnexion = $this->container->router->pathFor('deconnexion');
        $content = "";


        if (isset($_SESSION['profile']['username'])){
            $connected = "Mon Compte";
            $url_compte = $this->container->router->pathFor('compte');
        }else{
            $connected = "Connexion";
            $url_compte = $this->container->router->pathFor('connexion');
        }

        switch ($select) {

            case 0 :
            {
                $path = "";
                $current_page = "Connexion";

                //TODO
                if (isset($this->tab['login'])){
                    $content = 'Login <b>' . $this->tab['login'] . '</b> enregistré'."<br>";
                }
                $content .= $this->testform();
                break;
            }
            case 1 :
            {
                $content = "<div class=\"alert alert-danger\" role=\"alert\">Echec de l'inscription ! Le login existe déjà</div>";
            }
            // inscription
            case 2 :
            {
                $path = "../";
                $current_page = "Inscription";
                $content .= $this->formlogin();
                break;
            }
            case 3 :
            {
                $path = "../";
                if ($this->tab['login'] != "existe déjà") {
                    $title = "Votre inscription est complète !";
                    $content = 'Login <b>' . $this->tab['login'] . '</b> enregistré';
                }
                else {
                    $title = "Ce compte existe déjà";
                    $content = 'Création du compte impossible, le compte existe déjà';
                }
                $content = "<div class=\"alert alert-success\" role=\"alert\">Inscription réussie ! Login <b> $this->tab['login'] </b> enregistré</div>";
            }
            case 4 :
            {
                $path = "../";
                $res = ($this->tab['res']) ? 'OK' : 'KO';
                $content .= 'Mot de passe <b>' . $res . '</b></br>';
                if ($res == 'OK') $content .= 'Connecté en tant que <b>' . $_SESSION['profile']['username'] . '</b>';
                $current_page = "Espace personnel";
                break;
            }
            case 5 :
            {
                $url_deconnexion = $this->container->router->pathFor('deconnexion');
                $content = "<a href='$url_deconnexion'>Deconnexion</a>";
                break;
            }
            case (6) :
            {
                $content = "Deconnecté";
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
        <img src="{$path}img/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
        MYWISHLIST
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"> <a class="nav-link active" href="$url_accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_item">Participer à une liste</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_liste">Gérer mes listes</a></li>
                <li class="nav-item"><a class="nav-link" href="$url_compte">$connected</a></li>
            </ul>
        </div>
    </nav>
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item " aria-current="page"><a href="$url_accueil">Home</a></li>
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