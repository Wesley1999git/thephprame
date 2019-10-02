<?php

namespace Library;

class Cookies{

    public static $cookies = [];
    public static $queueCookies = [];

    public static function getCookie($name){
        if(key_exists($name,self::$cookies)){
            return self::$cookies[$name];
        }
        return null;
    }

    public static function queueCookie(Cookie $cookie){
        self::$queueCookies[] = $cookie;
    }

    public static function queueCookies($cookies){
        self::$queueCookies = array_merge(self::$cookies,$cookies);
    }

    public static function setCookie(Cookie $cookie){
        setcookie($cookie->name, $cookie->value, $cookie->expiresAt, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httpOnly);
    }

    public static function setQueuedCookies(){
        for($i = 0;$i<count(self::$queueCookies);$i++){
            setcookie(self::$queueCookies[$i]->name, self::$queueCookies[$i]->value, self::$queueCookies[$i]->expiresAt, self::$queueCookies[$i]->path, self::$queueCookies[$i]->domain, self::$queueCookies[$i]->secure, self::$queueCookies[$i]->httpOnly);
        }
    }

    public static function parseCookies($cookies){
        foreach($cookies as $key => $cookie){
            self::$cookies[$key] = $cookie;
        }
    }

}
