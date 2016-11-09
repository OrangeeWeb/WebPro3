<?php

namespace App\Routing;

use ErrorHandling;

class RouteHandler{
    
    private $route;
    private $url;
    
    /**
     * get the current url path
     * @author Agne *degaard
     * @return string 
     */
    private function get_path(){
        return "/".preg_replace("/(.*)m=(.*)/uimx", "$2", $_SERVER['QUERY_STRING']);
    }
    
    /**
     * get array variables without key
     * @author Agne *degaard
     * @param  string $path 
     * @return array 
     */
    private function get_vars($path){
        $regex = $this->regexSlash($this->get_current_page());
        $str = preg_replace("/$regex/uimx", '', $this->get_path());
        return explode("/", trim($str, "/"));   
    }
    
    /**
     * Convert / to \/ for regex
     * @author Agne *degaard
     * @param  string   $str
     * @return string
     */
    private function regexSlash($str){
        return preg_replace('/\//uimx', '\\\\/', $str);
    }
    
    /**
     * Find the right page in the route array
     * @author Agne *degaard
     * @return array
     */
    private function get_page(){
        $url = $this->get_path();
        $list = [];
        // Minify this stuff
        foreach(Route::lists() as $type => $types){
            foreach($types as $key => $value){
               if(preg_match("/".$this->regexSlash($key)."/i", $url)){
                   $list[] = $key;
               }
            }
        }
        $lengths = array_map('strlen', $list);
        $maxLength = max($lengths);
        $index = array_search($maxLength, $lengths);
        return ['page' => $list[$index], 'key' => count(explode($list[$index], '/'))];
    }
    
    /**
     * Get the page data from Route.php
     * @author Agne *degaard
     * @return array
     */
    private function get_current_page(){
        return $this->get_page()['page'];
    }
    
    
    /**
     * get the page data from current url
     * @author Agne *degaard
     * @return string
     */
    public function getPageData(){
        $url = $this->get_current_page();
        
        return $this->callController($url);
    }
    
    /**
     * Get the Method to call
     * @author Agne *degaard
     * @return string 
     */
    protected function getMethod(){
        return new $this->view[0];
    }
    
    /**
     * Get the Class to call
     * @author Agne *degaard
     * @return string
     */
    protected function getClass(){
        return $this->view[1];
    }
    
    /**
     * Call the class to the right URL, from RouteSetup.php
     * @author Agne *degaard
     * @param  string   $url
     * @return string
     */
    private function callController($url){
        $this->route = Direct::getCurrentRoute($url);
        
        $this->view = explode('@', $this->route['callback']);
        
        
        if(array_key_exists("error", $this->route)){
            ErrorHandling::fire("View Does not Exist: " . $url,
                                "Please set up a route to 404",
                                ['App/Routing/RouteSetup.php', 
                                 'Direct::err("404", "Controller@method");'
                                ]);
            
            return;
        }
        
        $funcToCall = [$this->getMethod(), $this->getClass()];
        
        $class = @call_user_func($funcToCall, $this->extractVars($url));
        
        if(!$class){
            ErrorHandling::fire("Error", $view[0]."@".$view[1]. " could not execute");
            return;
        }
        
        return $class;
    }
    
    /**
     * Extract Get variables from url, add correct key send them to $_GET
     * @author Agne *degaard
     * @param  integer  $url
     * @return array
     */
    private function extractVars($url){
        if(!empty($this->route['vars'])){
            $vars = $this->get_vars($url);
            
            foreach($this->route['vars'] as $key => $value){
                if(isset($vars[$key])){
                    $_GET[$value] = $vars[$key];
                }
            }
        }
        return array_merge($_GET, $_POST);
        
    }
    
}