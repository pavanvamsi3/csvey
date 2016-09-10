<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;
use FOS\RestBundle\Controller\FOSRestController;
use Twilio\Twiml;


class TwilioController extends Controller
{
    /**
     * @Route("/outbound", name="outbound_route")
     *
     */
    public function indexAction(Request $request)
    {
        $response = new Twiml();
        $response->say('Hello Patlola');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));

        return new Response($response);
    }
}
