<?php

namespace App\Database;
use Config;
use PDO;

class Database {
    public static $db;
    public static $table;

    /**
     * Init Database connection
     * @private
     * @param string $class Class called that extends Modul in \App\Modul
     */
    public function __construct(){
        try {

            $dns = 'mysql:host='.Config::$host;
            $dns .= ';dbname='.Config::$database;
            self::$db = new PDO($dns, Config::$username, Config::$password);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            //self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        } catch (PDOException $e) {
             die('Could not connect to Database'. $e);
        }
    }

    public static function do(){
      return new self();
    }

    /**
     * Bind Values to PDO prepare
     * @param object   &$query
     * @param array  &$args
     */
    public static function arrayBinder(&$query, &$args) {
        foreach ($args as $key => $value) {
            $query->bindValue(':'.$key, htmlspecialchars($value));
        }
	}

    /**
     * Execute a PDO mysql Query
     * @param  string   $sql
     * @param  array    [$args                 = null]
     * @return object
     */
    public static function query($sql, $args = null){
        $query = self::$db->prepare($sql);
        if($args !== null){
            self::arrayBinder($query, $args);
        }
        return $query->execute() ? $query : false;
    }
    //db::query("SELECT * from table where row = :value", ['value' => 'hei'])
    /**
     * Select * from class
     * @param  array  [$rows                  = ['*']]
     * @return object Table[Row object] Object
     */
    public static function all($rows = ['*'], $table = null){
        return self::query('SELECT '.implode(', ', $rows).' FROM '.$table)->fetchAll();
    }

    
    
    public static function select($rows = ['*'], $table = null, $data = null, $join = 'AND'){
        $args = null;
        if($data != null){
            $arg = [];
            $args = $data;
            foreach($data as $key => $value){
                $arg[] = "$key = :$key";
            }
            $where = " WHERE ".implode(" $join ", $args);
        } else {
            $where = "";
        }
        
        return self::query('SELECT '.implode(', ', $rows).' FROM '.$table.$where, $args)->fetchAll();
    }
    

    /**
     * Delete a row from a table
     * @param  string       [$col = 'id']   col name
     * @param  string       [$val = 0]      Value of col to delete
     * @param  string       [$table = null] Table name
     * @return object/false
     */
    public static function deleteWhere($col = 'id', $val = 0, $table = null){
        return self::query("DELETE FROM {$table} WHERE {$col} = :val", ['val' => $val]);
    }

    /**
     * clear a table
     * @param  string  $table MySQL table
     * @return boolean
     */
    public static function clearTable($table){
         return self::query("DELETE from $table");
    }

    public function clearOut(){
        $this->query('DROP DATABASE IF EXISTS '.Config::$database);

        if($this->query('CREATE DATABASE IF NOT EXISTS `'.Config::$database.'` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; USE `'.Config::$database.'`;')){
            return true;
        }
    }

    /**
     * create a new table
     * @param  string  $name table name
     * @param  array   $rows arrow of Row objects
     * @return boolean
     */
    public function createTable($name, array $rows){
        $query = "CREATE TABLE `".$name."` (";
        $row_arr = [];
        foreach($rows as $key => $row){
             $row_arr[] = $row->toString();
        }

        $query .= implode(", ", $row_arr);
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        //die($query);
        return $this->query($query);

    }

    /**
     * covert variables types to sql variable types
     * @author Agne *degaard
     * @param  string $type [[Description]]
     * @return string string
     */
    protected static function types($type){
        $types = [
            'int' => 'int(11)',
            'varchar' => 'varchar(255)',
            'tinyint' => 'tinyint(1)',
            'boolean' => 'tinyint(1)',
        ];

        return array_key_exists($type, $types) ? $types[$type] : $type;
    }

    /**
     * insert one row to table
     * @param  array  array $data
     * @param  string [$table = null] MySQL table
     * @return boo
     */
    public static function insert(array $data, $table = null){
        $trows = [];
        $placeholder = [];
        $values = [];
        $insertData = [];
        foreach($data[0] as $key => $value){
            $trows[] = $key;
        }

        foreach($data as $nr => $rows){
            $p = [];
            foreach($rows as $key => $row){
                $p[] = ":".$key.$nr;
                $insertData[$key.$nr] = $row;
            }
            $placeholder[] = '('.implode(", ", $p).')';
        }

        $trows = implode(", ", $trows);
        $placeholder = implode(", ", $placeholder);
        //return [("INSERT INTO {$table} ({$trows}) VALUES {$placeholder}"), $insertData];
        return self::query("INSERT INTO {$table} ({$trows}) VALUES {$placeholder}", $insertData);
    }
}