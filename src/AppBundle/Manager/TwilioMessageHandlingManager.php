<?php

namespace AppBundle\Manager;

use Twilio\Twiml;

/**
* 
*/
class TwilioMessageHandlingManager
{   
    private $twilio;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->twilio = new Twiml();
    }

    public function makeHomeMessage()
    {
        $response = $this->twilio;
        $response->say('Hello Patlola');
        $response->gather(array("action" => "/homemessagehandler", "timeout"=>20,
            "method"=> "GET"))->say("asdasd");
        $response->redirect('/outbound', array("method"=>"GET"));

        return $response;
    }
}