<?php

namespace AppBundle\Manager;

use AppBundle\Entity\UserSurvey;
use Symfony\Component\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

/**
 * User Survey Manager
 */
class UserSurveyManager 
{
    /**
     * Constructor
     *
     * @param Doctrine $doctrine - Doctrine
     *
     */
    public function __construct(Doctrine $doctrine) 
    {
        parent::__construct($doctrine);

        $this->userRepo = $this->em->getRepository('AppBundle:User');
        $this->surveyRepo = $this->em->getRepository('AppBundle:Survey');
        $this->choiceRepo = $this->em->getRepository('AppBundle:Choice');
    }

    /**
     * Add User Response
     *
     * @param array   $requestParams - Request Params
     * @param integer $surveyId      - Survey
     *
     * @return array
     */
    public function add($requestParams, $surveyId)
    {
        if ($surveyId && isset($requestParams['user_number'])) 
        {
            $userSurvey = new UserSurvey();
            $survey = $this->surveyRepo->findOneById($surveyId);
            $user = $this->userRepo->findOneByPhone($requestParams['user_number']);
            if($survey->getType() == 'multiple choice') {
                $choice = $this->choiceRepo->fineOneBy(
                    array(
                        'choiceName' => $requestParams['choice'],
                        'surveyId' => $surveyId
                    )
                );
                $userSurvey->setChoiceId($choice);
            } else {
                $userSurvey->setRating($requestParams['choice']);
            }   
            $userSurvey->setSurveyId($survey);
            $userSurvey->setUserId($user);
        }
        $this->em->persist($userSurvey);
        $this->em->flush();

        return $userSurvey;
    }
}
