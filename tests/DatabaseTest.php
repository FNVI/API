<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Collection;
use FNVi\Mongo\Database;


/**
 * Description of DatabaseTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class DatabaseTest extends TestCase{
    
    public static function tearDownAfterClass() {
        Database::dropDatabase();
    }

    public function testConnection(){
        $uri = "mongodb://localhost/testdb";
        $client = Database::connect($uri);
        $this->assertEquals($uri, "$client");
    }
    
    public function testBasicURI(){
        $original = "mongodb://localhost";
        $servers = [
            "localhost"
        ];
        
        $this->assertEquals($original, Database::createMongoURI($servers));
    }
    
    public function testMultipleServers(){
        $original = "mongodb://server1:27017,server2:27017,server3:27017";
        $servers = [
            "server1:27017",
            "server2:27017",
            "server3:27017"
        ];

        $this->assertEquals($original, Database::createMongoURI($servers));
    }
    
    public function testDatabase(){
        $original = "mongodb://localhost/database";
        $servers = [
            "localhost"
        ];
        $database = "database";
        
        $this->assertEquals($original, Database::createMongoURI($servers, $database));
    }
    
    public function testCredentials(){
        $original = "mongodb://username:password@localhost/database";
        $servers = [
            "localhost"
        ];

        $username = "username";
        $password = "password";       
        $database = "database";
        $this->assertEquals($original, Database::createMongoURI($servers, $database, $username, $password));
    }
    
    public function testURI(){
        $original = "mongodb://username:password@server1:27017,server2:27017,server3:27017/database?replicaSet=replicaset";
        $servers = [
            "server1:27017",
            "server2:27017",
            "server3:27017"
        ];

        $username = "username";
        $password = "password";
        $database = "database";
        $replicaset = "replicaset";
        
        $this->assertEquals($original, Database::createMongoURI($servers, $database, $username, $password, ["replicaSet"=>$replicaset]));
    }
}
