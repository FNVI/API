<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Database;
use FNVi\Mongo\Collection;
use FNVi\Mongo\Schema;
/**
 * Description of SchemaTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class SchemaTest extends TestCase{
    
    /**
     * An FNVi Schema class
     * @var Schema
     */
    protected $schema;

    public static function setUpBeforeClass() {
        Database::connect("mongodb://localhost/testdb");
    }
    
    public static function tearDownAfterClass() {
        Database::dropDatabase();
    }
    
    protected function setUp() {
        $this->schema = $this->getMockBuilder(Schema::class)->setMockClassName("Test")->getMockForAbstractClass();
    }
    
//    public function testClassName(){
//        $this->assertEquals("tests", $this->schema->className());
//    }
//    
    public function testGetClass(){
        $this->assertEquals("schemas", Schema::getClass(), "get class static");
    }
    
//    public function testCollection(){
//        $actual = $this->schema->collection->collectionName();
//        $excpected = "tests";
//        $this->assertEquals($excpected, $actual);
//        $this->assertEquals(Collection::class, get_class($this->schema->collection));
//    }
    
//   
//    public function testToArray(){
//        $expected = ["_id"=>$this->schema->getId()];
//        $this->assertEquals($expected, $this->schema->toArray());
//        
//        $expected += ["field1"=>"field1"];
//        $this->schema->field1 = "field1";
//        $this->schema->field2 = "field2";
//        
//        $this->assertEquals($expected, $this->schema->toArray([], ["field2"]));
//        $expected += ["field2"=>"field2"];
//        
//        $this->assertEquals($expected, $this->schema->toArray());
//        $this->assertEquals(["active"=>true], $this->schema->toArray(["active"]));
//    }
//    
//    public function testKeys(){
//        $expected = ["_id","active","field1","field2"];
//        $exclude = [];
//        foreach($expected as $e){
//            $this->assertEquals($expected, $this->schema->keys($exclude), '', 0.0, 10, true);
//            $exclude[] = array_pop($expected);
//        }
//        
//    }
//    
//    public function testGetProperties(){
//        $expected = ["_id","collection", "collectionName","active"];
//        $actual = Schema::getProperties();
//        $this->assertEquals($expected, $actual, '', 0.0, 10, true);
//        
//    }
    
}
