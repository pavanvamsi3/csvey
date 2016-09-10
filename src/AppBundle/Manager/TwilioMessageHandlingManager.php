<?php

namespace AppBundle\Manager;

use Twilio\Twiml;

/**
* 
*/
class TwilioMessageHandlingManager
{   
    private $twilio;
    private $surveyManager;

    /**
     * Constructor
     */
    public function __construct($surveyManager)
    {
        $this->twilio = new Twiml();
        $this->surveyManager = $surveyManager;

    }

    public function handleHomePage($queryParams)
    {
        $response = $this->twilio;

        if ($queryParams['Digits'] == "1") {
            $survey = $this->surveyManager->getSurvey();
            $response->gather(array('action' => "/survey/".$survey['id'], "method" => "POST",
                "numDigits" => 1))->say($survey['text'], array("language" => "en-IN"));
            $response->say('sorry no response, switching to main menu');
            $response->redirect('/outbound', array("method"=>"GET"));
        }

        return $response;
    }
}