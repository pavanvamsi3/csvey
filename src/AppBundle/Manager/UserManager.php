<?php

namespace AppBundle\Manager;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\Choice;
use FOS\RestBundle\View\View;
use FOS\Rest\Util\Codes;

/**
 * User Manager
 */
class UserManager
{
    protected $doctrine;

    /**
     * Constructor
     *
     * @param Doctrine              $doctrine           - Doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $this->doctrine->getManager();
        $this->userRepo = $this->em->getRepository('AppBundle:User');
    }

    /**
     * @return array
     */
    public function add($requestParams)
    {
        $user = null;
        $requestParams = json_decode($requestParams, true);
        if (isset($requestParams['user_number'])) {
            $user = $this->userRepo->findOneByPhone($requestParams['user_number']);
            if (!$user) {
                $user = new User();
                $user->setPhone($requestParams['user_number']);
                if (isset($requestParams['age'])) {
                    $user->setAge($requestParams['age']);
                }
                $this->em->persist($user);
                $this->em->flush();
            } 
        }

        return $user;
    }
}