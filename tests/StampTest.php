<?php

use PHPUnit\Framework\TestCase;
use MongoDB\BSON\ObjectID;
use FNVi\Mongo\Stamp;
/**
 * Description of StampTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class StampTest extends TestCase{
    
    public function testConstructor(){
        $data = [
            "_id"=>new ObjectID(),
            "test"=>"value"
        ];
        
        $stamp = new Stamp($data);
        $this->assertEquals($data, (array)$stamp);
    }
    
}
