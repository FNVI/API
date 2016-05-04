<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FNVi\Mongo\Tools;

/**
 * Description of Update
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Update {
    
    private $query = [];
    private $update = [];
    private $single = false;
    private $upsert = false;
    /**
     *
     * @var \MongoDB\Collection
     */
    private $collection;
    
    /**
     * @param \MongoDB\Collection $collection
     * @param type $query
     */
    public function __construct($collection, $query = []) {
        $this->query = $query;
        $this->collection = $collection;
    }
    
    private function addOperator($operator, $value){
        if(is_array($this->update[$operator]))
        {
            $this->update[$operator] += $value;
        }
        else
        {
            $this->update[$operator] = $value;
        }
        return $this;
    }
    
    public function addToQuery($query){
        $this->query += $query;
        return $this;
    }
    
    public function set($fields) {
        return $this->addOperator('$set', $fields);
    }
    
    public function inc($field, $number = 1){
        return $this->addOperator('$inc',[$field=>$number]);
    }
    
    public function mul($field, $number){
        return $this->addOperator('$mul',[$field=>$number]);
    }
    
    public function rename($fields){
        return $this->addOperator('$rename',$fields);
    }
    
    public function setOnInsert($values){
        return $this->addOperator('$setOnInsert',$values);
    }
    
    public function clear($fields){
        return $this->addOperator('$unset',array_combine($fields,array_fill(0, count($fields),"")));
    }
    
    public function min($field, $value){
        return $this->addOperator('$min',[$field=>$value]);
    }
    
    public function currentDate($field, $type=true){
        return $this->addOperator('$currentDate',[$field=>$type]);
    }
    
    public function updateOne($options = []){
        return $this->collection->updateOne($this->query, $this->update, $options);
    }
    
    public function updateMany($options = []){
        return $this->collection->updateMany($this->query, $this->update, $options);
    }
    
    public function remove(){
        $this->query += ["active"=>true];
        return $this->set(["active"=>false]);
    }
    
    public function recover(){
        $this->query += ["active"=>false];
        return $this->set(["active"=>true]);
    }
}
