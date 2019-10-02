<?php

namespace Library;

class Encryption{

    public static function encrypt($value){

        $encryptedData = openssl_encrypt($value,"AES-128-CBC",ENCRYPTION_KEY,0,SECRET_KEY);

        return $encryptedData;
    }

    public static function decrypt($value){
        $decryptedData = openssl_decrypt($value,"AES-128-CBC",ENCRYPTION_KEY,0,SECRET_KEY);

        return $decryptedData;
    }

}
