<?php

namespace FNVi\API\Tools;

/**
 * Description of Aggregate
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Aggregate {
    
    private $pipeline = [];
    /**
     *
     * @var \MongoDB\Collection
     */
    private $collection;
    
    public function __construct($collection) {
        $this->collection = $collection;
    }
    
    private function add($operator,$value){
        $this->pipeline[][$operator] = $value;
        return $this;
    }
    
    public function project($fields){
        return $this->add('$project', $fields);
    }
    
    public function match($query) {
        return $this->add('$match', $query);
    }
    
    public function group($fields){
        return $this->add('$group', $fields);
    }

    public function sort($fields){
        return $this->add('$sort', $fields);
    }
    
    public function unwind($field){
        return $this->add('$unwind', $field);
    }

    public function skip($number){
        return $this->add('$skip', $number);
    }
    
    public function limit($number){
        return $this->add('$limit', $number);
    }
    
    public function execute($options = []){
        $this->collection->aggregate($this->pipeline, $options);
    }
    
    public function getPipeline(){
        return $this->pipeline;
    }
}
