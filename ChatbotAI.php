<?php

class ChatbotAI
{
    protected $config;

    /**
     * ChatbotAI constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get the answer to the user's message
     * @param $message
     * @return string
     */
    public function getAnswer(string $message, $name = '', $platform = 'fb')
    {
        $town = array();
        
        // Analyze the message
        if(preg_match('/(hi|hey|hello|morning|afternoon|evening|good day)/', strtolower($message), $matches)) {
            //The user's intent was to greet the chatbot or start a conversation
            return '"text" : "' . ucfirst($matches[1]) . ' ' . $name . '! Which town would you like to explore today?"';
        }
        else if(preg_match('/(weather|temperature|raining|sunny|hot)/', strtolower($message))) {
            
            if(preg_match('/(mbabane|manzini|matsapha|ezulwini)/', strtolower($message), $town)){
                //The user's intent is to get the weather at any one of these towns
                //Extract the town name and get the weather for that town
                return $this->getWeather($town[1]);
            }
            else{
                //The user's intent is to get the weather at an unspecified location
                //Let him share his location and we retrieve the weather forecast for that location
                return '"text" : "Which town are you asking about?"';
            }
        }
        else if(preg_match('/(traffic|accidents|jam)/', strtolower($message))) {
            
            if(preg_match('/(mbabane|manzini|matsapha|ezulwini)/', strtolower($message), $town)){
                //The user's intent is to get the traffic report at any one of these towns
                //Extract the town name and get the traffic report for that town
                return $this->getTraffic($town[1]);
            }
            else{
                //The user's intent is to get traffic report at an unspecified location
                //Ask the user to state the name of the town he wants to get the traffic update
                return '"text" : "Which town are you asking about?"';
            }
        }
        else if(preg_match('/(events|event|happening)/', strtolower($message))) {
            
            if(preg_match('/(mbabane|manzini|matsapha|ezulwini)/', strtolower($message), $town)){
                //The user's intent is to get upcoming events at any one of these towns
                //Extract the town name and get the events for that town
                return $this->getEvents($town[1]);
            }
            else{
                //The user's intent is to get happening events
                //Ask the user to state the name of the town he wants to get the events for
                return '"text" : "In which town do you want to see upcoming events?"';
            }
            
        }
        else if(preg_match('/(contacts|number|phone|authorities)/', strtolower($message))) {
            
            
            if(preg_match('/(mbabane|manzini|matsapha|ezulwini)/', strtolower($message), $town)){
                //The user's intent is to get contact details at any one of these towns
                //Remove the keywords(contacts, number etc) from the query message
                //Extract the town name and get the events for that town
                return $this->getContacts($town[1]);
            }
            else{
                //The user's intent is to get contact details
                //Remove the keywords(contacts, number etc) from the query message
                //Search all contacts and return relavant results
                return $this->searchContacts($message);
               
            }
            
        }
        else if(preg_match('/(news|stories|headlines|observer|times)/', strtolower($message))) {
            
            //The user's intent is to get top stories or news 
            //Get the top stories from top news sites
            if(preg_match('(observer)', strtolower($message))){
                return $this->getNews('observer');
            }
            else if(preg_match('(times)', strtolower($message))){
                return $this->getNews('times');
            }
            else{
                return $this->getNews();
            }
        }
        else if(preg_match('/(mbabane|manzini|matsapha|ezulwini)/', strtolower($message), $town)){
            //The user's intent is to explore any one of these towns
            //Present the user with quick buttons to select what he wants to explore about that particular town
            if($platform == 'fb'){
                return '"text" : "What do you want to know about '. ucwords($town[1]) .'?",
                    "quick_replies" : [
                    {
                        "content_type" : "text",
                        "title" : "Weather",
                        "payload" : "'. $town[1] .'"
                    },
                    {
                        "content_type" : "text",
                        "title" : "News",
                        "payload" : "'. $town[1] .'"
                    },
                    {
                        "content_type" : "text",
                        "title" : "Contacts",
                        "payload" : "'. $town[1] .'"
                    },
                    {
                        "content_type" : "text",
                        "title" : "Events",
                        "payload" : "'. $town[1] .'"
                    },
                    {
                        "content_type" : "text",
                        "title" : "Traffic",
                        "payload" : "'. $town[1] .'"
                    }
                ]';
            }
            else if($platform == 'twitter'){
                return '"text" : "What do you want to know about '. ucwords($town[1]) .'?",
                    "quick_reply":{
                        "type": "options",
                        "options" : [
                        {   
                            "label" : "Weather",
                            "description" : "Get the weather forecast for the next 3 days",
                            "metadata" : "'. $town[1] .'"
                        },
                        {
                            "label" : "News",
                            "description" : "Local News",
                            "metadata" : "'. $town[1] .'"
                        },
                        {
                            "label" : "Contacts",
                            "description" : "Important contacts while you are in '. $town[1] .'",
                            "metadata" : "'. $town[1] .'"
                        },
                        {
                            "label" : "Events",
                            "descroption" : "Events around '. $town[1] .'",
                            "metadata" : "'. $town[1] .'"
                        },
                        {
                            "label" : "Traffic",
                            "description" : "The latest traffic updates in '. $town[1] .'",
                            "metadata" : "'. $town[1] .'"
                        }   
                    ]
                }
                ';
            }
            
        }
        else{
            return '"text" : "Sorry, I cant understand your message.... Lets begin again. Which town would you like to explore"';
        }
    }
    
