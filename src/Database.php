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
    private static $db = null;
        
    /**
     * Sets handle to database
     * 
     * This could have been implemented as a protected function, however usage 
     * needs to be kept from being available later. Therefore it is private.
     * 
     * @return MongoDB\Client
     */
    private static function db()
    {
        if(self::$db === null)
        {
            $db = DATABASE;
            self::$db = new Client(MONGOURI);
        }
        return self::$db;
    }
    
    /**
     * Returns a collection object to be used in derived classes
     * 
     * @param string $name
     * @return MongoDB\Collection
     */
    protected function setCollection($name) {
        return self::db()->selectCollection(DATABASE,$name);
    }
    
    public static function dropDatabase(){
        self::$db->dropDatabase(constant("DATABASE"));
    }
}
