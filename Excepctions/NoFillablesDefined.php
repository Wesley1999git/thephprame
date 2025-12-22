<?php

namespace ThePHPrame\Exceptions;

use Exception;
use ThePHPrame\Core\Exceptions\IException;
use ThePHPrame\Router\Routes;

class NoFillablesDefined extends Exception implements IException {

    private $request;

    public function __construct() {
        $this->request = Routes::createRequestObject();
        parent::__construct("No fillables defined", 0, null);
    }

    public function handle() {
        http_response_code(500);
    }
}
