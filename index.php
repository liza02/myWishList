<?php
declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use mywishlist\controls\ControleurAccueil;
use mywishlist\controls\ControleurCompte;
use mywishlist\controls\ControleurListe;
use mywishlist\controls\ControleurItem;
use mywishlist\controls\ControleurParticipant;

$config = ['settings' => [
    'displayErrorDetails' => true,
]];

$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('config/config.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$container = new \Slim\Container($config);
$app = new \Slim\App($container);

/*
 * Nouveau Chemin (ceux que je mets a jour)
 */
//Chemin Accueil

$app->get('/', ControleurAccueil::class.':accueil')->setName('racine');
$app->get('/item', ControleurAccueil::class.':item')->setName('item');
$app->get('/listes/nouvelleListe', ControleurAccueil::class.':list')->setName('liste');

//Chemin Compte
$app->get('/inscription', ControleurCompte::class.':inscription')->setName('inscription');
$app->post('/inscription', ControleurCompte::class.':enregistrerInscription')->setName('enregistrerInscription');
$app->get('/connexion', ControleurCompte::class.':connexion')->setName('connexion');
$app->post('/connexion', ControleurCompte::class.':testConnexion')->setName('testConnexion');
$app->get('/compte', ControleurCompte::class.':afficherCompte')->setName('afficherCompte');
$app->get('/compte/modifier', ControleurCompte::class.':modifierCompte')->setName('modifierCompte');
$app->post('/compte', ControleurCompte::class.':enregistrerModif')->setName('enregistrerModif');
$app->get('/compte/changePassword', ControleurCompte::class.':changerMotDePasse')->setName('changerMotDePasse');
$app->post('/compte/changePassword', ControleurCompte::class.':enregistrerMotDePasse')->setName('enregistrerMotDePasse');
$app->get('/deconnexion', ControleurCompte::class.':deconnexion')->setName('deconnexion');

//Chemin Liste
$app->get('/mesListes',ControleurListe::class.':afficherMesListes')->setName('afficherMesListes');
$app->get('/mesListes/nouvelleliste' , ControleurListe::class.':creerListe')->setName('creerListe');
$app->post('/mesListes/nouvelleliste' , ControleurListe::class.':enregistrerListe')->setName('enregistrerListe');
$app->get('/mesListes/{token}', ControleurListe::class.':afficherUneListe' )->setName('aff_liste');
//$app->get('/liste/{token}',ControleurListe::class.':afficherItemsListe')->setName('aff_item_liste');

//Chemin Item
$app->get('/listes/{token}/{id_item}', ControleurItem::class.':afficherItem' )->setName('aff_item');
$app->post('/listes/{token}/{id_item}/reserverItem', ControleurItem::class.':reserverItem')->setName('reserve_item');

//Chemin Participant
$app->get('/participer', ControleurParticipant::class.':afficherListes' )->setName('participer');
$app->post('/participer', ControleurParticipant::class.':accederListe' )->setName('accederListe');

$app->run();