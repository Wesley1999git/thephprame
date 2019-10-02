<?php

namespace Library;

class Storage{

    public static function storeFile(File $file,$location,$name){

        $location = getRootFolder()."\\Storage\\".$location.$name;

        if(move_uploaded_file($file->tmpName,$location)){
            return $location.$name;
        }
        return null;
    }

    public static function getFile($name){
        $location = getRootFolder()."\\Storage\\".$name;

        if(file_exists($location)) {
            $file = file_get_contents($location);

            return $file;
        }
        return null;
    }

    public static function deleteFile($name){
        $location = getRootFolder()."\\Storage\\".$name;

        if(file_exists($location)){
            if(unlink($location)){
                return true;
            }
        }
        return false;
    }

    public static function createNewFile($location,$name,$content){
        $fp = fopen(getRootFolder()."\\Storage\\".$location.$name,'w');
        fwrite($fp,$content);
        fclose($fp);
    }

    public static function fileExists($name){
        if(file_exists(getRootFolder()."\\".$name)){
            return true;
        }
        return false;
    }
}
