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
        $html = <<<FIN
<form method="POST" action="$url_nouveaulogin">
    <label>Nom:<br> <input type="text" name="nom"></label><br>
    <label>Prenom:<br> <input type="text" name="prenom"></label><br>
    <label>Login:<br> <input type="text" name="login"/></label><br>
    <label>Mot de passe: <br><input type="text" name="pass"/></label><br>
    <button type="submit">Enregistrer le login</button>
</form>    
FIN;
        return $html;
    }

    private function testform() : string {
        $url_testpass = $this->container->router->pathFor( 'testpass' ) ;
        $html = <<<FIN
<form method="POST" action="$url_testpass">
    <label>Login:<br> <input type="text" name="login"/></label><br>
    <label>Mot de passe: <br><input type="text" name="pass"/></label><br>
    <button type="submit">Tester le login</button>
</form>    
FIN;
        return $html;
    }

    public function render( int $select ) : string
    {

        $url_accueil = $this->container->router->pathFor('racine');
        $url_formlogin = $this->container->router->pathFor('formlogin');
        $url_testform = $this->container->router->pathFor('testform');
        $url_compte = $this->container->router->pathFor('compte');
        $url_item = $this->container->router->pathFor('item');
        $url_liste = $this->container->router->pathFor('liste');
        $url_deconnexion = $this->container->router->pathFor('deconnexion');

        if (isset($_SESSION['profile']['username'])){
            $connected = "Mon Compte";
        }else{
            $connected = "Connexion";
        }

        switch ($select) {

            case 0 :
            {
                $path = "Connexion";
                $content = $this->testform();
                $content .= '<div> Pas encore de compte ? <a href="' .$url_formlogin . '"> En créer un </a></div> ';
                break;
            }
            case 1 :
            {
                $path = "Inscription";
                $content = $this->formlogin();
                $content .= '<div> Deja un compte ? <a href="' .$url_testform . '"> Se connecter </a></div> ';
                break;
            }
            case 2 :
            {
                $content = 'Login <b>' . $this->tab['login'] . '</b> enregistré';
                break;
            }
            case 3 :
            {
                $path = "Test";
                $content = $this->testform();
                $content .= '<div> Pas encore de compte ? <a href="' .$url_formlogin . '"> En créer un </a></div> ';
            }
            case 4 :
            {
                $res = ($this->tab['res']) ? 'OK' : 'KO';
                $content = 'Mot de passe <b>' . $res . '</b></br>';
                if ($res == 'OK') $content .= 'Connecté en tant que <b>' . $_SESSION['profile']['username'] . '</b>';
                break;
            }
            case 5 :
            {
                $url_deconnexion = $this->container->router->pathFor('deconnexion');
                $content = "<a href='$url_deconnexion'>Deconnexion</a>";
                break;
            }
            case 6 :
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

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="$url_accueil">
        <img src="img/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
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
            <li class="breadcrumb-item active" aria-current="page">$path</li>
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