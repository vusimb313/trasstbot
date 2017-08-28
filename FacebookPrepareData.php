<?php


class FacebookPrepareData
{

    /**
     * Create JSON data for the play to facebook
     * @param $senderId
     * @param string $message
     * @return string
     */
    public function prepare($senderId, $message)
    {
        return '{
            "recipient":{
                "id":"' . $senderId . '"
            },
            "message":{
                ' . $message . '
            }
        }';
        
    }
}
