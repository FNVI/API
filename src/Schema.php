<?php

namespace FNVi\Mongo;

use FNVi\Mongo\Collection;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;

/**
 * Description of Schema
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Schema extends Document {
    
    /**
     * Provides basic access to the collection this Schema works with
     * @var FNVi\Mongo\Collection 
     */
    public $collection;
    
    /**
     * Provides the name of the collection this Schema works with
     * @var string The name of the collection
     */
    protected $collectionName;
    
    /**
     * 
     * @param string $collection
     */
    public function __construct($collection = "") {
        $this->collectionName = $collection;
        $name = $collection !== "" ? $collection : $this->className();
        $this->collection = new Collection($name);
        parent::__construct();
    }
    
    /**
     * Returns the name of the schema. This may be unused in future, but is still here as a backup if a collection name isn't provided
     * @return string
     */
    public function className(){
        $array = explode('\\',  strtolower(get_class($this)));
        return array_pop($array)."s";
    }
        
    /**
     * Returns the name of the Schema. This may be unused in future, but is still here as a backup if a collection name isn't provided
     * @return string
     */
    public static function getClass(){
        $array = explode('\\',  strtolower(get_called_class()));
        $string = array_pop($array);
        return substr($string, -1) === "s" ? $string : $string."s";
    }
    
    /**
     * Removes the current item from the collection specified in the schema
     * @return MongoDB\UpdateResult
     */
    public function delete() {
        return $this->collection->removeOne(["_id"=>$this->_id]);
    }
    
    /**
     * Recovers the current item in the collection specified in the schema (if the document was marked inactive)
     * @return MongoDB\UpdateResult
     */
    public function recover(){
        return $this->collection->recoverOne(["_id"=>$this->_id]);
    }
    
    /**
     * Stores the current item in the collection specified in the schema
     * @return MongoDB\UpdateResult
     */
    public function store(){
        return $this->collection->findOneAndReplace(["_id"=>$this->_id], $this, ["upsert"=>true]);
    }
    
    protected static function loadFromID($id){
        return $this->collection->findOne(["_id"=>new ObjectID($id."")]);
    }

    /**
     * Returns the current time in the correct Mongo type
     * @return UTCDateTime
     */
    protected function timestamp(){
        return new UTCDateTime(time() * 1000);
    }
    
    public function toArray(array $include = [], array $exclude = []) {
        return parent::toArray($include, array_merge($exclude,["collection", "collectionName"]));
    }
    
    public function bsonUnserialize(array $data) {
        $this->collection = new Collection($this->collectionName ? $this->collectionName : $this->className());
        parent::bsonUnserialize($data);
    }
    
    
    public function keys(array $exclude = []) {
        return parent::keys(array_merge($exclude, ["collection", "collectionName"]));
    }
    
    public static function getProperties(){
        return array_keys(get_class_vars(get_called_class()));
    }
}
