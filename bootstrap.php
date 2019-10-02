<?php

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

function getRootFolder(){
    $rootFolderName = dirname(__FILE__);
    return $rootFolderName;
}

function response(){
    return new \Library\Response();
}

function asset($name){
    return SITE_ROOT."/".$name;
}

function view($view,$params = []){
    \Library\Cookies::setQueuedCookies();
    extract($params);
    $location = getRootFolder()."\\Views\\".implode("\\",explode(".",$view)).".php";
    if(file_exists($location)){
        include_once ($location);
    }else{
        throw new \App\Exceptions\PageNotFound();
    }
}

// Register autoload
spl_autoload_register(function($class){
    if(file_exists(getRootFolder().'\\'.$class.".php")){
        require_once getRootFolder().'\\'.$class.".php";
    }
});

if(file_exists(getRootFolder().'\\vendor\\autoload.php')){
    require_once getRootFolder().'\\vendor\\autoload.php';
}

\Dotenv\Dotenv::create(__DIR__)->load();


require_once (getRootFolder()."\\Config\\app.php");
require_once (getRootFolder()."\\Config\\database.php");



// Get current url
$requestUri = $_SERVER["REQUEST_URI"];

\Library\Routes::createRequestObject();

\Library\Session::createNewSessionIfNoneExists();
\Library\Session::saveSession();

library\Routes::loadRoutes();
$route = library\Routes::getRoute(trim($requestUri, "/"), $_SERVER['REQUEST_METHOD']);




?>
