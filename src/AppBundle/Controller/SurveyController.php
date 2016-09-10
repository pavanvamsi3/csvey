<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Twilio\Twiml;


class SurveyController extends Controller
{
    /**
     * @Route("/survey", name="post_survey")
     *
     * @return View
     */
    public function postSurveyAction(Request $request)
    {
        $postData = $request->getContent();
        $surveyManager = $this->get('csvey_api.survey_manager');
        $survey = $surveyManager->add($postData);

        return new Response(json_encode(array('message' => 'success')));
    }

    /**
     * @Route("/survey/{surveyId}", name="twilio_response")
     * 
     *
     * @return Response
     */
    public function postUserSurveyAction($surveyId)
    {
        $requestParams = $this->get('request')->request->all();
        $surveyManager = $this->get->('csvey_api.survey_manager');
        $userManager = $this->get->('csvey_api.user_manager');
        $survey = $surveyManager->load($surveyId);
        $user = null;
        if (isset($requestParams['Called']) && $requestParams['Called']) {
            $user = $userManager->loadByPhoneNumber($requestParams['Called']);
        }

        if ($survey && $user && isset($requestParams['Digits'])) {
            $surveyUserManager = $this->get->('csvey_api.user_survey_manager');
            $surveyUserManager->add($requestParams, $survey->getId());
        }
        $response = new Twiml();
        $response->say("Thanks for your time, your csvey balance is updated to 10.
            You'll get a free recharge when it reaches ten rupees.");
        $response->redirect('/outbound', array("method"=> "GET"));

        return new Response($response);
    }
}
