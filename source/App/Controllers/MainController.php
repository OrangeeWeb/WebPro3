<?php
namespace App\Controllers;

use View, Direct;

use Recipie;

/**
 * making a view with/without variables to render
 * @return object View
 */
class MainController extends Controller {

    
    public function app(){
        
        return View::make('app');
        
    }
    
    public function error(){
        return View::make('error.404');
    }
    
    public function index(){

        $ratings = $this->query('SELECT r.*, AVG(ra.rating) as rating, i.big as image, i.small as thumbnail
        FROM recipies AS r
        INNER JOIN image AS i ON r.image = i.id
        LEFT JOIN ratings AS ra ON ra.recipe_id = r.id
        GROUP BY r.id
        ORDER BY rating DESC LIMIT 3', 'Recipie')->fetchAll();
        
        $newest = $this->query('SELECT r.*, i.big as image, i.small as thumbnail
        FROM recipies AS r
        INNER JOIN image AS i ON r.image = i.id
        ORDER BY time DESC LIMIT 3', 'Recipie')->fetchAll();
        
        return View::make('index', [
            'ratings' => $ratings,
            'newest' => $newest,
        ]);
    }
    
    public function about() {

    	return View::make('about');
    }
}
