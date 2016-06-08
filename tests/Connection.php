<?php
use phpunit\framework\TestCase;
use FNVi\Mongo\Collection;

define("DATABASE","mongodb://localhost");

/**
 * Description of Connection
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Connection extends TestCase{
    
    private $collection;
    
    public function testDBConnection(){
        $this->collection = new Collection("testCollection");
    }
    
}
