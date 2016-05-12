<?php

namespace FNVi\Mongo;

use MongoDB\BSON\ObjectID;

/**
 * Description of Document 
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class Document extends BSON{
    
    protected $_id;
    
    public function __construct() {
        $this->_id = new ObjectID();
    }
    
    public function setId($id){
        $this->_id = new ObjectID($id);
    }
    
    public function getId(){
        return $this->_id;
    }
}
