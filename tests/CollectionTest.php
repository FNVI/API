<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Collection;
use FNVi\Mongo\Database;
use MongoDB\Model\BSONDocument;

/**
 * Description of CollectionTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class CollectionTest extends TestCase{
    
    /**
     * An FNVi collection class
     * @var Collection
     */
    protected $collection;
    
    public static function setUpBeforeClass() {
        Database::connect("mongodb://testuser:testpassword@pnmmgt.com/testdb");
    }
    
    public static function tearDownAfterClass() {
//        Database::dropDatabase();
    }
    
    protected function setUp(){
        $this->collection = $this->getMockBuilder(Collection::class)->setMockClassName("Test")->getMockForAbstractClass();
    }
        
    protected function tearDown() {
//        $this->collection->deleteMany();
    }
    
    public function testClassName(){
        $actual = $this->collection->getCollectionName();
        
        $expected = "tests";
        
        $this->assertEquals($expected, $actual, "Check collection name is set correctly");
    }
    
    public function testAggregate(){
        $actual = $this->collection->aggregationPipeline();
        $this->assertEquals(\FNVi\Mongo\Tools\AggregationPipeline::class, get_class($actual), "Check aggregation pipeline object returned");
    }
    
//    public function testCRUDOne(){
//        $document = new BSONDocument(["test"=>"insert one"]);
//        
//        $insertResult = $this->collection->insertOne($document);
//        $this->assertEquals(1, $insertResult->getInsertedCount(), "insert one");
//        
//        $countResult = $this->collection->count();
//        $this->assertEquals(1, $countResult, "Count result after inserting document");
//        
//        $query = ["_id"=>$insertResult->getInsertedId()];
//        
//        $document->offsetSet("_id", $insertResult->getInsertedId());
//        
//        $findResult = $this->collection->findOne($query);
//        $this->assertEquals($document, $findResult, "find one");
//        
//        $update = ["test"=>"update one"];
//        $document->offsetSet("test", "update one");
//        
//        $updateResult = $this->collection->updateOne($query, ['$set'=>$update]);
//        $this->assertEquals(1, $updateResult->getModifiedCount(), "update one");
//        
//        $findUpdatedResult = $this->collection->findOne($query);
//        $this->assertEquals($document, $findUpdatedResult, "find updated one");
//        
//        
//        $deleteResult = $this->collection->deleteOne($query);
//        $this->assertEquals(1, $deleteResult->getDeletedCount(), "remove one");
//        
//        $findRemovedResult = $this->collection->findOne($query);
//        $this->assertNull($findRemovedResult, "find removed one");
//        
//        $countRecoveredResult = $this->collection->count();
//        $this->assertEquals(0, $countRecoveredResult, "Count result after recovering document");
//        
//    }
    
    public function testCRUDMany(){
        $documents = array_fill(0, 5, new BSONDocument(["test"=>"insert many"]));
        
        $insertResult = $this->collection->insertMany($documents);
        $this->assertEquals(5, $insertResult->getInsertedCount(), "insert many");
        $countResult = $this->collection->count();
        $this->assertEquals(5, $countResult, "Count result after inserting documents");
        
        foreach($insertResult->getInsertedIds() as $i=>$id)
        {
            $documents[$i] = new BSONDocument(["_id"=>$id,"test"=>"insert many"]);
        }
        
        $findResult = $this->collection->find();
                
        $this->assertEquals($documents, iterator_to_array($findResult), "find all");
        
        $update = ["test"=>"update many"];
        
        $updateResult = $this->collection->updateMany([], ['$set'=>$update]);
        $this->assertEquals(5, $updateResult->getModifiedCount(), "update many");
        
        foreach ($documents as $d)
        {
            $d->offsetSet("test", "update many");
        }
        $findUpdatedResult = $this->collection->find();
        $this->assertEquals($documents, iterator_to_array($findUpdatedResult), "find updated many");
        
        
        $deleteResult = $this->collection->deleteMany();
        $this->assertEquals(5, $deleteResult->getDeletedCount(), "remove many");
        
        $findRemovedResult = $this->collection->findOne();
        $this->assertNull($findRemovedResult, "find removed one");
               
        $countRecoveredResult = $this->collection->count();
        $this->assertEquals(0, $countRecoveredResult, "Count result after recovering documents");
        
    }
}
