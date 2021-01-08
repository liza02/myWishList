<?php

namespace mywishlist\controls;

use \mywishlist\models\User;

class Authentication {
    public static function createUser($username, $password) {
        $nb = User::where('login','=',$username)->count();

        if ($nb == 0) {
            $u = new User();
            $u->login = $username;
            $u->pass = password_hash($password, PASSWORD_DEFAULT);
            $u->save();
        } else {
            throw new \Exception();
        }
    }

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

    private static function loadProfile($uid) {
        session_destroy();
        session_start();
        $_SESSION['profile'] = array('username' => User::where('id','=',$uid)->first()->login, 'userid' => $uid);
    }

    public static function checkAccessRights($required) {

    }

}