<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FNVi\Mongo;

use FNVi\Mongo\Collection;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;

/**
 * Description of Schema
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class Schema extends Document {
    
    /**
     *
     * @var FNVi\Mongo\Collection 
     */
    private $collection;
    
    protected $active = true;

    public function __construct($collection = "") {
        
        $name = $collection !== "" ? $collection : $this->className();
        $this->collection = new Collection($name);
        parent::__construct();
    }
    
    /**
     * 
     * @return \FNVi\Mongo\Collection
     */
//    public function collection(){
//        return $this->collection->collection();
//    }
    
    public function className(){
        return array_pop(explode('\\',  strtolower(get_class($this))))."s";
    }
    
    public static function find($query){
        return self::collection()->find($query);
    }
    
    public static function getClass(){
        $string = array_pop(explode('\\',  strtolower(get_called_class())));
        return substr($string, -1) === "s" ? $string : $string."s";
    }
    
    static private function collection($name = ""){
        if(!self::$coll)
        {
            self::$coll = new Collection(self::getClass());
        }
        return self::$coll;
    }
    
    public function delete() {
        return $this->collection->removeOne(["_id"=>$this->_id]);
    }
    
    public function recover(){
        return $this->collection->recoverOne(["_id"=>$this->_id]);
    }
    
    public function store(){
        return $this->collection->findOneAndReplace(["_id"=>$this->_id], $this, ["upsert"=>true]);
    }
    
    protected static function loadFromID($id){
        return $this->collection()->findOne(["_id"=>new ObjectID($id."")]);
    }

    protected function timestamp(){
        return new UTCDateTime(time() * 1000);
    }
    
}
