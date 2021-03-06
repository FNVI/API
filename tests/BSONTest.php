<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\BSON;
/**
 * Description of BSONTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class BSONTest extends TestCase{
    
    protected $BSON;
    
    protected function setUp() {
        $this->BSON = $this->getMockBuilder(BSON::class)->getMockForAbstractClass();
    }
    
    public function testToArray(){
        $this->BSON->testProperty = "testValue";
        $this->BSON->testPropertyHidden = "testValue";
        $this->BSON->testPropertyChosen = "testValue";
        $expected = [
            "testProperty"=>"testValue",
            "testPropertyHidden"=>"testValue",
            "testPropertyChosen"=>"testValue"
        ];
        $this->assertEquals($expected, $this->BSON->toArray(), "check all properties are returned");
        
        BSON::SetStrict(true);
        $this->assertEquals([], $this->BSON->toArray(), "check no runtime properties are returned");
        BSON::SetStrict(false);
        
        unset($expected["testPropertyHidden"]);
        $this->assertEquals($expected, $this->BSON->toArray([], ["testPropertyHidden"]), "check excluded properties aren't returned");
        unset($expected["testProperty"]);
        $this->assertEquals($expected, $this->BSON->toArray(["testPropertyChosen"]), "check included properties only are returned");
    }
    
    public function testKeys(){
        $this->BSON->testProperty = "testValue";
        $this->BSON->testPropertyHidden = "testValue";
        $expected = ["testProperty","testPropertyHidden"];
        $this->assertEquals($expected, $this->BSON->keys(), "check all keys are returned");
        
        BSON::SetStrict(true);
        $this->assertEquals([], $this->BSON->keys(), "check no runtime keys are returned");
        BSON::SetStrict(false);
        
        array_pop($expected);
        $this->assertEquals($expected, $this->BSON->keys(["testPropertyHidden"]), "check excluded keys aren't returned");
    }
    
    
    public function testToString(){
        $this->BSON->property1 = "testValue";
        $this->BSON->property2 = "testValue";
        $expected = json_encode(["property1"=>"testValue","property2"=>"testValue"],128);
        $actual = $this->BSON."";
        $this->assertEquals($expected, $actual);
    }
}
