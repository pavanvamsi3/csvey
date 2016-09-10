<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserSurveyController extends Controller
{
    /**
     * Post a user response
     *
     * @Route(
     *      path    = "/user/{surveyId}/response",
     *      methods = {"POST"}
     *  )
     *
     * @return array
     */
    public function postUserResponseAction($surveyId)
    {
        $queryParams = $this->get('request')->request->all();
        $userSurveyManager = $this->container->get('workflow_api.user_survey_manager');
        
        $userSurvey = $userSurveyManager->add($queryParams, $surveyId);

        return View::create($userSurvey);
    }
}   
