<?php
namespace App\Controllers;

use View, Direct, Route; // Routing
use Taxon, Csv, Maps; // APIs
use BaseController, Migrations, Row;
use App\Api\Populate as pop;

use Recipie;

/**
 * making a view with/without variables to render
 * @return object View
 */
class MainController extends BaseController {

    public function test(){

        $recipies = $this->query('SELECT r.*, i.big as image, i.small as thumbnail FROM recipies AS r
        INNER JOIN image AS i ON r.image = i.id')->fetchAll();
        
        foreach($recipies as &$recipie){
            $recipie = new Recipie($recipie);
        }
        
        return View::make('index', [
            'food' => $recipies,
        ]);
    }
    
    public function about() {

    	return View::make('about');
    }

    public function species() {

    	return View::make('taxons', [
            'taxon' => $this->query('SELECT * FROM blacklist WHERE taxonID = :a OR taxonID = :b OR taxonID = :c OR taxonID = :d OR taxonID = :e OR taxonID = :f', [
                'a' => 60303,
                'b' => 14365,
                'c' => 84141,
                'd' => 38890,
                'e' => 26171,
                'f' => 3457,
            ])->fetchAll(),
        ]);
    }


    public function specie($p) {
        
        $recipies = $this->query('SELECT r.*, im.big as image, im.small as thumbnail FROM recipies as r
            INNER JOIN ingredients as i ON i.recipie_id = r.id
            INNER JOIN image as im ON r.image = im.id
            WHERE i.taxonID = :taxon',['taxon' => $p['taxon']])->fetchAll();
        
        foreach($recipies as &$recipie){
            $recipie = new Recipie($recipie);
        }
        
    	return View::make('taxon', [
            'taxon' => $this->query('SELECT * FROM blacklist WHERE taxonID = :a', [
                'a' => $p['taxon']
            ])->fetch(),
            'oppskrift' => $recipies,
        ]);
    }
}
