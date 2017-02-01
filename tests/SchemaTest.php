<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Database;
use FNVi\Mongo\Collection;
use FNVi\Mongo\Schema;
use FNVi\Mongo\BSON;
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
    
    protected $collectionName = "testschemas";
    protected $className = "TestSchema";

    public static function setUpBeforeClass() {
        Database::connect("mongodb://localhost/testdb");
    }
    
    public static function tearDownAfterClass() {
        Database::dropDatabase();
    }
    
    protected function setUp() {
        $this->schema = $this->getMockBuilder(Schema::class)->setMockClassName($this->className)->getMockForAbstractClass();
    }
    
    public function testConstructor(){
        $collectionName = "someRandomCollectionName";
        $collection = new Collection($collectionName);
        $schema = $this->getMockBuilder(Schema::class)->setConstructorArgs([$collection])->getMockForAbstractClass();
        $this->assertEquals($collectionName, $schema->collectionName(), "Check collection can be set from passing in a Collection object");
    }
                
    public function testCollectionName(){
        $this->assertEquals($this->collectionName, $this->schema->collectionName(), "check collection name is the same set by the mock");
    }
    
    public function testSave() {
        $this->schema->property = "whatever";
        $this->schema->save();
        return $this->schema;
    }
    
    /**
     * @depends testSave
     * @param Schema $schema
     * @return Schema
     */
    public function testLoad(Schema $schema){
        $newObject = $schema::loadFromID($schema->_id);
        $this->assertEquals($schema, $newObject, "saved and loaded");
        $this->assertEquals($this->collectionName, $newObject->collectionName(), "Check collection is set");
        $this->assertEquals($this->className, get_class($newObject), "Check correct class name");
        return $newObject;
    }
    
    /**
     * @depends testLoad
     * @param Schema $schema
     * @return Schema
     */
    public function testUpdate(Schema $schema){
        $schema->property = "something else";
        $schema->save();
        $loaded = $schema::loadFromID($schema->_id);
        $this->assertEquals($schema, $loaded, "saved and loaded after changes made");
        $this->assertEquals("something else", $loaded->property, "check new object is not the same as the old object");
        return $loaded;
    }
    /**
     * @depends testUpdate
     * @param Schema $schema
     */
    public function testDelete(Schema $schema){
        $result = $schema->delete();
        $this->assertEquals(1, $result->getDeletedCount());
        $this->assertNull($schema::loadFromID($schema->_id), "null after deletion");
    }
    
    public function testToArray(){
        $this->schema->testProperty = "testValue";
        $expected = [
            "_id"=>$this->schema->_id,
            "testProperty"=>"testValue"
        ];
        $this->assertEquals($expected, $this->schema->toArray(), "check all properties are returned");
    }
    
}
