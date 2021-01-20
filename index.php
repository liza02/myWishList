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


//Chemin Accueil
$app->get('/', ControleurAccueil::class.':accueil')->setName('racine');


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
$app->get('/supprimerCompte', ControleurCompte::class.':supprimerCompte')->setName('supprimerCompte');

//Chemin Liste
$app->get('/mesListes',ControleurListe::class.':afficherMesListes')->setName('afficherMesListes');
$app->get('/mesListes/nouvelleliste' , ControleurListe::class.':creerListe')->setName('creerListe');
$app->post('/mesListes/nouvelleliste' , ControleurListe::class.':enregistrerListe')->setName('enregistrerListe');
$app->get('/mesListes/supprimer/{token}' , ControleurListe::class.':supprimerListe')->setName('supprimerListe');
$app->get('/mesListes/{token}', ControleurListe::class.':afficherUneListe' )->setName('aff_maliste');
$app->get('/mesListes/modifier/{token}', ControleurListe::class.':modifierListe' )->setName('modifierListe');
$app->post('/mesListes/{token}', ControleurListe::class.':enregistrerModificationListe' )->setName('enregistrerModificationListe');
$app->get('/meslistes/ajoutItem/{token}',ControleurListe::class.':ajoutItem')->setName('ajoutItem');
$app->post('/meslistes/ajoutItem/{token}',ControleurListe::class.':enregistrerNouveauItemListe')->setName('enregistrerNouveauItemListe');


//Chemin Participant
$app->get('/participer', ControleurParticipant::class.':afficherListes' )->setName('participer');
$app->post('/participer', ControleurParticipant::class.':accederListe' )->setName('accederListe');
$app->get('/participer/{token}', ControleurParticipant::class.':afficherListeParticipant' )->setName('afficherListeParticipant');
$app->get('/participer/{token}/message', ControleurParticipant::class.':ajouterMessage' )->setName('afficherFormMessage');
$app->post('/participer/{token}/message', ControleurParticipant::class.':ajouterUnMessage' )->setName('formMessageListe');

//Chemin Item Participant
$app->get('/participer/{token}/{id_item}', ControleurItem::class.':afficherItemParticipant' )->setName('aff_item');
$app->get('/participer/{token}/{id_item}/reserverItem', ControleurItem::class.':reserverItem')->setName('reserve_item');
$app->post('/participer/{token}/{id_item}/reserverItem', ControleurItem::class.':reserverUnItem')->setName('formReserveItem');

$app->get('/participer/{token}/{id_item}/cagnotte', ControleurItem::class.':participerCagnotte')->setName('participerCagnotte');
$app->post('/participer/{token}/{id_item}/cagnotte', ControleurItem::class.':formCagnotte')->setName('formCagnotte');


//Chemin Item Admin
$app->get('/mesListes/{token}/{id_item}', ControleurItem::class.':afficherItemCreateur' )->setName('aff_item_admin');
$app->get('/mesListes/{token}/{id_item}/modifier', ControleurItem::class.':modifierItem' )->setName('modifierItem');
$app->post('/mesListes/{token}/{id_item}/modifier', ControleurItem::class.':modifierUnItem' )->setName('formModifierItem');
$app->get('/mesListes/{token}/{id_item}/supprimer', ControleurItem::class.':supprimerItem' )->setName('supprimerItem');
$app->get('/mesListes/{token}/{id_item}/supprimerImage', ControleurItem::class.':supprimerImage' )->setName('supprimerImage');
$app->get('/mesListes/{token}/{id_item}/creerCagnotte', ControleurItem::class.':creerCagnotte' )->setName('creerCagnotte');

//Chemin Error
$app->get('/meslistes/erreur',ControleurListe::class.':erreurListe')->setName('erreurListe');
$app->get('/meslistes/item/erreur',ControleurItem::class.':erreurItem')->setName('erreurItem');

$app->run();