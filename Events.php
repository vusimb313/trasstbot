<?php
require_once 'DatabaseHelper.php';

class Events extends DatabaseHelper{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getEvents($town) {
        $results = array();
        if(empty($town)){
            //Return an empty array
            return $results;
        }
        else{
            $query = "SELECT `title`, `description` FROM `events` WHERE `town` = '$town'";
            $queryresult = mysqli_query($this->getConnection(), $query);
            if($queryresult){
                while($result_array = $queryresult->fetch_array()){
                    array_push($results, array('title' => $result_array['title'], 'description' => $result_array['description']));
                }
            }
            
            return $results;
        }
    }
}
