<?php
    namespace App;

class ContactDetails extends DatabaseHelper{
    public function __construct() {
        parent::__construct();
    }
    
    public function getContactsByTown($town) {
        $results = array();
        if(empty($town)){
            //Return an empty array
            return $results;
        }
        else{
    
            $query = "SELECT `name`, `contacts` FROM `contacts` WHERE `town` = '$town'";
            $queryresult = mysqli_query($this->getConnection(), $query);
            if($queryresult){
                while($result_array = $queryresult->fetch_array()){
                    array_push($results, $result_array);
                }
            }
            
            return $results;
        }
    }
    
    public function getContactsByName($name) {
        $results = array();
        if(empty($name)){
            //Return an empty array
            return $results;
        }
        else{
            
            $query = "SELECT `town`, `contacts` FROM `contacts` WHERE `name` = '$name'";
            $queryresult = mysqli_query($this->getConnection(), $query);
            if($queryresult){
                while($result_array = $queryresult->fetch_array()){
                    array_push($results, $result_array);
                }
            }
            
            return $results;
        }
    }
}
