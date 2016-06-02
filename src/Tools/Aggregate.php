<?php

namespace FNVi\Mongo\Tools;

/**
 * Description of Aggregate
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Aggregate implements AggregateInterface{
    
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
    
    public function group($_id, $fields, $keepClass = true){
        if($keepClass){
            $fields[] = "__pclass";
        }
        return $this->add('$group', ["_id"=>$_id, "count"=>['$sum'=>1], "documents"=>['$push'=> self::map($fields)]]);
    }
    
    public function groupBy($_id, $sumFields = []){
        $field = str_replace('$','',array_pop(explode(".", $_id)));
        $sum = $this->sumFields($sumFields);
        $test = Aggregate::map($sumFields);
        return $this->add('$group', $sum += ["_id"=>$_id, "count"=>['$sum'=>1], "documents"=>['$push'=>'$$ROOT']])
                ->project($test += ['_id'=>0,$field=>'$_id',"count"=>'$count',"documents"=>'$documents']);
    }
    
    private function sumFields(array $fields = []){
        $output = [];
        foreach($fields as $field){
            $output[array_pop(explode(".",$field))] = ['$sum'=>'$'.$field];
        }
        return $output;
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
    
    public static function map($fields){
        $out = [];
        foreach($fields as $field){
            $out += [$field=>'$'.array_pop(explode(".", $field))];
        }
        return $out;
    }
}
