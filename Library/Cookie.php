<?php

namespace Library;

class Cookie{

    public $name;
    public $value;
    public $domain;
    public $path;
    public $expiresAt;
    public $httpOnly;
    public $secure;

    public function __construct($name, $value, $expire = 3600, $path = "/", $domain = null, $secure = null, $httpOnly = null){
        $this->name = $name;
        $this->value = $value;
        $this->expiresAt = (time() + $expire);
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

}
