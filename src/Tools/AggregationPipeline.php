<?php
namespace FNVi\Mongo\Tools;

use FNVi\Mongo\Collection;

/**
 * Description of Pipeline
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class AggregationPipeline {

    private $pipeline = array();
    private $collection;

    /**
     * 
     * @param Collection $collection
     */
    public function __construct(Collection $collection) {
        $this->collection = $collection;
    }

    /**
     * 
     * @param array $options
     * @return Traversable
     */
    public function execute(array $options = []){
        return $this->getCursor($options);
    }
    
    /**
     * @param array $options Options for the aggregation function
     * @return Traversable
     */
    public function getCursor(array $options = []) {
        return $this->collection->aggregate($this->pipeline, $options);
    }

    /**
     * 
     * @return array
     */
    public function getResultAsArray(array $options = []) {
        return $this->collection->aggregate($this->pipeline, $options)->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
    }

    /**
     * 
     * @return array
     */
    public function getOne(array $options = []) {
        $cursor = $this->collection->aggregate($this->pipeline, $options);
        $iterator = new \IteratorIterator($cursor);
        $iterator->rewind();
        return $iterator->current();
    }

    /**
     * 
     * @param type $operator
     * @param type $value
     * @return AggregationPipeline
     */
    private function add($operator, $value){
        if($value !== [] && $value !== null){
            $this->pipeline[][$operator] = $value;
        }
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getPipeline() {
        return $this->pipeline;
    }

    /**
     * 
     * @param array $query
     * @return AggregationPipeline
     */
    public function match(array $query) {
        if (count($query)) {
            return $this->add('$match', $query);
        }
        return $this;
    }

    /**
     * 
     * @param array $fields
     * @return AggregationPipeline
     */
    public function project(array $fields) {
        return $this->add('$project', $fields);
    }

    /**
     * 
     * @param type $fields
     * @return AggregationPipeline
     */
    public function group($fields) {
        return $this->add('$group', $fields);
    }
    
    /**
     * 
     * @param array $options
     * @return AggregationPipeline
     */
    public function redact(array $options){
        return $this->add('$redact', $options);
    }

    /**
     * 
     * @param array $fields
     * @return AggregationPipeline
     */
    public function sort(array $fields) {
        return $this->add('$sort', $fields);
    }

    /**
     * 
     * @param string $field
     * @param string $includeArrayIndex
     * @param bool $preserveNullAndEmptyArrays
     * @return AggregationPipeline
     */
    public function unwind($field, $includeArrayIndex = null, $preserveNullAndEmptyArrays = false) {
        return $this->add('$unwind', $includeArrayIndex ? ["path"=>$field, "includeArrayIndex"=>$includeArrayIndex, "preserveNullAndEmptyArrays"=>$preserveNullAndEmptyArrays] : $field);
    }

    /**
     * 
     * @param int $number
     * @return AggregationPipeline
     */
    public function skip($number) {
        return $this->add('$skip', $number);
    }
    
    /**
     * 
     * @param array $options
     * @return AggregationPipeline
     */
    public function geoNear(array $options){
        return $this->add('$geoNear', $options);
    }
    
    /**
     * 
     * @param string $collection
     * @return AggregationPipeline
     */
    public function out($collection){
        return $this->add('$out', $collection);
    }
    
    /**
     * 
     * @param string $from
     * @param string $localField
     * @param string $foreignField
     * @param string $as
     * @return AggregationPipeline
     */
    public function lookup($from, $localField, $foreignField, $as){
        return $this->add('$lookup', ["from"=>$from, "localField"=>$localField, "foreignField"=>$foreignField, "as"=>$as]);
    }
    
    /**
     * 
     * @param int $size
     * @return AggregationPipeline
     */
    public function sample($size){
        return $this->add('$sample', ["size"=>$size]);
    }

    /**
     * 
     * @param int $number
     * @return AggregationPipeline
     */
    public function limit($number) {
        return $this->add('$limit', $number);
    }
    
    /**
     * 
     * @param string $value
     * @param string $total
     * @return array
     */
    public function percentage($value ,$total = '$total'){
        return [
            '$multiply'=>[[
                '$divide'=>[
                    100,
                    $total
                ]],
                $value
            ]
        ];
    }
}
