<?php

/**
 * Description of DatabaseHelper
 *
 * @author dev
 */
class DatabaseHelper {
    
    /*
     * The database user name
     */
    private $user = 'root';
    
    /*
     * The database password
     */
    private $password = '';
    
    /*
     * Our host address
     */
    private $host = 'localhost';
    
    /*
     * The database name
     */
    private $database = 'trasst';
    
    /*
     * The database instance
     */
    private $connection;


    public function __construct() {
        /*
        * Mysqli database connection object
        * Allows us to perform queries on the database
        */
       $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
    }
    
    
    protected function getConnection() {
        if($this->connection){
            return $this->connection;
        }
        else{
            throw new Exception;
        }
    }
}
