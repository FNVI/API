<?php

namespace FNVi\Mongo;
use MongoDB\BSON\Persistable as Persistable;
/**
 * The base class in which documents, subdocuments and schemas are derived
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class BSON implements Persistable{

    protected static $strict = false;

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
        foreach((self::$strict ? $this->keys() : array_keys($data)) as $key){
            if(isset($data[$key]) && $key !== '__pclass' && $key !== 'strict'){
                $this->{$key} = $data[$key];
            }
        }
    }
    
    /**
     * Gets the keys/properties of the current object
     * @param array $exclude Names of keys to exclude from the result
     * @return array A list of keys/properties of the object
     */
    public function keys(array $exclude = []){
        $exclude[] = "strict";
        return array_values(array_diff(array_keys($this->getVars($this)),$exclude));
    }
    
    /**
     * Return the current object as an array
     * @param array $include Names of keys/properties to specifically include
     * @param array $exclude Names of keys/properties to specifically exclude
     * @return array The current object represented as a key/value array
     */
    public function toArray(array $include = [], array $exclude = []){
        $exclude[] = "strict";
        if($include === [] && $exclude === []){
            return array_filter($this->getVars($this),[$this,"arrayFilter"]);
        } elseif($include === []){
            return array_filter(array_diff_key($this->getVars($this), array_flip($exclude)),[$this,"arrayFilter"]);
        }  else {
            return array_filter(array_intersect_key($this->getVars($this), array_flip(array_diff($include, $exclude))),[$this,"arrayFilter"]);
        }
    }
    
    private function getVars(){
        return self::$strict ? get_class_vars(get_class($this)) : get_object_vars($this);
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
    
    public static function SetStrict($boolean){
        self::$strict = $boolean;
    }
    
}
