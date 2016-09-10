<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;


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

}
