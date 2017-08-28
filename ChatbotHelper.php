<?php
    require_once 'ChatbotAI.php';
    require_once 'FacebookSend.php';
    require_once 'TwitterSend.php';

class ChatbotHelper
{

    protected $chatbotAI;
    protected $facebookSend;
    protected $twitterSend;
    private $accessToken;
    public $config;

    public function __construct()
    {
        $this->config = include('config.php');
        $this->accessToken = $this->config['access_token'];
        $this->chatbotAI = new ChatbotAI($this->config);
        $this->facebookSend = new FacebookSend();
        $this->twitterSend = new TwitterSend();
    }

    /**
     * Get the sender id of the message
     * @param $input
     * @return mixed
     */
    public function getSenderId($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return $input['entry'][0]['messaging'][0]['sender']['id'];
        }
        else if($platform == 'twitter'){
            return $input['event']['sender_id'];
        }
    }

    /**
     * Get the user's message from input
     * @param $input
     * @return mixed
     */
    public function getMessage($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return $input['entry'][0]['messaging'][0]['message']['text'];
        }
        else if($platform == 'twitter'){
            return $input['event']['message_create']['message_data']['text'];
        }
    }
    
    /**
     * Get the user's selected quick reply button from input
     * @param $input
     * @return mixed
     */
    public function getQuickReply($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return $input['entry'][0]['messaging'][0]['message']['quick_reply']['payload'];
        }
        else if($platform == 'twitter'){
            return $input['event']['message_create']['quick_reply_response']['metadata'];
        }
    }
    
    /**
     * Get the user's location from input
     * @param $input
     * @return mixed
     */
    public function getLocation($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates'];
        }
        else if($platform == 'twitter'){
            return $input['event']['message_create']['message_data']['attachment']['location']['shared_coordinate']['coordinates']['coordinates'];
        }
    }
    
    /**
     * Check if the callback is echo message from facebook
     * @param $input
     * @return bool
     */
    public function isEcho($input)
    {
        return isset($input['entry'][0]['messaging'][0]['message']['is_echo']);

    }
    

    /**
     * Check if the callback is a user message
     * @param $input
     * @return bool
     */
    public function isMessage($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return (isset($input['entry'][0]['messaging'][0]['message']['text']) && !isset($input['entry'][0]['messaging'][0]['message']['quick_reply']['payload']));
        }
        else if($platform == 'twitter'){
            return isset($input['event']['message_create']['message_data']['text'])  && !isset($input['event']['message_create']['quick_reply_response']['metadata']) && !isset($input['event']['message_create']['message_data']['attachment']['location']['shared_coordinate']['coordinates']['coordinates']);
        }
        

    }
    
    /**
     * Check if the user tapped a quick reply button
     * @param $input
     * @return bool
     */
    public function isQuickReply($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return isset($input['entry'][0]['messaging'][0]['message']['quick_reply']['payload']);
        }
        else if($platform == 'twitter'){
            return isset($input['event']['message_create']['quick_reply_response']['metadata']);
        }

    }
    
    /**
     * Check if the user shared his location
     * @param $input
     * @return bool
     */
    public function isLocation($input, $platform = 'fb')
    {
        if($platform == 'fb'){
            return isset($input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']);
        }
        else if($platform == 'twitter'){
            return isset($input['event']['message_create']['message_data']['attachment']['location']['shared_coordinate']['coordinates']['coordinates']);
        }
    }

    /**
     * Get the answer to a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getAnswer($message, $name = '', $platform = 'fb')
    {

        return $this->chatbotAI->getAnswer($message, $name, $platform);

    }
    
    /**
     * Set the typing on indicator
     * @param null $api
     * @param string $message
     * @return string
     */
    public function setTyping($psid)
    {
        
        $jsonDataEncoded = '{
            "recipient":{
                "id":"' . $psid . '"
            },
            "sender_action":"typing_on"
        }';
        $url = 'https://graph.facebook.com/v2.9/me/messages?&access_token=' . $this->accessToken;
        //  Initiate curl
        $ch = curl_init();
        // Tell cURL to send POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        // Attach JSON string to post fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        curl_exec($ch);
        // Closing
        curl_close($ch);
    }
    
    /**
     * Get the user's profile info
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getUserProfile($psid)
    {

        $url = 'https://graph.facebook.com/v2.9/' . $psid . '?fields=first_name&access_token=' . $this->accessToken;
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);
        
        if($result){
            $user = json_decode($result);
            return $user->first_name;
        }
        else{
            return '';
        }
    }

    /**
     * Send a reply back to Facebook chat
     * @param $senderId
     * @param $replyMessage
     */
    public function send($senderId, string $replyMessage, $platform = 'fb')
    {
        if($platform == 'fb'){
            return $this->facebookSend->send($this->accessToken, $senderId, $replyMessage);
        }
        else if($platform == 'twitter'){
            return $this->twitterSend->send($this->accessToken, $senderId, $replyMessage);
        }
    }

    /**
     * Verify Facebook webhook
     * This is only needed when you setup or change the webhook
     * @param $request
     * @return mixed
     */
    public function verifyWebhook($request)
    {
        if (!isset($request['hub_challenge'])) {
            return false;
        }

        $hubVerifyToken = $request['hub_verify_token'];
        $hubChallenge = $request['hub_challenge'];

        if (isset($hubChallenge) && $hubVerifyToken == $this->config['webhook_verify_token']) {
            echo $hubChallenge;
        }


    }
}