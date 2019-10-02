<?php

namespace Library;
use Ramsey\Uuid\Uuid;

class Session{

    public static $sessionId;
    public static $data;

    public static function setSessionVariable($key,$value){
        self::$data[$key] = $value;
    }

    public static function getSessionVariable($key){
        if(key_exists($key,self::$data)) {
            return self::$data[$key];
        }
        return null;
    }

    public static function saveSession(){
        $fileContent = Encryption::encrypt(json_encode(self::$data));
        $name = self::$sessionId.".txt";
        $location = "sessions\\";
        Storage::createNewFile($location,$name,$fileContent);
    }

    public static function createNewSessionIfNoneExists(){
        if(!Cookies::getCookie("session_uuid")){
            $uuid5 = Uuid::uuid5(Uuid::fromBytes(openssl_random_pseudo_bytes(16)),'php.net');
            if(Storage::fileExists("sessions\\".self::$sessionId.".txt")){
                self::createNewSessionIfNoneExists();
                return;
            }
            $uuid = $uuid5->toString();
            self::$sessionId = $uuid;
            self::$data['session_id'] = self::$sessionId;
            $sessionCookie = new Cookie("session_uuid",$uuid,98000);
            Cookies::setCookie($sessionCookie);
        }else{
            self::$sessionId = Cookies::getCookie("session_uuid");
            if(!Storage::fileExists("sessions\\".self::$sessionId.".txt")){
                self::$data['session_id'] = self::$sessionId;
                self::saveSession();
            }
            $encryptedFileContent = Storage::getFile("sessions\\".self::$sessionId.".txt");
            $decryptedFileContent = json_decode(Encryption::decrypt($encryptedFileContent),true);
            self::$data = $decryptedFileContent;
        }
    }

    public static function destroySession(){
        Storage::deleteFile("sessions\\".self::$sessionId.".txt");
        self::$sessionId = null;
        self::$data = [];
        Cookies::setCookie(new Cookie("session_uuid",null,-3600));
    }
}
