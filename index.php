<?php
declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use \mywishlist\controls\MonControleur;

$config = ['settings' => [
    'displayErrorDetails' => true,
]];

$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('config/config.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$container = new \Slim\Container($config);
$app = new \Slim\App($container);

$app->get('/'          , MonControleur::class.':accueil'       )->setName('racine'    );
$app->get('/listes'    , MonControleur::class.':afficherListes')->setName('aff_listes');
$app->get('/liste/{no}', MonControleur::class.':afficherListe' )->setName('aff_liste' );
$app->get('/item/{id}' , MonControleur::class.':afficherItem'  )->setName('aff_item'  );

$app->get('/nouvelleliste' , MonControleur::class.':formListe'  )->setName('formListe'  );
$app->post('/nouvelleliste' , MonControleur::class.':newListe'  )->setName('newListe'  );

$app->get('/formlogin'    , MonControleur::class.':formlogin'   )->setName('formlogin'  );
$app->post('/nouveaulogin', MonControleur::class.':nouveaulogin')->setName('nouveaulogin'  );

$app->get('/testform' , MonControleur::class.':testform'  )->setName('testform'  );
$app->post('/testpass', MonControleur::class.':testpass'  )->setName('testpass'  );

$app->post('/deconnexion', MonControleur::class.':deconnexion'  )->setName('deconnexion'  );

$app->run();