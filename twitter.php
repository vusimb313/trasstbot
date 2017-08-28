<?php
    require_once 'ChatbotHelper.php';

    // Create the chatbot helper instance
    $chatbotHelper = new ChatbotHelper();

    // Get the twitter users data
    $input = json_decode(file_get_contents('php://input'), true);
    $senderId = $chatbotHelper->getSenderId($input, 'twitter');

    //Check if user has entered a general query
    if ($senderId && $chatbotHelper->isMessage($input, 'twitter')) {
        
        // Get the user's message
        $message = $chatbotHelper->getMessage($input, 'twitter');
        
        // Get the response
        $replyMessage = $chatbotHelper->getAnswer($message, '');

        // Send the answer back to the twitter DM
        $chatbotHelper->send($senderId, $replyMessage, 'twitter');

    }
    
    //Check if user has selected a quick reply button
    else if ($senderId && $chatbotHelper->isQuickReply($input, 'twitter')) {
        
        // Get the selected quick reply payload
        $payload = $chatbotHelper->getQuickReply($input, 'twitter');
        // Get the selected quick reply text
        $text = $chatbotHelper->getMessage($input, 'twitter');
        
        $message = $payload . " " . $text;
        
        // Get the response
        $replyMessage = $chatbotHelper->getAnswer($message, 'twitter');

        // Send the answer back to the Facebook chat
        $chatbotHelper->send($senderId, $replyMessage, 'twitter');

    }