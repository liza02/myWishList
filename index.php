<?php
declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use mywishlist\controls\ControleurAccueil;
use mywishlist\controls\ControleurCompte;
use mywishlist\controls\ControleurListe;
use mywishlist\controls\ControleurItem;

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
$app->get('/compte', ControleurAccueil::class.':compte')->setName('compte');
$app->get('/item', ControleurAccueil::class.':item')->setName('item');
$app->get('/listes/nouvelleListe', ControleurAccueil::class.':list')->setName('liste');

//Chemin Compte
$app->get('/compte/newlogin', ControleurCompte::class.':formlogin'   )->setName('formlogin');
$app->post('/compte/newlogin', ControleurCompte::class.':nouveaulogin')->setName('nouveaulogin');
$app->get('/compte/login', ControleurCompte::class.':testform')->setName('testform');
$app->post('/compte/login', ControleurCompte::class.':testpass')->setName('testpass');
$app->get('/deconnexion', ControleurCompte::class.':deconnexion')->setName('deconnexion');

//Chemin Liste
$app->get('/listes/nouvelleliste' , ControleurListe::class.':formListe')->setName('formListe');
$app->post('/listes/nouvelleliste' , ControleurListe::class.':newListe')->setName('newListe');
$app->get('/listes/{token}', ControleurListe::class.':afficherItemsListe' )->setName('aff_liste');
//$app->get('/liste/{token}',ControleurListe::class.':afficherItemsListe')->setName('aff_item_liste');

//Chemin Item
$app->get('/listes/{token}/{id_item}', ControleurItem::class.':afficherItem' )->setName('aff_item');
$app->post('/listes/{token}/{id_item}/reserverItem', ControleurItem::class.':reserverItem')->setName('reserve_item');
/*
 * Chemin de base que je supprime dès que j'en ai plus besoin
 */
//$app->get('/'          , MonControleur::class.':accueil'       )->setName('racine'    );

//$app->get('/liste/{no}', MonControleur::class.':afficherItemsListe' )->setName('aff_liste' );
//$app->get('/item/{id}' , MonControleur::class.':afficherItem'  )->setName('aff_item'  );
//$app->post('/reserver/{id}' , MonControleur::class.':reserverItem'  )->setName('reserve_item'  );

//$app->get('/nouvelleliste' , MonControleur::class.':formListe'  )->setName('formListe'  );
//$app->post('/nouvelleliste' , MonControleur::class.':newListe'  )->setName('newListe'  );

//$app->get('/formlogin'    , MonControleur::class.':formlogin'   )->setName('formlogin'  );
//$app->post('/nouveaulogin', MonControleur::class.':nouveaulogin')->setName('nouveaulogin'  );

//$app->get('/testform' , MonControleur::class.':testform'  )->setName('testform'  );
//$app->post('/testpass', MonControleur::class.':testpass'  )->setName('testpass'  );

//$app->post('/deconnexion', MonControleur::class.':deconnexion'  )->setName('deconnexion'  );

$app->run();