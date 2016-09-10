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
    public function outboundAction(Request $request)
    {
        $txt = json_encode($this->get('request')->request->all());
        $txt1 = json_encode($this->get('request')->query->all());
        $myfile = fopen("newfile.txt", "w");
        fwrite($myfile, $txt.$txt1);
        fclose($myfile);
        $response = new Twiml();
        $response->say('Hello Patlola');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));

        return new Response($response);
    }

    /**
     * @Route("/inbound", name="inbound_route")
     *
     */
    public function inboundAction(Request $request)
    {
        $queryParams = $this->get('request')->query->all();
        if (isset($queryParams['Direction']) && $queryParams['Direction'] == "inbound" &&
            isset($queryParams['CallerCountry']) && $queryParams['CallerCountry'] == "IN" &&
            isset($queryParams['From'])) {
            $twilioCallingManager = $this->get('twilio_calling_manager');
            $twilioCallingManager->makeOutBoundCall($queryParams['From']);
        }
        $response = new Twiml();
        $response->say('Hello Patlola');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));

        return new Response($response);
    }
}
