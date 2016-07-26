<?php

namespace FNVi\Mongo;
use MongoDB\BSON\Persistable as Persistable;
/**
 * The base class in which documents, subdocuments and schemas are derived
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class BSON implements Persistable{

    public function bsonSerialize()
    {
        return $this->toArray();
    }
    
    public function bsonUnserialize(array $data)
    {
        foreach($this->keys() as $key){
            if(isset($data[$key])){
                $this->{$key} = $data[$key];
            }
        }
    }
    
    protected function keys($exclude = []){
        return array_values(array_diff(array_keys(get_object_vars($this)),$exclude));
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
