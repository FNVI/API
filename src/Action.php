<?php
namespace FNVi\Mongo;
use MongoDB\BSON\UTCDateTime;


/**
 * Description of Action
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Action extends SubDocument{
    
    public $by;
    public $to;
    public $timestamp;
    public $notes;
    
    public function __construct($by = null, $notes = null, $to = null, $timestamp = null) {
        $this->by = $by;
        $this->notes = $notes;
        $this->to = $to;
        $this->timestamp = $timestamp ? $timestamp : $this->timestamp();
    }
    
    private function timestamp(){
        return new UTCDateTime(time() * 1000);
    }
}
