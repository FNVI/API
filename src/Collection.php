<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FNVi\API;
use FNVi\API\Tools\Update;

/**
 * Represents a MongoDB collection
 * 
 * This class is a wrapper for the default MongoDB collection class. This allows
 * the extension of new methods as well as giving access control on its methods.
 * This allows 
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Collection extends Database {
    
    /**
     * The collection object
     * @var \MongoDB\Collection
     */
    protected $collection;
    
    /**
     * The collection name.
     * (to be used when requested later)
     * @var String
     */
    protected $collectionName;
    
    /**
     * Creates the collection object.
     * 
     * The name of the collection will generally be set by the name of the derived class
     * in a similar way to the node.js Mongoose module. If this is not the desired 
     * functionality then a class name must be provided.
     * @param String $collection
     */
    public function __construct($collection = "") {
                
        $this->collectionName = $collection !== "" ? $collection : $this->getCollectionName();
        $this->collection = $this->setCollection($this->collectionName);
    }
    
    /**
     * Gets a query object.
     * 
     * For further details on the query object see its entry in the documentation.
     * 
     * @param array $query
     * @return \FNVi\API\Query
     */
    protected function query($query = []){
        return new Query($this->collectionName, $query);
    }
    
    /**
     * Wrapper for the aggregate function
     * 
     * @param array $pipeline
     * @param array $options
     * @return Traversible
     */
    public function aggregate($pipeline, $options = []){
        return $this->collection->aggregate($pipeline, $options);
    }
    
    /**
     * Wrapper for the count function
     * 
     * @param array $query
     * @param array $options
     * @return integer
     */
    public function count($query, $options = []){
        return $this->collection->count($query, $options);
    }
    
    /**
     * Wrapper for the deleteMany function
     * 
     * This one is protected as this function should hopefully be controlled
     * within the API
     * 
     * @param array $query
     * @param array $options
     * @return MongoDB\DeleteResult
     */
    protected function deleteMany($query, $options = []){
        return $this->collection->deleteMany($query, $options);
    }
    
    /**
     * Wrapper for the deleteOne function
     * 
     * @param array $query
     * @param array $options
     * @return MongoDB\DeleteResult
     */
    protected function deleteOne($query, $options) {
        return $this->collection->deleteOne($query, $options);
    }
    
    /**
     * Wrapper for the distinct function
     * 
     * @param string $fieldName
     * @param array $query
     * @param array $options
     * @return array
     */
    public function distinct($fieldName, $query = [], $options = []){
        return $this->collection->distinct($fieldName, $query, $options);
    }
    
    /**
     * Wrapper for the find function
     * 
     * @param array $query
     * @param array $options
     * @return MongoDB\Driver\Cursor
     */
    public function find($query = [], $options = []){
        return $this->collection->find($query, $options);
    }
    
    /**
     * Wrapper for the findOne function
     * 
     * @param array $query
     * @param array $options
     * @return object
     */
    public function findOne($query, $options = []){
        return $this->collection->findOne($query, $options);
    }
    
    /**
     * Wrapper for the findOneAndDelete function
     * 
     * @param array $query
     * @param array $options
     * @return MongoDB\DeleteResult
     */
    public function findOneAndDelete($query, $options=[]){
        return $this->collection->findOneAndDelete($query, $options);
    }
    
    /**
     * Wrapper for the findOneAndReplace function
     * 
     * @param array $query
     * @param array $replacement
     * @param array $options
     * @return object
     */
    public function findOneAndReplace($query, $replacement, $options = [] ){
        return $this->collection->findOneAndReplace($query, $replacement, $options);
    }
    
    /**
     * Wrapper for the findOneAndUpdate function
     * 
     * @param array $query
     * @param array $update
     * @param array $options
     * @return object
     */
    public function findOneAndUpdate($query, $update, $options = []){
        return $this->collection->findOneAndUpdate($query, $update, $options);
    }
    
    /**
     * Wrapper for the insertMany function
     * 
     * @param array $documents
     * @param array $options
     * @return MongoDB\InsertManyResult
     */
    public function insertMany($documents, $options = []){
        return $this->collection->insertMany($documents, $options);
    }
    
    /**
     * Wrapper for the insertOne function
     * 
     * @param array|object $document
     * @param array $options
     * @return MongoDB\InsertOneResult
     */
    public function insertOne($document, $options = []){
        return $this->collection->insertOne($document, $options);
    }
    
    /**
     * Wrapper for the listIndexes function
     * 
     * @param array $options
     * @return MongoDB\Model\IndexInfoIterator
     */
    protected function listIndexes($options = []){
        return $this->collection->listIndexes($options);
    }

    /**
     * Wrapper for the updateMany function
     * @param type $query
     * @return Update
     */
    public function updateMany($query = []){
        return new Update($this->collection, $query);
    }
    
    /**
     * 
     * @param type $query
     * @return Update
     */
    public function updateOne($query = []){
        return new Update($this->collection, $query, true);
    }
    
    /**
     * 
     * @return type
     */
    public function getCollectionName(){
        $string = array_pop(explode('\\',  strtolower(get_called_class())));
        return substr($string, -1) === "s" ? $string : $string."s";
    }
    
}
