<?php

namespace FNVi\Mongo;
use FNVi\Mongo\Tools\Update;
use FNVi\Mongo\Tools\AggregationPipeline;

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
    
//    protected $query = ["active" => true];

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
        $this->collection = $this->selectCollection($this->collectionName);
    }
    
    public function collectionName(){
        return $this->collectionName;
    }
    
    /**
     * Returns a clone of the collection that allows inactive documents are returned
     * @return \FNVi\Mongo\Collection
     */
//    public function includeRemoved(){
//        $output = clone $this;
//        unset($output->query["active"]);
//        return $output;
//    }
    
    /**
     * Returns a clone of the collection where only inactive documents are returned
     * @return \FNVi\Mongo\Collection
     */
//    public function onlyRemoved(){
//        $output = clone $this;
//        $output->query["active"] = false;
//        return $output;
//    }
    
    /**
     * Returns a clone of the collection where only active documents are returned
     * (only implemented as a precaution, as this is the default functionality)
     * @return \FNVi\Mongo\Collection
     */
//    public function onlyActive(){
//        $output = clone $this;
//        $output->query["active"] = true;
//        return $output;
//    }
    
    /**
     * Gets a query object.
     * 
     * For further details on the query object see its entry in the documentation.
     * 
     * @param array $query
     * @return \FNVi\Mongo\Query
     */
    protected function query(array $query = []){
        return new Query($this->collectionName, $query);
    }
    
    /**
     * Wrapper for the aggregate function
     * 
     * @param array $pipeline An aggregation pipeline in array format
     * @param array $options
     * @return Traversable
     */
    public function aggregate(array $pipeline ,array $options = []){
        return $this->collection->aggregate($pipeline, $options);
    }
    
    /**
     * Returns an 
     * @return AggregatePipeline
     */
    public function aggregationPipeline(){
        return new AggregationPipeline($this);
    }
    
    /**
     * Wrapper for the count function
     * 
     * @param array $query
     * @param array $options
     * @return integer
     */
    public function count($query = [], $options = []){
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
    public function deleteMany(array $query = [], array $options = []){
        return $this->collection->deleteMany($query, $options);
    }
    
    /**
     * Wrapper for the deleteOne function
     * 
     * @param array $query
     * @param array $options
     * @return MongoDB\DeleteResult
     */
    public function deleteOne(array $query, array $options = []) {
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
    public function distinct($fieldName, array $query = [], array $options = []){
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
    public function findOne($query = [], $options = []){
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
    public function findOneAndUpdate(array $query, array $update, array $options = []){
        return $this->collection->findOneAndUpdate($query, $update, $options);
    }
    
    /**
     * Wrapper for the insertMany function
     * 
     * @param array $documents
     * @param array $options
     * @return MongoDB\InsertManyResult
     */
    public function insertMany(array $documents, array $options = []){
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
     * Creates an update object.
     * 
     * The update object allows method chaining. This allows for a straightforward
     * way of building the update operators, which coincidentally allows some 
     * methods to be reused. Ideally it should be kept in mind that the updateOne
     * and updateMany need to be called at the end of one of these chains.
     * 
     * @param array $query
     * @return Update
     */
    public function update($query = []){
        return new Update($this->collection, $query);
    }
    
    /**
     * Wrapper for the updateMany function
     * @param type $query
     * @return MongoDB\UpdateResult
     */
    public function updateMany(array $query, $update, array $options = []){
        return $this->collection->updateMany($query, $update, $options);
    }
    
    /**
     * 
     * @param type $query
     * @return MongoDB\UpdateResult
     */
    public function updateOne(array $query, $update, array $options = []){
        return $this->collection->updateOne($query, $update, $options);
    }
    
    /**
     * 
     * @return type
     */
    public function getCollectionName(){
        $array = explode('\\',  strtolower(get_called_class()));
        $string = array_pop($array);
        return substr($string, -1) === "s" ? $string : $string."s";
    }
    
//    public function recoverMany($query){
//        return $this->update($query)->recover()->updateMany();
//    }
//    
//    public function recoverOne($query){
//        return $this->update($query)->recover()->updateMany();
//    }
//    
//    public function removeOne($query){
//        return $this->update($query)->remove()->updateOne();
//    }
//    
//    public function removeMany($query = []){
//        return $this->update($query)->remove()->updateMany();
//    }
//    
//    public function flush(){
//        $this->onlyRemoved()->deleteMany([]);
//    }
}
