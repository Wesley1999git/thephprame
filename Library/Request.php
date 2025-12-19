<?php

namespace Library;

class Request{

    public $url;
    public $httpMethod;
    public $params;
    public $headers;
    public $cookies;

    public function __construct($url,$httpMethod,$params,$headers,$cookies,$files) {
        $this->url = $url;
        $this->httpMethod = $httpMethod;
        $this->params = $params;
        $this->headers = $headers;
        $this->cookies = $cookies;

        Cookies::parseCookies($this->cookies);
        Files::setFiles($files);

        $this->setParamsAsProperty();
    }

    private function setParamsAsProperty(){
        foreach($this->params as $key => $value){
            $this->$key = $value;
        }
    }

    public function getUrl(){
        return $this->url;
    }

    public function getParam($paramName){
        if(array_key_exists($paramName,$this->params)) {
            return $this->params[$paramName];
        }
        return null;
    }

    public function getHeaderValue($headerName){
        if(array_key_exists("Content-Type",$this->headers)){
            return $this->headers[$headerName];
        }
        return null;
    }

    public function getCookie($name){
        return Cookies::getCookie($name);
    }

    public function hasFile($inputName){
        return Files::hasFile($inputName);
    }

    public function getFile($inputName){
        return Files::getFile($inputName);
    }
}
