<?php

use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Document;
use MongoDB\BSON\ObjectID;
/**
 * Description of DocumentTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class DocumentTest extends TestCase{
    
    /**
     * @var Document
     */
    protected $document;
    
    protected function setUp() {
        $this->document = $this->getMockBuilder(Document::class)->getMockForAbstractClass();
    }
    
    public function testConstructorID(){
        $this->assertEquals(ObjectID::class, get_class($this->document->_id));
    }
    
    public function testStamp(){
        $this->document->propertyHidden = "testValue";
        $this->document->propertyChosen = "testValue";
        $expected = [
            "_id" => $this->document->_id,
            "propertyChosen"=>"testValue"
        ];
        
        $this->assertEquals($expected, $this->document->stamp(["propertyChosen"]));
        unset($expected["propertyChosen"]);
        $this->assertEquals($expected, $this->document->stamp());
    }
    
    
}
