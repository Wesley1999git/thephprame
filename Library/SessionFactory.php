<?php 

namespace Library;

class SessionFactory{

    public static function createSessionDriver(){
        $driver = SESSION_DRIVER;;
        self::createSessionHandler($driver);
    }

    public static function createSessionHandler($driver){
        switch ($driver){
            case "file":
                \Library\Session::createNewSessionIfNoneExists();
                \Library\Session::saveSession();
            default:
                return null;
        }
    }
}   