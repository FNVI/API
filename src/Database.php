<?php

namespace FNVi\Mongo;

use MongoDB\Client;

/**
 * Represents a database
 * 
 * This class is used as a hybrid base class/singleton within the API. It exists
 * to create the connection to a mongo instance, keeping one persistent client object
 * throughout the application, but implemented in a way that class inheritance prevents 
 * access outside of the derived classes.
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
abstract class Database {
    
    /**
     * This is the client object
     * @var MongoDB\Client
     */
    private static $client;
    
    private static $database = "";

    /**
     * Sets handle to database
     * 
     * This could have been implemented as a protected function, however usage 
     * needs to be kept from being available later. Therefore it is private.
     * 
     * @return MongoDB\Client
     */
    public static function connect($uri){
        if(self::$client === null)
        {
            self::$database = ltrim(parse_url($uri, PHP_URL_PATH),'/');
            self::$client = new Client($uri);
        }
        return self::$client;
    }
    
    /**
     * A helper function for creating MongoURI strings
     * @param array $servers    Array of servers
     * @param string $database  The database to connect to
     * @param string $username  The username to connect with
     * @param string $password  The password to connect with
     * @param array $options    MongoURI options in a key value format
     * @return string   A MongoURI
     */
    public static function createMongoURI(array $servers, $database = null, $username = null, $password = null, array $options = []){
        return "mongodb://". ($username && $password ? "$username:$password@" : ""). implode(",", $servers) . ($database ? "/$database" : "") . (count($options) ? "?".http_build_query($options) : "");
    }
        
    public static function dropDatabase(){
        self::$client->dropDatabase(self::$database);
    }
    
    /**
     * 
     * @return \MongoDB\Driver\Manager
     */
    public static function getManager(){
        return self::$client->getManager();
    }
    
    public static function getDatabase(){
        return self::$database;
    }
}
