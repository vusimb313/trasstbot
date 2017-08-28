<?php
    require_once 'ChatbotHelper.php';

    // Create the chatbot helper instance
    $chatbotHelper = new ChatbotHelper();

    // Facebook webhook verification
    $chatbotHelper->verifyWebhook($_REQUEST);

    // Get the fb users data
    $input = json_decode(file_get_contents('php://input'), true);
    $senderId = $chatbotHelper->getSenderId($input);

    //Check if user has entered a general query
    if ($senderId && $chatbotHelper->isMessage($input)) {
        //Set the typing indicator
        $chatbotHelper->setTyping($senderId);
        
        //Get the user's name
        $name = $chatbotHelper->getUserProfile($senderId);
        
        // Get the user's message
        $message = $chatbotHelper->getMessage($input);
        
        // Get the response
        $replyMessage = $chatbotHelper->getAnswer($message, $name);

        // Send the answer back to the Facebook chat
        $chatbotHelper->send($senderId, $replyMessage);

    }
    
    //Check if user has selected a quick reply button
    else if ($senderId && $chatbotHelper->isQuickReply($input)) {
        //Set the typing indicator
        $chatbotHelper->setTyping($senderId);

        // Get the selected quick reply payload
        $payload = $chatbotHelper->getQuickReply($input);
        // Get the selected quick reply text
        $text = $chatbotHelper->getMessage($input);
        
        $message = $payload . " " . $text;
        
        // Get the response
        $replyMessage = $chatbotHelper->getAnswer($message);

        // Send the answer back to the Facebook chat
        $chatbotHelper->send($senderId, $replyMessage);

    }
    
    //Check if user has shared his location with us
    else if ($senderId && $chatbotHelper->isLocation($input)) {

        // Get the user's latitude and longitude points
        //$coordinates = $chatbotHelper->getLocation($input);

        // Send the answer back to the Facebook chat
        //$chatbotHelper->send($senderId, $replyMessage);

    }
    
    else{
        // Get the weather
        //$replyMessage = $chatbotHelper->getAnswer('mbabane', 'weather');
        //echo $replyMessage;
    }