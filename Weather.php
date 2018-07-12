<?php
    require_once 'DatabaseHelper.php';
    
class Weather extends DatabaseHelper{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getWeather($town) {
        $results = array();
        if(empty($town)){
            //Return an empty array
            return $results;
        }
        else{
            $date = new Date();
            $today = $date.date("Y-m-d");
    
            $query = "SELECT `hi`, `low`, `description` FROM `weather` WHERE `town` = '$town' AND DATE(`timestamp`) = '$today'";
            $queryresult = mysqli_query($this->getConnection(), $query);
            if($queryresult){
                while($result_array = $queryresult->fetch_array()){
                    array_push($results, $result_array);
                }
            }
            
            return $results;
        }
    }
    
    public function getForecast($town) {
        $key = "YOUR_KEY_HERE";
        $forcast_days='3';
        $url ="http://api.apixu.com/v1/forecast.json?key=$key&q=$town&days=$forcast_days";
    
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    
        $json_output = curl_exec($ch);
        $weather = json_decode($json_output);
        $days = $weather->forecast->forecastday;
        
        $results = array();
        foreach ($days as $day){
            array_push($results, array('hi' => $day->day->maxtemp_c, 'low' => $day->day->mintemp_c, 'description' => $day->day->condition->text));
        }
        
        return $results;
    }
}
