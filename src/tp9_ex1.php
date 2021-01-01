<?php

$user="root";
$pass="";

$dsn="mysql:host=db;dbname=mywishlist";

try {
    $db = new PDO($dsn, $user, $pass, [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false
    ]);

    $db->prepare('SET NAMES \'UTF8\'')->execute();

    $st = $db->prepare("select * from item");

    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $row) {
        print $row['id'] . ' : ';
        print $row['nom'] . ' : ';
        print $row['tarif'] . "<br>\n";
    }   
}
catch (PDOException $e){
    echo $e->getMessage() . '\n';
}