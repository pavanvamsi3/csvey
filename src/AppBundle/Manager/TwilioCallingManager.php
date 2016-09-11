<?php

namespace AppBundle\Manager;

use Twilio\Rest\Client;

/**
* 
*/
class TwilioCallingManager
{   
    private $twilioVerifiedNumber;
    private $twilio;

    /**
     * Constructor
     */
    public function __construct($ssid, $secret, $number)
    {
        $this->twilioVerifiedNumber = $number;
        $this->twilio = new Client($ssid, $secret);
    }

    public function makeOutBoundCall($phoneNumber = null)
    {
        $call = $this->twilio->calls->create(
              $phoneNumber, // Call this number
              $this->twilioVerifiedNumber, // From a valid Twilio number
              array(
                  'url' => 'https://www-marker.practodev.com/outbound',
                  'Method' => 'GET',
                  'statusCallback' => 'https://www-marker.practodev.com/statuscallback'
              )
            );

        return $call;
    }
}