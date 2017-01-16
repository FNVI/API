<?php

use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Tools\Update;

/**
 * Description of UpdateTest
 *
 * @backupGlobals disabled
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class UpdateTest extends TestCase{
    
    protected $update;
    
    protected function setUp() {
        $this->update = new Update();
    }
    
    protected function tearDown() {
        unset($this->update);
    }
    
    public function testConstructor(){
        $expected = ["test"=>"query"];
        $update = new Update(null, $expected);
        $this->assertEquals($expected, $update->getQuery());
    }
    
    public function testAddToQuery(){
        $expected = ["test"=>"query"];
        $this->update->addToQuery($expected);
        $this->assertEquals($expected, $this->update->getQuery());
    }
    
    public function testBasicOperators(){
        $this->update
                ->set(["set"=>"value"])
                ->rename(["oldname"=>"newname"])
                ->setOnInsert(["setOnInsert"=>"value"])
                ->clear(["unset"]);
        
        $expected = [
            '$set'=>["set"=>"value"],
            '$rename'=>["oldname"=>"newname"],
            '$setOnInsert'=>["setOnInsert"=>"value"],
            '$unset'=>["unset"=>'']
        ];
        
        $this->assertEquals($expected, $this->update->getUpdate());
    }
    
    public function testMathOperators(){
        $this->update
                ->inc("increment")
                ->inc("decrement", -1)
                ->mul("multiply", 10);
        
        $expected = [
            '$inc'=>["increment"=>1, "decrement"=>-1],
            '$mul'=>["multiply"=>10]
        ];
        
        $this->assertEquals($expected, $this->update->getUpdate());
    }
    
    public function testArrayOperators(){
        $this->update
                ->addToSet("addToSet", "value")
                ->addToSet("addToSetEach", ["value","value"], true)
                ->pop("pop")
                ->pop("popFirst", true)
                ->pull("pull", "value")
                ->pullAll("pullAll", ["value","value"]);
        $expected = [
            '$addToSet'=>[
                "addToSet"=>"value",
                "addToSetEach"=>['$each'=>["value","value"]]
            ],
            '$pop'=>[
                "pop"=>1,
                "popFirst"=>-1
            ],
            '$pull'=>["pull"=>"value"],
            '$pullAll'=>["pullAll"=>["value","value"]]
        ];
        
        $this->assertEquals($expected, $this->update->getUpdate());
    }
    
    public function testPush(){
        $value = ["value","value"];
        $this->update
                ->push("basic", $value)
                ->push("each", $value,true)
                ->push("sort",$value,true, ["sort"=>1])
                ->push("slice", $value, true, null, 3)
                ->push("position", $value, true, null, null, 3)
                ->push("all", $value, true, ["sort"=>1], 2, 3);
        
        $expected = [
            '$push'=>[
                "basic"=>$value,
                "each"=>['$each'=>$value],
                "sort"=>['$each'=>$value,'$sort'=>["sort"=>1]],
                "slice"=>['$each'=>$value,'$slice'=>3],
                "position"=>['$each'=>$value,'$position'=>3],
                "all"=>['$each'=>$value,'$sort'=>["sort"=>1],'$slice'=>2,'$position'=>3],
            ]
        ];
        
        $this->assertEquals($expected, $this->update->getUpdate());
    }
}
