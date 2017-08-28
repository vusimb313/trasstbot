<?php
require_once 'DatabaseHelper.php';

class Contacts extends DatabaseHelper{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getContacts($town) {
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
                    array_push($results, array('name' => $result_array['name'], 'contacts' => $result_array['contacts']));
                }
            }
            
            return $results;
        }
    }
    
    public function searchContacts($name) {
        $results = array();
        if(empty($name)){
            //Return an empty array
            return $results;
        }
        else{
    
            $query = "SELECT `name`, `contacts` FROM `contacts` WHERE `name` LIKE '%$name%'";
            $queryresult = mysqli_query($this->getConnection(), $query);
            if($queryresult){
                while($result_array = $queryresult->fetch_array()){
                    array_push($results, array('name' => $result_array['name'], 'contacts' => $result_array['contacts']));
                }
            }
            
            return $results;
        }
    }
}
