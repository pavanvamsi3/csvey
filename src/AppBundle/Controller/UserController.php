<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;


class UserController extends Controller
{
    /**
     * @Route(
     *      path    = "/user",
     *      methods = {"POST"}
     *  )
     *
     * @return View
     */
    public function postUserAction(Request $request)
    {
        $postData = $request->getContent();
        $userManager = $this->get('csvey_api.user_manager');
        $user = $userManager->add($postData);

        return new Response(json_encode(array('message' => 'success')));
    }

}
