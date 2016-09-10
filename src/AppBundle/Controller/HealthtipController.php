<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;


class HealthtipController extends Controller
{
    /**
     * @Route("/healthtip", name="post_healthtip")
     *
     * @return View
     */
    public function postHealthtipAction(Request $request)
    {
        $postData = $request->getContent();
        $healthTipManager = $this->get('csvey_api.health_tip_manager');
        $survey = $healthTipManager->add($postData);

        return new Response(json_encode(array('message' => 'success')));
    }

}
