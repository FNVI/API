<?php
namespace FNVi\Mongo;

/**
 * Description of Status
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Status {
    public $name;
    public $order;
    
    public function __construct($name, $order = null) {
        $this->name = $name;
        if($order){
            $this->order = $order;
        }
    }
}
