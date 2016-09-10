<?php

namespace AppBundle\Manager;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use AppBundle\Entity\Company;
use AppBundle\Entity\Survey;
use AppBundle\Entity\Choice;
use FOS\RestBundle\View\View;
use FOS\Rest\Util\Codes;

/**
 * Survey Manager
 */
class SurveyManager
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

        if (empty($requestParams['company']) && empty($requestParams['survey'])) {
            $message = 'Bad Request';
            return $message;
        }
        try {
            $conn = $this->doctrine->getConnection();
            $conn->beginTransaction();
            $company = new Company();
            $company->setName($requestParams['company']);
            $this->entityManager->persist($company);
            $this->entityManager->flush();

            foreach($requestParams['surveys'] as $survey) {
                $newSurvey = new Survey();
                $newSurvey->setQuestion($survey['question']);
                $newSurvey->setType($survey['type']);
                $newSurvey->setCompanyId($company);
                $this->entityManager->persist($newSurvey);
                $this->entityManager->flush();
                if (strtolower($survey['type']) == "multiple choice") {
                    foreach ($survey['options'] as $option) {
                        $choice = new Choice();
                        $choice->setChoiceName($option);
                        $choice->setSurveyId($newSurvey);
                        $this->entityManager->persist($choice);
                    }
                    $this->entityManager->flush();
                }
            }
            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    /**
     * Get survey
     *
     * @param integer $surveyId   - surveyId
     *
     * @return array
     */
    public function load($surveyId)
    {
        return $this->em->getRepository('AppBundle:Survey')
            ->findOneById($surveyId);
    }
}
