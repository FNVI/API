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
        $this->assertEquals($collectionName, $schema->getCollectionName(), "Check collection can be set from passing in a Collection object");
    }
            
    public function testToArray() {
        $this->schema->testProperty = "testValue";
        $this->schema->testPropertyHidden = "testValue";
        $this->schema->testPropertyChosen = "testValue";
        $expected = [
            "_id" => $this->schema->_id,
            "testProperty"=>"testValue",
            "testPropertyHidden"=>"testValue",
            "testPropertyChosen"=>"testValue"
        ];
        $this->assertEquals($expected, $this->schema->toArray(), "check all properties are returned");
        unset($expected["testPropertyHidden"]);
        $this->assertEquals($expected, $this->schema->toArray([], ["testPropertyHidden"]), "check excluded properties aren't returned");
        unset($expected["_id"]);
        unset($expected["testProperty"]);
        $this->assertEquals($expected, $this->schema->toArray(["testPropertyChosen"]), "check included properties only are returned");
    }
    
    public function testKeys(){
        $this->schema->testProperty = "testValue";
        $this->schema->testPropertyHidden = "testValue";
        $expected = ['_id',"testProperty","testPropertyHidden"];
        $this->assertEquals($expected, $this->schema->keys(), "check all keys are returned");
        array_pop($expected);
        $this->assertEquals($expected, $this->schema->keys(["testPropertyHidden"]), "check excluded keys aren't returned");
    }
    
    public function testCollectionName(){
        $this->assertEquals($this->collectionName, $this->schema->getCollectionName(), "check collection name is the same set by the mock");
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
        $newObject = Schema::loadFromID($schema->_id);
        $this->assertEquals($schema, $newObject, "saved and loaded");
        $this->assertEquals($this->collectionName, $newObject->getCollectionName(), "Check collection is set");
        $this->assertEquals($this->className, get_class($newObject), "Check correct class name");
        return $schema;
    }
    
    /**
     * @depends testLoad
     * @param Schema $schema
     * @return Schema
     */
    public function testUpdate(Schema $schema){
        $schema->property = "something else";
        $schema->save();
        $loaded = Schema::loadFromID($schema->_id);
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
        $this->assertNull(Schema::loadFromID($schema->_id), "null after deletion");
    }
    
}
