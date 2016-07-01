<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Schema;
/**
 * Description of SchemaTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class SchemaTest extends TestCase{
    
    public function testConstructor(){
        $document = new Schema("testSchema");
        $this->assertEquals("testSchema", $document->collectionName);
        return $document;
    }
    
    /**
     * @depends testConstructor
     * @param Schema $document
     * @return Schema A Schema document
     */
    public function testClassName(Schema $document){
        $this->assertEquals("schemas", $document->className());
        return $document;
    }
    
    public function testGetClass(){
        $expected = "schemas";
        $actual = Schema::getClass();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * To be implemented later
     * 
     * @depends testClassName
     * @param Schema $document
     */
//    public function testStore(Schema $document){
//    }
    
    /**
     * @depends testConstructor
     * @param Schema $document
     */
    public function testToArray(Schema $document){
        $expected = ["active"=>true,"_id"=>$document->getId()];
        $this->assertEquals($expected, $document->toArray());
        
        $expected += ["field1"=>"field1"];
        $document->field1 = "field1";
        $document->field2 = "field2";
        
        $this->assertEquals($expected, $document->toArray([], ["field2"]));
        $expected += ["field2"=>"field2"];
        
        $this->assertEquals($expected, $document->toArray());
        $this->assertEquals(["active"=>true], $document->toArray(["active"]));
        return $document;
    }
    
    /**
     * @depends testToArray
     * @param Schema $document
     */
    public function testKeys(Schema $document){
        $expected = ["_id","active","field1","field2"];
        $actual = $document->keys();
        
        $this->assertEquals($expected, $actual, '', 0.0, 10, true);
    }
    
}
