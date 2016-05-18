<?php
namespace FNVi\Mongo;

/**
 * Description of Status
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Status extends SubDocument{
    public $name;
    public $order;
    
    public function __construct($name, $order = null) {
        $this->name = $name;
        if($order){
            $this->order = $order;
        }
    }
    
    public function __toString() {
        return $this->name;
    }
}