    /**
     * Get the weather in a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getWeather($town, $platform = 'fb')
    {
        require_once 'Weather.php';
        
        $message = '"text" : "Sorry, Supported towns are Manzini, Mbabane, Matsapha and Ezulwini"';
        $results_template = '';
        $c = 1;
        
        $weather = new Weather();
        $answer = $weather->getForecast($town);
        
        if(count($answer) >= 1){
            if($platform == 'fb'){
                foreach ($answer as $row) {
                    if($c != 1){
                        $results_template .= ',';
                    }
                    else{
                        $c++;
                    }
                    
                    $results_template .= '{
                        "title": "Hi: '.$row['hi'].' Low: '.$row['low'].'",
                        "subtitle": "'.$row['description'].'"
                    }';
                }
                
                $message = '"attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"generic",
                        "elements":[
                            '.$results_template.'
                        ]
                    }
                }';
            }
            else if($platform == 'twitter'){
                
            }
        }
        else{
            $message = '"text" : "There is no weather update available currently"';
        }
        return $message;
    }
    
    /**
     * Get the latest news from local publications
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getNews($publication = 'all', $platform = 'fb')
    {
        require_once 'News.php';
        
        $message = '"text" : "No news available"';
        $results_template = '';
        $c = 1;
        
        $news = new News();
        
        if($publication === 'times'){
            $answer = $news->times_of_swaziland();
        }
        else if($publication === 'observer'){
            $answer = $news->swazi_observer();
        }
        else{
            $answer = $news->all();
        }
        
        if(count($answer) >= 1){
            if($platform == 'fb'){
                foreach ($answer as $row) {
                    if($c != 1){
                        $results_template .= ',';
                    }
                    else{
                        $c++;
                    }
                    
                    $results_template .= '{
                        "title": "' . $row['title'] . '",
                        "subtitle": "'.$row['link'].'"
                    }';
                }
                
                $message = '"attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"generic",
                        "elements":[
                            '.$results_template.'
                        ]
                    }
                }';
            }
            else if($platform == 'twitter'){
                
            }
        }
        else{
            $message = '"text" : "There are no news updates available at the moment"';
        }
        
        return $message;
    }
    
    /**
     * Get the contact details in a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getContacts($town, $platform = 'fb')
    {
        require_once 'Contacts.php';
        
        $message = '"text" : "Sorry, Supported towns are Manzini, Mbabane, Matsapha and Ezulwini"';
        $results_template = '';
        $c = 1;
        
        $contacts = new Contacts();
        $answer = $contacts->getContacts($town);
        
        if(count($answer) >= 1){
            if($platform == 'fb'){
                foreach ($answer as $row) {
                    if($c != 1){
                        $results_template .= ',';
                    }
                    else{
                        $c++;
                    }
                    
                    $results_template .= '{
                        "title": "' . $row['name'] . '",
                        "subtitle": "'.$row['contacts'].'"
                    }';
                }
                
                $message = '"attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"generic",
                        "elements":[
                            '.$results_template.'
                        ]
                    }
                }';
            }
            else if($platform == 'twitter'){
                
            }
        }
        else{
            $message = '"text" : "There are no contacts of interest in this town"';
        }
        
        return $message;
    }
    
    /**
     * Search contact details in a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function searchContacts($name, $platform = 'fb')
    {
        require_once 'Contacts.php';
        
        $message = '"text" : "Sorry, Supported towns are Manzini, Mbabane, Matsapha and Ezulwini"';
        $results_template = '';
        $c = 1;
        
        $contacts = new Contacts();
        $answer = $contacts->searchContacts($name);
            
        if(count($answer) >= 1){
            if($platform == 'fb'){
                foreach ($answer as $row) {
                    if($c != 1){
                        $results_template .= ',';
                    }
                    else{
                        $c++;
                    }
                    
                    $results_template .= '{
                        "title": "' . $row['name'] . '",
                        "subtitle": "'.$row['contacts'].'"
                    }';
                }
                
                $message = '"attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"generic",
                        "elements":[
                            '.$results_template.'
                        ]
                    }
                }';
            }
            else if($platform == 'twitter'){
                
            }
        }
        else{
            $message = '"text" : "There are no contacts for that name"';
        }
        
        return $message;
    }
    
    /**
     * Get the events in a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getEvents($town, $platform = 'fb')
    {
        require_once 'Events.php';
        
        $message = '"text" : "Sorry, Supported towns are Manzini, Mbabane, Matsapha and Ezulwini"';
        $results_template = '';
        $c = 1;
        
        $events = new Events();
        $answer = $events->getEvents($town);
        
        if(count($answer) >= 1){
            if($platform == 'fb'){
                foreach ($answer as $row) {
                    if($c != 1){
                        $results_template .= ',';
                    }
                    else{
                        $c++;
                    }
                    
                    $results_template .= '{
                        "title": "' . $row['title'] . '",
                        "subtitle": "'.$row['description'].'"
                    }';
                }
                
                $message = '"attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"generic",
                        "elements":[
                            '.$results_template.'
                        ]
                    }
                }';
            }
            else if($platform == 'twitter'){
                
            }
        }
        else{
            $message = '"text" : "There are no upcoming events yet"';
        }
        
        return $message;
    }
    
    /**
     * Get the traffic in a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getTraffic($town, $platform = 'fb')
    {
        require_once 'Traffic.php';
        
        $message = '"text" : "Sorry, Supported towns are Manzini, Mbabane, Matsapha and Ezulwini"';
        $results_template = '';
        $c = 1;
        
        $traffic = new Traffic();
        $answer = $traffic->getTraffic($town);
        
        if(count($answer) >= 1){
            if($platform == 'fb'){
                foreach ($answer as $row) {
                    if($c != 1){
                        $results_template .= ',';
                    }
                    else{
                        $c++;
                    }
                    
                    $results_template .= '{
                        "title": "' . $row['title'] . '",
                        "subtitle": "'.$row['description'].'"
                    }';
                }
                
                $message = '"attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"generic",
                        "elements":[
                            '.$results_template.'
                        ]
                    }
                }';
            }
            else if($platform == 'twitter'){
                
            }
        }
        else{
            $message = '"text" : "There are no traffic updates available at the moment"';
        }
        
        return $message;
    }

}
