<?php

namespace FNVi\Mongo;

use MongoDB\BSON\Persistable as Persistable;
use MongoDB\BSON\ObjectID;

/**
 * Description of Document 
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class Document implements Persistable{
    
    protected $_id;
    
    public function __construct() {
        $this->_id = new ObjectID();
    }
    
    public function setId($id){
        $this->_id = new ObjectID($id);
    }
    
    public function getId(){
        return $this->_id;
    }
    
    function bsonSerialize()
    {
        return $this->toArray();
    }
    
    function bsonUnserialize(array $data)
    {
        foreach($this->keys() as $key){
            $this->{$key} = $data[$key];
        }
    }
    
    private function keys(){
        return array_keys(get_object_vars($this));
    }
    
    public function toArray($include = [], $exclude = []){
        return array_intersect_key(get_object_vars($this), array_flip(array_diff($include, $exclude)));
    }
    
    public function __toString() {
        return "<pre>".  json_encode($this->toArray(), 128)."</pre>";
    }
    
    public function stamp($fields = []){
        $fields[] = "_id";
        return $this->toArray($fields);
    }
}
