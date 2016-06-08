<?php
use phpunit\framework\TestCase;
use FNVi\Mongo\Collection;


/**
 * Description of Connection
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Connection extends TestCase{
        
    /**
     * 
     * @return Collection
     */
    public function testCollection(){
        return $this->collection = new Collection("testCollection");
    }
    
    /**
     * @depends testCollection
     * @param Collection $collection
     * @return Collection
     */
    public function testInsertOne(Collection $collection){
        $result = $collection->insertOne(["test"=>"one","active"=>true]);
        $this->assertEquals(1, $result->getInsertedCount(), "insert one");
        return $collection;
    }
    
    /**
     * @depends testInsertOne
     * @param Collection $collection
     * @return Collection
     */
    public function testFindOne(Collection $collection){
        $this->assertNotNull($collection->findOne(["test"=>"one"]));
        return $collection;
    }
    
    /**
     * @depends testFindOne
     * @param Collection $collection
     * @return Collection
     */
    public function testInsertMany(Collection $collection){
        $items = [];
        for($i = 0; $i < 20; $i++){
            $items[] = ["test"=>"many","item"=>$i];
        }
        $result = $collection->insertMany($items);
        $this->assertEquals(20, $result->getInsertedCount(), "All inserted");
        return $collection;
    }
    
    /**
     * @depends testInsertMany
     * @param Collection $collection
     * @return Collection
     */
    public function testFind(Collection $collection){
        
        return $collection;
    }
    
}
