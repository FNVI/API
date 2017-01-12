<?php

namespace FNVi\Mongo\Tools;
use FNVi\Mongo\Collection;

/**
 * Description of Update
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Update {
    
    private $query = [];
    private $update = [];
    /**
     * @var Collection A collection object to use
     */
    private $collection;
    
    /**
     * @param Collection $collection
     * @param type $query
     */
    public function __construct(Collection $collection = null, $query = []) {
        $this->query = $query;
        $this->collection = $collection;
    }
    
    private function addOperator($operator, $value){
       if(isset($this->update[$operator]))
        {
            if(is_array($this->update[$operator])){
                $this->update[$operator] += $value;
            }
            else
            {
                throw new Exception("Already set $operator operator");
            }
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
    
    public function addToSet($field, $value, $each = false){
        return $this->addOperator('$addToSet', [$field=>$each ? ['$each'=>$value] : $value]);
    }
    
    public function pop($field, $first = false){
        return $this->addOperator('$pop', [$field=>$first ? -1 : 1]);
    }
    
    public function pullAll($field, array $values){
        return $this->pullAll('$pullAll', [$field=>$values]);
    }
    
    public function pull($field, $value){
        return $this->addOperator('$pull', [$field=>$value]);
    }
        
    public function push($field, $value, $each = false, $sort = null, $slice = null, $position = null){
        if(!$each){
            return $this->addOperator('$push', [$field=>$value]);
        } else {
            $update = ['$each'=>$value];
            if($sort !== null){
                $update += ['$sort'=>$sort];
            }
            if($slice !== null){
                $update += ['$slice'=>$slice];
            }
            if($position !== null){
                $update += ['$position'=>$position];
            }
            return $this->addOperator('$push', [$field=>$update]);
        }
    }
    
    public function updateOne(array $options = []){
        return $this->collection->updateOne($this->query, $this->update, $options);
    }
    
    public function updateMany(array $options = []){
        return $this->collection->updateMany($this->query, $this->update, $options);
    }
    
//    public function remove(){
//        $this->query["active"] = true;
//        return $this->set(["active"=>false]);
//    }
//    
//    public function recover(){
//        $this->query["active"] = false;
//        return $this->set(["active"=>true]);
//    }
    
    public function getQuery(){
        return $this->query;
    }
    
    public function getUpdate(){
        return $this->update;
    }
}
