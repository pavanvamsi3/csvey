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
        $response->say('Hello');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));

        return new Response($response);
    }

    /**
     * Function to fetch location of a number
     *
     */
    private function getPhoneDetails($number)
    {
        $base_url = "http://apilayer.net/api/validate?";
        $data = array(
            $access_key => "39694ac085e4af5b2922991ae2b16a1e",
            $number => $number,
            $country_code => "IN",
            $format => 1
        }
        $params = http_build_query($data);
        $url = $base_url . $params;
        $data = file_get_contents($url);

        return new Response($data['location']);
    }
}
