<?php

namespace FNVi\Mongo;
use MongoDB\BSON\Persistable as Persistable;
/**
 * The base class in which documents, subdocuments and schemas are derived
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class BSON implements Persistable{

    /**
     * Serializes the object to an array
     * @return array
     */
    public function bsonSerialize()
    {
        return $this->toArray();
    }
    
    /**
     * Unserializes the data from an array to an object
     * @param array $data
     */
    public function bsonUnserialize(array $data)
    {
        foreach(array_keys($data) as $key){
            if(isset($data[$key]) && $key !== '__pclass'){
                $this->{$key} = $data[$key];
            }
        }
    }
    
    /**
     * Gets the keys/properties of the current object
     * @param array $exclude Names of keys to exclude from the result
     * @return array A list of keys/properties of the object
     */
    protected function keys(array $exclude = []){
        return array_values(array_diff(array_keys(get_object_vars($this)),$exclude));
    }
    
    /**
     * Return the current object as an array
     * @param array $include Names of keys/properties to specifically include
     * @param array $exclude Names of keys/properties to specifically exclude
     * @return array The current object represented as a key/value array
     */
    public function toArray(array $include = [], array $exclude = []){
        if($include === [] && $exclude === []){
            return array_filter(get_object_vars($this),[$this,"arrayFilter"]);
        } elseif($include === []){
            return array_filter(array_diff_key(get_object_vars($this), array_flip($exclude)),[$this,"arrayFilter"]);
        }  else {
            return array_filter(array_intersect_key(get_object_vars($this), array_flip(array_diff($include, $exclude))),[$this,"arrayFilter"]);
        }
    }
    
    /**
     * A filter to verify null values
     * 
     * When filtering the object as an array, most filters won't pass anything that acts as a false value,
     * this includes false, 0 and empty strings. We currently only require that empty strings and null values be filtered out.
     * 
     * @param mixed $var
     * @return bool
     */
    protected function arrayFilter($var){
        return ($var !== NULL && $var !== '');
    }
    
    /**
     * Returns the current object as JSON text
     * @return string The current object as JSON text
     */
    public function __toString() {
        return json_encode($this->toArray(), 128);
    }
    

    
}
