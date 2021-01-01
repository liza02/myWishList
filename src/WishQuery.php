<?php

namespace mywishlist;
use \PDO;
use mywishlist\bd\ConnectionFactory as ConnectionFactory;
class WishQuery {
    public static function listItems()  {
        $pdo = ConnectionFactory::makeConnection();

        $query = "select * from item";

        $st=$pdo->prepare($query);
        $st->execute();
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $row) {
            foreach ($row as $att=>$val) echo "$att : $val </br>";
        }
    }

    public static function unItem($id) {
        $pdo = \mywishlist\bd\ConnectionFactory::makeConnection();
        $query = "select * from item where id = ?";

        $st=$pdo->prepare($query);
        $st->bindParam(1,$id, PDO::PARAM_INT);
        $st->execute();
        $row=$st->fetch(PDO::FETCH_ASSOC);
        foreach ($row as $att=>$val) echo "$att : $val \n";
    }

    public static function nouvelleListe ($nom) {
        $pdo = \mywishlist\bd\ConnectionFactory::makeConnection();
        $query="insert into liste (titre) values (?)";
    }

    public static function supprimerListe ($id) {
        $pdo = \mywishlist\bd\ConnectionFactory::makeConnection();
    }

    public static function listItemListe ($id) {
        $pdo = \mywishlist\bd\ConnectionFactory::makeConnection();

        $pdo = ConnectionFactory::makeConnection();
        $stmt = $pdo->prepare('SELECT id, liste_id, nom FROM item WHERE liste_id = ?');
        $stmt->execute([$id]);
        echo "Items de la liste numero : $id <br>";
        while(list($id, $liste_id, $nom) = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "$id $liste_id $nom <br>";
        }
    }
}