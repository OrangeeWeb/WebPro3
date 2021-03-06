<?php

namespace App\Modules;

use DB;

class Comment{

    public $id;
    public $user_id;
    public $content;
    public $recipe_id;

    public $ingredients = [];
    public $user = null;

    public function __construct($query = null){
        if($query != null){
            $this->id           = $query['id'];
            $this->user_id      = $query['user_id'];
            $this->content      = $query['content'];
            $this->recipe_id    = $query['recipe_id'];
        }
    }

    /**
     * Get the poster from a comment
     * @author Agne *degaard
     * @return object
     */
    public function user() {
        if($this->user != null) return $this->user;
        $this->user =  new User($this->user_id);   
        return $this->user;
    }

    /**
     * Get a recipe from a comment
     * @author Agne *degaard
     * @return object
     */
    public function getRecipe(){
      return new Recipie($this->user_id);
    }

}


