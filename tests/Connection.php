<?php
use phpunit\framework\TestCase;
use FNVi\Mongo\Collection;

define("MONGOURI","mongodb://localhost");
define("DATABASE","testdb");

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
