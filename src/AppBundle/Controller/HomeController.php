<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Twiml;

class HomeController extends Controller
{
    /**
     * @Route("/homemessagehandler", name="home_message_handler")
     */
    public function homeMessageHandlerAction(Request $request)
    {
        $queryParams = $this->get('request')->query->all();
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
}
