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
     * Provides basic access to the collection this Schema works with, for 
     * advanced usage, the collection property should be set with the specific 
     * class type.
     * 
     * @var Collection 
     */
    protected $collection;
    
    /**
     *
     * @var Collection
     */
    protected static $collectionStatic;


    /**
     * Provides the name of the collection this Schema works with
     * @var string The name of the collection
     */
    protected static $collectionName;
    
    /**
     * 
     * @param Collection $collection
     */
    public function __construct(Collection $collection = null) {
        self::$collectionName = $collection ? $collection->getCollectionName() : null;
        $this->collection = $collection ?: self::Collection();
        parent::__construct();
    }
        
    private static function Collection(){
        if(!self::$collectionStatic){
            self::$collectionStatic = new Collection(self::$collectionName ?: self::getClass());
        }
        return self::$collectionStatic;
    }
        
    /**
     * Returns the name of the Schema. This may be unused in future, but is still here as a backup if a collection name isn't provided
     * @return string
     */
    private static function getClass(){
        $array = explode('\\',  strtolower(get_called_class()));
        $string = array_pop($array);
        return substr($string, -1) === "s" ? $string : $string."s";
    }
    
    /**
     * Removes the current item from the collection specified in the schema
     * @return \MongoDB\DeleteResult
     */
    public function delete() {
        return $this->collection->deleteOne(["_id"=>$this->_id]);
    }
       
    /**
     * Saves the current item in the collection specified in the schema
     * @return \MongoDB\UpdateResult
     */
    public function save(){
        return $this->collection->findOneAndReplace(["_id"=>$this->_id], $this, ["upsert"=>true]);
    }
    
    public static function loadFromID($id){
        $collection = self::Collection();
        return $collection->findOne(["_id"=>new ObjectID($id."")]);
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
        $this->collection = self::Collection();
        parent::bsonUnserialize($data);
    }
    
    
    public function keys(array $exclude = []) {
        return parent::keys(array_merge($exclude, ["collection", "collectionName"]));
    }
    
    public static function getProperties(){
        return array_keys(get_class_vars(get_called_class()));
    }
    
    public function getCollectionName(){
        return $this->collection->getCollectionName();
    }
}
