<?php

namespace FNVi\Mongo;

use MongoDB\BSON\ObjectID;

/**
 * Description of Document 
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class Document extends BSON{
    
    /**
     *
     * @var ObjectID
     */
    public $_id;
    
    public function __construct() {
        $this->_id = new ObjectID();
    }
    
    public function setId($id){
        $this->_id = new ObjectID($id);
    }
    
    /**
     * 
     * @return ObjectID
     */
    public function getId(){
        return $this->_id;
    }
    
    /**
     * Returns a small portion of the object as an array
     * @param array $fields Names of fields to include
     * @return array 
     */
    public function stamp(array $fields = []){
        return $this->toArray(array_merge(["_id"],$fields));
    }
}
