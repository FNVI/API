<?php
use phpunit\framework\TestCase;
use FNVi\Mongo\Action;
//use MongoDB\BSON\UTCDateTime;
/**
 * Description of Action
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class ActionTest extends TestCase{
    
    public function testConstructor(){
        $object = new Action;
        $this->assertObjectHasAttribute("timestamp", $object);
    }
    
}
