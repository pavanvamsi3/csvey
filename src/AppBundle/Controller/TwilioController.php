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
        $queryParams = $this->get('request')->query->all();
        $twilioMessageManager = $this->get('twilio_message_manager');
        $response = null;
        if (isset($queryParams['Called'])) {
            $userManager = $this->get('csvey_api.user_manager');
            $userManager->add($queryParams);
            
            $response = $twilioMessageManager->makeHomeMessage();
        }
        
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

        return new Response(null);
    }

    /**
     * @Route("/ageinformation", name="ageinformation")
     *
     */
    public function postAgeAction(Request $request)
    {
        $requestParams = $this->get('request')->request->all();
        if (isset($requestParams['Digits']) && isset($requestParams['Called'])) {
            $userManager = $this->get('csvey_api.user_manager');
            $healthTipManager = $this->get('csvey_api.health_tip_manager');
            $user = $userManager->updateAge($requestParams['Called'], $requestParams['Digits']);
            $response = new Twiml();
            $healthTip = $healthTipManager->getHealthTip($user->getAge());
            $response->say($healthTip, array("language" => "en-IN"));
            $response->redirect('/outbound', array("method"=>"GET"));
            return new Response($response);
        }
    }

    /**
     * @Route("/statuscallback", name="statuscallback")
     *
     */
    public function postStatusCallAction(Request $request)
    {
        $requestParams = $this->get('request')->request->all();
        if (isset($requestParams['Called']) && isset($requestParams['CallStatus']) &&
            $requestParams['CallStatus'] == "completed") {
            $twilioCallingManager = $this->get('twilio_calling_manager');
            $twilioCallingManager->sendBalanceMessage($requestParams['Called']);
        }

        return new Response(null);
    }
}
