<?php

namespace FNVi\API;

use \Iterator;
/**
 * Description of Query
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Query extends Collection implements Iterator{
    
    private $query = [];
    /**
     *
     * @var \MongoDB\Driver\Cursor 
     */
    private $cursor;
    private $options = [];
    
    public function __construct($collection = "", $query = []) {
        $this->query = $query;
        parent::__construct($collection);
        
    }
    
    public function limit($number){
        $this->options += ["limit"=>$number];
    }
    
    public function skip($number){
        $this->options += ["skip"=>$number];
    }
    
    public function sort($options){
        $this->options += $options;
    }
    
    public function subset($query = []){
        return new Query($this->collectionName, $query += $this->query);
    }
    
    public function current() {
        return $this->cursor->current();
    }

    public function key() {
        $this->cursor->key();
    }

    public function next() {
        return $this->cursor->next();
    }

    public function rewind() {
        $this->cursor = new \IteratorIterator($this->find($this->query,[]));
        return $this->cursor->rewind();
    }

    public function valid() {
        return $this->cursor->valid();
    }

}
