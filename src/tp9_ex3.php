<?php

/**
 * établir la connexion à la base
 */
/*require_once('WishQuery.php');
require_once('./bd/ConnectionFactory.php');*/

require_once('../vendor/autoload.php');
use \mywishlist\WishQuery;
use \mywishlist\bd\ConnectionFactory as ConnectionFactory;

ConnectionFactory::setConfig('../config/config.ini');

print "db connection ok";

WishQuery::listItems();
echo "</br>";
echo "</br>";
WishQuery::unItem(2);
echo "</br>";
echo "</br>";
$id = WishQuery::nouvelleListe("ma liste d'anniversaire");
echo "</br>";
echo "</br>";
WishQuery::supprimerListe($id);
echo "</br>";
echo "</br>";
WishQuery::listItemListe(1);