<?php

require '../vendor/autoload.php';
use mywishlist\WishQueryModel as WishQueryModel;

new mywishlist\WishQueryModel();
//WishQueryModel::afficherListes();
//WishQueryModel::afficherItems();
foreach ($_GET as $var => $val){
    WishQueryModel::afficherItemURL($val);
}
//WishQueryModel::creerItem(8,"Netflix","Petite soiree autour d une petite serie","netflix.png","",0.000);
WishQueryModel::listeDItem();
WishQueryModel::listeItemListe(1);