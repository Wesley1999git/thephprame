<?php

namespace App\Exceptions;

use Exception;
use Library\IException;
use Library\Response;
use Library\Routes;

class NotAuthenticated extends Exception implements IException {

    private $request;

    public function __construct() {
        $this->request = Routes::createRequestObject();
        parent::__construct("Not authenticated", 0, null);
    }

    public function handle() {
        $contentType = $this->request->getHeaderValue("Accept");
        $responseData = ["Message" => "Not authenticated"];
        if($contentType && strpos($contentType,"json") !== false){
            http_response_code(403);
            echo json_encode($responseData);
            return;
        }
        if($contentType && strpos($contentType,"xml") !== false){
            http_response_code(403);
            $xml = new \SimpleXMLElement('<root/>');
            array_walk_recursive($responseData,array($xml,'addChild'));
            echo $xml->asXML();
            return;
        }
        Response::redirect("https://google.com");
    }
}
