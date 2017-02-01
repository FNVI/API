<?php

use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Document;
use MongoDB\BSON\ObjectID;
use FNVi\Mongo\Stamp;
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
        $this->assertEquals(Stamp::class, get_class($this->document->stamp()));
    }
    
    
}
