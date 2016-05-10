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
    
    protected function keys($exclude = []){
        return array_diff(array_keys(get_object_vars($this)),$exclude);
    }
    
    public function toArray($include = [], $exclude = []){
        if($include === [] && $exclude === []){
            return array_filter(get_object_vars($this),[$this,"arrayFilter"]);
        } elseif($include === []){
            return array_filter(array_diff_key(get_object_vars($this), array_flip($exclude)),[$this,"arrayFilter"]);
        }  else {
            return array_filter(array_intersect_key(get_object_vars($this), array_flip(array_diff($include, $exclude))),[$this,"arrayFilter"]);
        }
    }
    
    protected function arrayFilter($var){
        return ($var !== NULL && $var !== '');
    }
    
    public function __toString() {
        return "<pre>".  json_encode($this->toArray(), 128)."</pre>";
    }
    
    public function stamp($fields = []){
        $fields[] = "_id";
        return $this->toArray($fields);
    }
}
