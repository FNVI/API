<?php

namespace FNVi\Mongo;
use MongoDB\Collection as BaseCollection;
use FNVi\Mongo\Tools\Update;
use FNVi\Mongo\Tools\AggregationPipeline;
use FNVi\Mongo\Tools\Query;

/**
 * Represents a MongoDB collection
 * 
 * This class extends the default MongoDB collection class. This allows
 * the extension of new methods such as being able to chain update and 
 * aggregation pipelines
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Collection extends BaseCollection {
      
    /**
     * The collection name.
     * @var String
     */
    protected $collectionName;
    

    /**
     * Creates the collection object.
     * 
     * The name of the collection will generally be set by the name of the derived class
     * in a similar way to the node.js Mongoose module. If this is not the desired 
     * functionality then a collection name must be provided.
     * @param String $collectionName
     */
    public function __construct($collectionName = null) {
        $this->collectionName = $collectionName ?: $this->getCollectionNameFromClass();
        parent::__construct(Database::getManager(), Database::getDatabase(), $this->collectionName);
    }
            
    /**
     * Gets a query object.
     * 
     * For further details on the query object see its entry in the documentation.
     * 
     * @param array $query
     * @return Query
     */
    public function query(array $query = []){
        return new Query($this, $query);
    }
    
    /**
     * Returns an 
     * @return AggregatePipeline
     */
    public function aggregationPipeline(){
        return new AggregationPipeline($this);
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
        return new Update($this, $query);
    }
    
    /**
     * 
     * @return type
     */
    protected function getCollectionNameFromClass(){
        $array = explode('\\',  strtolower(get_called_class()));
        $string = array_pop($array);
        return substr($string, -1) === "s" ? $string : $string."s";
    }
    
}
