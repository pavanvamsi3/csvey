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

    public function handleHomePage($queryParams)
    {
        $response = $this->twilio;

        return $response;
    }
}