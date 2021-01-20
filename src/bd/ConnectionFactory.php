<?php
namespace mywishlist\bd;
use \PDO;
use PDOException;

class ConnectionFactory {

    private static $config = null;
    private static $db = null;

    public static function setConfig($configfile){
        self::$config = parse_ini_file($configfile);
        if( is_null(self::$config)) throw new DBException("config file could not be parsed <br>");
    }

    public static function makeConnection(){

        if(is_null(self::$db)){
            $dbtype = self::$config['driver'];
            $host = self::$config['host'];
            $dbname = self::$config['database'];
            $user = self::$config['username'];
            $pass = self::$config['password'];
            $port = ((isset(self::$config['port'])) ? self::$config['port'] : null);

            $dsn = "$dbtype:host=$host";
            if (!empty($port)) $dsn .= "port=$port;";
            $dsn .= ";dbname=$dbname";

            try {
                self::$db = new PDO($dsn, $user, $pass, array(  PDO::ATTR_PERSISTENT => true,
                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                                                PDO::ATTR_EMULATE_PREPARES => false,
                                                                PDO::ATTR_STRINGIFY_FETCHES => false
                                                            ));


                self::$db->prepare('SET NAMES \'UTF8\'')->execute();
            }
            catch (PDOException $e){
                echo $e->getMessage() . '\n';
            }
        }
        return self::$db;
    }
}