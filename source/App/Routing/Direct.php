<?php

namespace App\Routing;

use Config;

class Direct extends Route{
    
    private $middleware = [];
    private $route = '';
    private $type = '';
    
    public function __construct($route, $callback, $type, $get = null){
        $this->route = $route;
        $this->type = $type;
        parent::$routes[$type][$route] = [
                                    'callback' => Config::$controllers.$callback,
                                    'vars' => $get,
                                    'middleware' => [],
                                ];
    }
    
    /**
     * redirect to a page
     * @param string $page
     */
    public static function re($page){
        header("location: {$page}");
    }
    
    /**
     * Create a new Direct
     * @param  string  $a URI
     * @param  callback $b 
     * @return object   Direct Object
     * and so on...
     */
    public static function get($a, $b){
        $get = explode(",", preg_replace("/(.*)\/(\\{(.*)\\})/uiUmx", "$3,", $a));
        array_pop($get);
        return new Direct("/".trim(preg_replace("/(.*)\/(\\{(.*)\\})/uiUmx", "$1", $a), "/"), $b, 'get', $get);
    }
    
    public static function delete($a, $b){
        return new Direct($a, $b, 'delete');
    }
    
    public static function put($a, $b){
        return new Direct($a, $b, 'put');
    }
    
    public static function update($a, $b){
        return new Direct($a, $b, 'update');
    }
   
    public static function post($a, $b){
        return new Direct($a, $b, 'post');
    }
    
    public static function err($a, $b){
        return new Direct($a, $b, 'error');
    }
    
    public static function stack($a){
        
    }
    
    protected function setAuth($grade, $callback){
        parent::$routes[$this->type][$this->route]['middleware']['auth'] = ['doAuth' => true, 'grade' => $grade];
        
        if(gettype($callback) == 'function' && $callback != null){
            parent::$routes[$this->type][$this->route]['middleware']['callback'] = $callback;
        }
    }
    
    public function Auth($callback = null){
        $this->setAuth(4, $callback);
    }
    
    public function Mod($callback = null){
        $this->setAuth(2, $callback);
    }
    
    public function Admin($callback = null){
        $this->setAuth(1, $callback);
    }
    
    /**
     * Gets called when a method on \App\Direct does not exist
     * @private
     * @param string $func 
     * @param string $args 
     */
    public function __call($func, $args){
        die($func."(".implode(', ', $args).") is not a method of ".__CLASS__);
    }
    
}