<?php

namespace mywishlist\controls;

use \mywishlist\models\User;

/**
 * Class Authentication
 * @package mywishlist\controls
 */
class Authentication {

    /**
     * Fonction de création de USer
     * @param $nom
     * @param $prenom
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public static function createUser($nom, $prenom, $username, $password) {
        $nb = User::where('login','=',$username)->count();
        if ($nb == 0) {
            $u = new User();
            $u->nom = $nom;
            $u->prenom = $prenom;
            $u->login = $username;
            $u->pass = password_hash($password, PASSWORD_DEFAULT);
            $u->save();
        } else {
            throw new \Exception();
        }
    }

    /**
     * Fonction de vérification d'authentification
     * @param $username
     * @param $password
     * @return bool
     */
    public static function authenticate($username, $password) {
        $u = User::where('login','=',$username)->first();
        if(gettype($u) != 'NULL'){
            $res = password_verify($password, $u->pass);
        }
        else{
            $res = false;
        }
        if ($res) self::loadProfile($u->id);

        return $res;
    }

    /**
     * Fonction pour stocker le profile dans la variable de session
     * @param $uid
     */
    private static function loadProfile($uid) {
        session_destroy();
        $_SESSION = [];
        session_start();
        setcookie("user_id", $uid, time() + 60*60*24*30, "/" );
        $_SESSION['profile'] = array('username' => User::where('id','=',$uid)->first()->login, 'userid' => $uid);
    }

    public static function checkAccessRights($required) {

    }

}