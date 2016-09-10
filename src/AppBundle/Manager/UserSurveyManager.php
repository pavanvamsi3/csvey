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
    protected $doctrine;
    /**
     * Constructor
     *
     * @param Doctrine $doctrine - Doctrine
     *
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
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
        $userSurvey = new UserSurvey();
        if ($surveyId && isset($requestParams['user_number'])) {
            $survey = $this->surveyRepo->findOneById($surveyId);
            $user = $this->userRepo->findOneByPhone($requestParams['user_number']);
            if($survey->getType() == 'multiple choice') {
                $choice = $this->choiceRepo->findOneBy(
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
            $this->em->persist($userSurvey);
            $this->em->flush();
        }

        return $userSurvey;
    }

    /**
     * Survey Count
     *
     * @param integer $surveyId      - Survey
     *
     * @return array
     */
    public function loadSurveyCounts($surveyId)
    {
        $survey = $this->surveyRepo->findOneById($surveyId);
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(us.id)')
            ->from('AppBundle:UserSurvey', 'us')
            ->andWhere('us.surveyId = :surveyId')
            ->setParameter('surveyId', $surveyId);

        if ($survey->getType() === "multiple choice") {
            $qb->groupBy('us.choiceId');
        } else {
            $qb->groupBy('us.rating');
        }
        $query = $qb->getQuery();
        $counts = $query->getResult();

        return $counts;
    }
}
