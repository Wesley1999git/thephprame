<?php

use ThePHPrame\Core\Library\Cookies;
use ThePHPrame\Router\Response;
use ThePHPrame\Router\Routes;

function exceptionHandler($exception){

    if(method_exists($exception,"handle")){
        $exception->handle();
    }

    if(ENVIRONMENT == "dev"){
        print($exception);
    }
}

set_exception_handler('exceptionHandler');

// constants
define("SITE_ROOT",$_SERVER["HTTP_HOST"]);
define("ENVIRONMENT","dev");

define("ROOT_FOLDER",dirname(__DIR__));    

function response(){
    return new Response();
}

function asset($name){
    return SITE_ROOT."/".$name;
}

function view($view,$params = []){
    Cookies::setQueuedCookies();
    extract($params);
    $location = ROOT_FOLDER."//Views//".implode("//",explode(".",$view)).".php";
    if(file_exists($location)){
        include_once ($location);
    }else{
        throw new \ThePHPrame\Core\Exceptions\PageNotFound();
    }
}

// Register autoload
spl_autoload_register(function($class){
    $class = str_replace("\\","//",$class);
    if(file_exists(ROOT_FOLDER.'//'.$class.".php")){
        require_once ROOT_FOLDER.'//'.$class.".php";
    }
});

if(file_exists(ROOT_FOLDER.'//vendor//autoload.php')){
    require_once ROOT_FOLDER.'//vendor//autoload.php';
}

if(file_exists(ROOT_FOLDER.'//.env')){
    \Dotenv\Dotenv::createImmutable(ROOT_FOLDER)->load();
}


require_once (ROOT_FOLDER."//Bootstrap//container.php");
require_once (ROOT_FOLDER."//Config//app.php");
require_once (ROOT_FOLDER."//Config//database.php");


// Get current url
$requestUri = $_SERVER["REQUEST_URI"];

$router = new Routes($container);
$router->dispatch(trim($requestUri, "/"), $_SERVER['REQUEST_METHOD']);




?>
