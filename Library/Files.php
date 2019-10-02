<?php

namespace Library;

class Files{

    public static $files;

    public static function hasFile($inputName){
        if(key_exists($inputName,self::$files)){
            return true;
        }
        return false;
    }

    public static function getFile($fileName){
        if(key_exists($fileName,self::$files)){
            return self::$files[$fileName];
        }
        return null;
    }

    public static function setFiles($files){
        foreach($files as $key => $file){
            if(is_array($file)){
                foreach($file as $fileKey => $fileValue){
                    $files[$key][$fileKey] = new File($fileKey, $fileValue['name'], $fileValue['size'], $fileValue['type'], $fileValue['tmp_name'], $fileValue['error']);
                }
            }else {
                $files[$key] = new File($key, $file['name'], $file['size'], $file['type'], $file['tmp_name'], $file['error']);
            }
        }
    }

}
