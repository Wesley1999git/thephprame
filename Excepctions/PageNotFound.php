<?php

namespace ThePHPrame\Exceptions;

use Exception;
use ThePHPrame\Core\Exceptions\IException;
use ThePHPrame\Router\Routes;

class PageNotFound extends Exception implements IException {

    private $request;

    public function __construct() {
        $this->request = Routes::createRequestObject();
        parent::__construct("Page was not found", 0, null);
    }

    public function handle(){
        http_response_code(404);
        $contentType = $this->request->getHeaderValue("Content-Type");
        $responseData = ["Message" => "Resource was not found", "Request" => $this->request];
        if($contentType && strpos($contentType,"json") !== false){
            echo json_encode($responseData);
            return;
        }
        if($contentType && strpos($contentType,"xml") !== false){
            $xml = new \SimpleXMLElement('<root/>');
            array_walk_recursive($responseData,array($xml,'addChild'));
            echo $xml->asXML();
            return;
        }
        view("errors.404");
    }

}
