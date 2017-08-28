<?php
    require_once 'FacebookPrepareData.php';

class FacebookSend
{
    private $facebookPrepareData;
    protected $apiUrl = 'https://graph.facebook.com/v2.9/me/messages';

    public function __construct()
    {
        $this->facebookPrepareData = new FacebookPrepareData();
    }

    /**
     * @param string $accessToken
     * @param string $senderId
     * @param string $replyMessage
     * @internal param string $jsonDataEncoded
     */
    public function send(string $accessToken, string $senderId, string $replyMessage)
    {

        $jsonDataEncoded = $this->facebookPrepareData->prepare($senderId, $replyMessage);
        
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
