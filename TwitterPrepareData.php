<?php

class TwitterPrepareData {
    /**
     * Create JSON data for the user to twitter
     * @param $senderId
     * @param string $message
     * @return string
     */
    public function prepare($senderId, $message)
    {
        return '{
            "event":{
                "type":"message_create",
                "message_create":{
                    "target":{
                        "recient_id":"' . $senderId . '",
                    },
                    "message_data":{
                        ' . $message . '
                    }
                }
            }
        }';
        
    }
}
