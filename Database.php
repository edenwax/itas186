<?php

/**
 * Class to handle connection to the Database
 * Singleton pattern applied to ensure only one instance of the PDO object
 * created
 *
 * User: croftd
 * Date: 2019-04-09
 */
class Database {

    private static $singleton = null;

    /** 
     * Constructor is private so you can't create Database objects
     * directly.
     */
    private function __construct() {}  // Prevents instantiation

    /**
     * Open a connection to the database.
     *
     * @return PDO connection object
     */
    public static function connect() {
        try {
            // the first time connect is called, singelton will
            // be null, so create a new PDO object
            if (self::$singleton == null) {
                return new PDO("mysql:host=localhost;dbname=aim",
                    "root", "");
            } else {
                return self::$singleton;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}

