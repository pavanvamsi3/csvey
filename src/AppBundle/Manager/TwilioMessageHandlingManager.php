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

        switch ($queryParams['Digits']) {
            case "1":
                $survey = $this->surveyManager->getSurvey();
                $response->gather(array('action' => "/survey/".$survey['id'], "method" => "POST",
                "numDigits" => 1))->say($survey['text'], array("language" => "en-IN"));
                break;
            case "2":

                break;
            case "3":
                
                break;
            default:
                $response->say('sorry your response is not valid, switching to main menu', array("language" => "en-IN"));
        }
        $response->redirect('/outbound', array("method"=>"GET"));

        return $response;
    }
}