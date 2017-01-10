<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Database;
use FNVi\Mongo\Collection;
use FNVi\Mongo\Tools\AggregationPipeline;

/**
 * Description of AggregationTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class AggregateTest extends TestCase{
    
    /**
     * An FNVi collection class
     * @var AggregationPipeline
     */
    protected $aggregatePipeline;
    
    public static function setUpBeforeClass() {
        Database::connect("mongodb://localhost/testdb");
    }
    
    public static function tearDownAfterClass() {
//        Database::dropDatabase();
    }
    
    protected function setUp(){
        $collection = $this->getMockBuilder(Collection::class)->setMockClassName("AggregationTest")->getMockForAbstractClass();
        $this->aggregatePipeline = new AggregationPipeline($collection);
    }
    
    protected function tearDown() {
        unset($this->aggregatePipeline);
    }
    
    /**
     * Tests the pipeline methods all work correctly. This should potentially be broken up
     * to allow the more complex methods to be tested
     */
    public function testPipeline(){
        $detail = ["pipeline"=>"test"];
        
        $actual = $this->aggregatePipeline
                ->match($detail)
                ->geoNear($detail)
                ->unwind("field")
                ->project($detail)
                ->group($detail)
                ->redact($detail)
                ->lookup("from", "local", "foreign", "as")
                ->sample(10)
                ->skip(10)
                ->limit(10)
                ->sort($detail)
                ->out("collection")
                ->getPipeline();
        $expected = [
            ['$match'=>$detail],
            ['$geoNear'=>$detail],
            ['$unwind'=>"field"],
            ['$project'=>$detail],
            ['$group'=>$detail],
            ['$redact'=>$detail],
            ['$lookup'=>["from"=>"from","localField"=>"local","foreignField"=>"foreign","as"=>"as"]],
            ['$sample'=>["size"=>10]],
            ['$skip'=>10],
            ['$limit'=>10],
            ['$sort'=>$detail],
            ['$out'=>"collection"]
        ];
        
        $this->assertEquals($expected, $actual, json_encode($actual,128));
    }
    
    /**
     * 
     */
    public function testPercentage(){
        $actual = $this->aggregatePipeline->percentage("value", "total");
        $expected = [
            '$multiply'=>[[
                '$divide'=>[
                    100,
                    "total"
                ]],
                "value"
            ]
        ];
        $this->assertEquals($expected, $actual);
    }
    
}
