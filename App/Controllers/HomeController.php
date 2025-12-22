<?php

namespace App\Controllers;

use App\Services\ExampleService;
use ThePHPrame\Core\Library\Controller;
use ThePHPrame\Router\Request;

class HomeController extends Controller {

    public function __construct(private ExampleService $exampleService){}

    public function index(Request $request){
        echo $this->exampleService->exampleMethod();
        echo "Empty framework";
    }

}
