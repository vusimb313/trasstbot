<?php

class News {
    
    private $times_rss = "http://www.times.co.sz/feed/index.rss";
    
    private $observer_rss = "http://www.observer.org.sz/feed/index.rss";


    public function all() {
        //Randomize the top news from both publications
        $xml = simplexml_load_file($this->times_rss);
        //$json = json_encode($xml);
        //$array = json_decode($json,TRUE);
        
        $results = array();
        $i = 0;
        foreach ($xml->channel->item as $item) {
            //Get only 10 items
            if ($i < 10) {
                array_push($results, array('title' => $item->title, 'link' => $item->link));
            }

            $i++;
        }
        
        return $results;
    }
    
    public function times_of_swaziland() {
        $xml = simplexml_load_file($this->times_rss);
        //$json = json_encode($xml);
        //$array = json_decode($json,TRUE);
        
        $results = array();
        $i = 0;
        foreach ($xml->channel->item as $item) {
            //Get only 10 items
            if ($i < 10) {
                array_push($results, array('title' => $item->title, 'link' => $item->link));
            }

            $i++;
        }
        
        return $results;
    }
    
    public function swazi_observer() {
        $xml = simplexml_load_file($this->observer_rss);
        
        $results = array();
        $i = 0;
        foreach ($xml->channel->item as $item) {
            //Get only 10 items
            if ($i < 10) {
                array_push($results, array('title' => $item->title, 'link' => $item->link));
            }

            $i++;
        }
        
        return $results;
    }
}
