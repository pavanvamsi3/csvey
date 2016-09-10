<?php

namespace AppBundle\Manager;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use AppBundle\Entity\Healthtip;
use FOS\RestBundle\View\View;
use FOS\Rest\Util\Codes;

/**
 * Health tip Manager
 */
class HealthtipManager
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
    }

    /**
     * @return array
     */
    public function add($requestParams)
    {
        $requestParams = json_decode($requestParams, true);
        if (empty($requestParams['tip']) && empty($requestParams['age_range'])) {
            $message = 'Bad Request';
            return $message;
        }
        try {
            $healthTip = new Healthtip();
            $healthTip->setTip($requestParams['tip']);
            $healthTip->setMinAge($requestParams['min_age']);
            $healthTip->setMaxAge($requestParams['max_age']);
            $this->entityManager->persist($healthTip);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
