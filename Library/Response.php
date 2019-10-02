<?php

namespace Library;

class Response{

    private $data;

    public function __construct() {
        http_response_code(200);
    }

    public function setResponseCode($code = 200){
        http_response_code($code);
    }

    public function json($json){
        $this->data = $json;
        header("Content-Type: application/json");

        return $this;
    }

    public function xml($xml){
        $this->data = $xml;
        header("Content-Type: application/xml");

        return $this;
    }

    public static function redirect($url){
        header("Location: ".$url);
    }

    public function __destruct() {
        echo $this->data;
    }
}
