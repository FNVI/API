<?php

use FNVi\Mongo\BSON;
use MongoDB\BSON\ObjectID;

namespace FNVi\Mongo;

/**
 * Description of Stamp
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Stamp extends BSON {
    
    protected static $strict = false;
    
    /**
     *
     * @var ObjectID
     */
    public $_id;
    
    public function __construct(array $data) {
        $this->bsonUnserialize($data);
    }
    
}
