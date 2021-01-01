<?php

namespace  mywishlist;
use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\models\Liste;
use mywishlist\models\Item;

class WishQueryModel
{
    public function __construct(){
        $db = new DB();
        $config = parse_ini_file("../config/config.ini");
        if ($config) $db->addConnection($config);

        $db->setAsGlobal();
        $db->bootEloquent();
    }

    // lister les listes de souhaits
    public static function afficherListes(){
        $lall = Liste::all();

        echo "ALL LISTE : <br><br>";
        foreach ( $lall as $item){
            echo "Titre : " . $item->titre . " " . "Description : " . $item->description . "<br>";
        }
    }

    // lister les items
    public static function afficherItems(){
        $iall = Item::all();
        echo '<br> ALL ITEM : <br><br>';
        foreach ( $iall as $item){
            echo "Titre : " . $item->nom . " " . "Description : " . $item->descr . "<br>";
        }
    }

    // afficher un item en particulier, dont l'id est passé en paramêtre dans l'url (test.php?id=1)
    public static function afficherItemURL($get){
        $i2 = Item::select('nom','descr')->where('id','=',$get)->get();
        //mettre un parametre dans l'url
        echo '<br> ITEM ID  : <br><br>';
        foreach ($i2 as $item){
            echo "Titre : " . $item->nom . " " . "Description : " . $item->descr . "<br>";
        }
    }

    // créer un item, l'insérer dans la base et l'ajouter à une liste de souhaits.
    public static function creerItem($liste_id,$nom,$desc,$img,$url,$tarif){
        $l = new Item();
        $l->liste_id = $liste_id;
        $l->nom=$nom;
        $l->descr=$desc;
        $l->img=$img;
        $l->url=$url;
        $l->tarif=$tarif;
        $l->save();
    }

    // indiquer le nom de la liste de souhait dans la liste des items
    public static function listeDItem(){
        $i = Item::all();
        foreach ( $i as $item){
            if ($item->liste_id < 1 || $item->liste_id > 2){
                $res = "Inconnue";
            }else{
                $res = $item->Liste()->first()->titre;
            }
            echo "Liste : " . $res . " " . "Nom : " . $item->nom . "<br>";
        }
    }

    // lister les items d'une liste donnée dont l'id est passé en paramètre
    public static function listeItemListe($id){
        $list = Liste::where('no', '=', $id)->first()->Item()->get();
        foreach ($list as $item){
            echo "Nom : " . $item->nom . " Description : " . $item->decr . "<br>";
        }
    }
}