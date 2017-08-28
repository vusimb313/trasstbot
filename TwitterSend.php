<?php
require_once 'TwitterPrepareData.php';

class TwitterSend {
    protected $apiUrl = 'https://api.twitter.com/1.1/direct_messages';
    private $twitterPrepareData;

    public function __construct()
    {
        $this->twitterPrepareData = new TwitterPrepareData();
    }

    /**
     * @param string $accessToken
     * @param string $senderId
     * @param string $replyMessage
     * @internal param string $jsonDataEncoded
     */
    public function send(string $accessToken, string $senderId, string $replyMessage)
    {

        $jsonDataEncoded = $this->twitterPrepareData->prepare($senderId, $replyMessage);
        
        $url = $this->apiUrl . '?access_token=' . $accessToken;
        $ch = curl_init($url);

        // Tell cURL to send POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        // Attach JSON string to post fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        // Set the content type
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // Execute
        curl_exec($ch);

        if (curl_error($ch)) {
            //Log this error
        }

        curl_close($ch);
    }
}
