<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Twiml;

class HomeController extends Controller
{
    /**
     * @Route("/homemessagehandler", name="home_message_handler")
     */
    public function homeMessageHandlerAction(Request $request)
    {
        $response = null;
        $queryParams = $this->get('request')->query->all();
        if (isset($queryParams['Digits'])) {
            $twilioMessageHandlingManager = $this->get('twilio_message_handling_manager');
            $response = $twilioMessageHandlingManager->handleHomePage($queryParams);

        }
        
        return new Response($response);
    }
}
