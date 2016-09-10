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
        $queryParams = $this->get('request')->query->all();
        if (isset($queryParams['Digits'])) {
            $response = new Twiml();
            $response->say('You have pressed ' . $queryParams['Digits']);
        }
        
        return new Response($response);
    }
}
