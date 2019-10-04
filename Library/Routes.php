<?php
namespace Library;
use App\Exceptions\PageNotFound;

class Routes{

    private static $routes = [];

    public static function loadRoutes(){

        self::$routes["GET"] = [];
        self::$routes["POST"] = [];
        self::$routes["PATCH"] = [];
        self::$routes["PUT"] = [];
        self::$routes["DELETE"] = [];

        require_once (getRootFolder()."\\routes\\web.php");
        require_once (getRootFolder()."\\routes\\api.php");

    }

    public static function get($url,$method,$middleware = null){
        if(self::routeAlreadyExists($url,"GET")){
            self::removeAndRearrangeRouteArray($url,"GET");
        }

        if(is_string($method)) {
            self::$routes['GET'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }else{
            self::$routes['GET'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }
    }

    public static function post($url,$method,$middleware = null){
        if(self::routeAlreadyExists($url,"POST")){
            self::removeAndRearrangeRouteArray($url,"POST");
        }

        if(is_string($method)) {
            self::$routes['POST'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }else{
            self::$routes['POST'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }
    }

    public static function patch($url,$method,$middleware = null){
        if(self::routeAlreadyExists($url,"PATCH")){
            self::removeAndRearrangeRouteArray($url,"PATCH");
        }

        if(is_string($method)) {
            self::$routes['PATCH'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }else{
            self::$routes['PATCH'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }
    }

    public static function put($url,$method,$middleware = null){
        if(self::routeAlreadyExists($url,"PUT")){
            self::removeAndRearrangeRouteArray($url,"PUT");
        }

        if(is_string($method)) {
            self::$routes['PUT'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }else{
            self::$routes['PUT'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }
    }

    public static function delete($url,$method,$middleware = null){
        if(self::routeAlreadyExists($url,"DELETE")){
            self::removeAndRearrangeRouteArray($url,"DELETE");
        }

        if(is_string($method)) {
            self::$routes['DELETE'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }else{
            self::$routes['DELETE'][] = ['url' => $url, 'method' => $method, 'middleware' => $middleware];
        }
    }

    private static function routeAlreadyExists($route,$method){
        $routes = self::$routes[$method];
        $exists = false;
        for($i=0;$i<count($routes);$i++){
            if($routes[$i]['url'] == $route){
                $exists = true;
            }
        }
        return $exists;
    }

    private static function removeAndRearrangeRouteArray($url,$method){
        $routes = self::$routes[$method];
        for($i=0;$i<count($routes);$i++){
            if($routes[$i]['url'] == $url){
                unset($routes[$i]);
                self::$routes[$method] = array_values($routes);
            }
        }
    }

    public static function getRoute($route,$httpMethod){
        $linkedRoute = self::getLinkedRoute($route,$httpMethod);
        if($linkedRoute){
            if($linkedRoute['middleware']){
                call_user_func_array("App\\Middleware\\".$linkedRoute['middleware']."::handle",[self::createRequestObject()]);
            }
            if(is_string($linkedRoute['method'])){
                $methodSplit = explode('@',$linkedRoute['method']);
                $controller = "App\\Controllers\\".$methodSplit[0];
                $method = $methodSplit[1];

                $instance = new $controller();
                if(strpos($linkedRoute['url'],'{') !== false){
                    $params = [];
                    $linkedRouteSplit = explode("/",$linkedRoute['url']);
                    $routeSplit = explode("/",$route);
                    $countRouteSplit = count($routeSplit);
                    $params[0] = self::createRequestObject();
                    for($i=1;$i<$countRouteSplit;$i++){
                        if(strpos($linkedRouteSplit[$i],"{") !== false){
                            $params[trim(trim($linkedRouteSplit[$i],"{"),"}")] = $routeSplit[$i];
                        }
                    }
                    return call_user_func_array(array($instance,$method),$params);
                }
                return $instance->$method(self::createRequestObject());

            }else{
                if(strpos($linkedRoute['url'],'{') !== false){
                    $params = [];
                    $linkedRouteSplit = explode("/",$linkedRoute['url']);
                    $routeSplit = explode("/",$route);
                    $countRouteSplit = count($routeSplit);
                    $params[0] = self::createRequestObject();
                    for($i=1;$i<$countRouteSplit;$i++){
                        if(strpos($linkedRouteSplit[$i],"{") !== false){
                            $params[trim(trim($linkedRouteSplit[$i],"{"),"}")] = $routeSplit[$i];
                        }
                    }
                    return call_user_func_array($linkedRoute['method'],$params);
                }else{
                    return call_user_func($linkedRoute['method'],self::createRequestObject());
                }
            }
        }else{
            throw new PageNotFound();
        }
    }

    private static function getLinkedRoute($url,$method){
        $routeCollection = self::$routes[$method];
        $linkedRoute = array_filter($routeCollection,function($routeItem) use ($url){
            $linkedRouteSplit = explode("/",$routeItem['url']);
            $routeSplit = explode("/",$url);
            $countRouteSplit = count($routeSplit);
            $countLinkedRouteSplit = count($linkedRouteSplit);
            if(strpos($routeItem["url"],"{") !== false){
                $routeMatch = true;
                if($countRouteSplit == $countLinkedRouteSplit){
                    for($i=0;$i<count($routeSplit);$i++){
                        if(strpos($linkedRouteSplit[$i],"{") === false){
                            if($linkedRouteSplit[$i] != $routeSplit[$i]){
                                $routeMatch = false;
                            }
                        }
                    }
                    if($routeMatch){
                        return $routeItem;
                    }
                }
            }
            if($routeSplit == $linkedRouteSplit){
                return $routeItem;
            }
        });
        if(!empty($linkedRoute)){
            return $linkedRoute[0];
        }
        return null;
    }

    public static function createRequestObject(){
        $data = file_get_contents('php://input');
        $headers = getallheaders();
        $params = [];
        if(!empty($data)) {
            if (array_key_exists("Content-Type", $headers)) {
                if (strpos($headers["Content-Type"], "json") !== false) {
                    $params = json_decode($data, true);
                }
                if (strpos($headers["Content-Type"], "xml") !== false) {
                    $xmlDoc = simplexml_load_string($data);
                    $con = json_encode($xmlDoc);
                    $params = json_decode($con, true);
                }
            }
        }
        $params = array_merge($params,$_GET,$_POST);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .'://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $request = new Request($url,$_SERVER['REQUEST_METHOD'],$params,$headers,$_COOKIE,$_FILES);

        return $request;
    }
}
