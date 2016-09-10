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
     * @Route("/surveyquestions/{company}", name="get_survey")
     *
     * @return View
     */
    public function getSurveyAction($company)
    {
        $surveyManager = $this->get('csvey_api.survey_manager');
        $survey = $surveyManager->loadQuestions($company);

        return new Response(json_encode($survey));
    }

    /**
     * @Route("/surveyoptions/{surveyId}", name="get_option")
     *
     * @return View
     */
    public function getOptionAction($surveyId)
    {
        $surveyManager = $this->get('csvey_api.survey_manager');
        $options = $surveyManager->loadOptions($surveyId);

        return new Response(json_encode($options));
    }

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
        $surveyManager = $this->get('csvey_api.survey_manager');
        $userManager = $this->get('csvey_api.user_manager');
        $survey = $surveyManager->load($surveyId);
        $user = null;
        if (isset($requestParams['Called']) && $requestParams['Called']) {
            $user = $userManager->loadByPhoneNumber($requestParams['Called']);
        }
        $surveyResponse = "failed";
        if ($survey && $user && isset($requestParams['Digits'])) {
            $surveyUserManager = $this->get('csvey_api.user_survey_manager');
            $surveyResponse = $surveyUserManager->add($requestParams, $survey->getId());
        }

        $response = new Twiml();
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->em->refresh($user);
        if ($surveyResponse == "success") {
            $response->say("Thanks for your time, your csvey balance is updated to 10.
            You'll get a free recharge when it reaches ten rupees.");   
        } else {
            $response->say("Sorry The Digit you have pressed is not valid."); 
        }
        $response->redirect('/outbound', array("method"=> "GET"));

        return new Response($response);
    }
}
