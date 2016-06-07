<?php
use phpunit\framework\TestCase;
use FNVi\Mongo\Action;
/**
 * Description of Action
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class ActionTest extends TestCase{
    
    public $object;
    
    public function create(){
        $this->object = new Action("by","notes","to");
    }
    
    public function createsTimestamp(){
        $object = new Action;
        $this->assertObjectHasAttribute("timestamp", $object);
    }
    
}
