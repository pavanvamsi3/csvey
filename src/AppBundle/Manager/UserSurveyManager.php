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
        if ($surveyId && isset($requestParams['Called'])) {
            $survey = $this->surveyRepo->findOneById($surveyId);
            $user = $this->userRepo->findOneByPhone($requestParams['Called']);
            if (isset($requestParams['Digits']) && ($requestParams['Digits'] < 1 || $requestParams['Digits'] > 5)) {
                return "failed";
            }
            if($survey->getType() == 'multiple choice') {
                $choices = $this->choiceRepo->findBySurveyId($surveyId);
                $userSurvey->setChoiceId($choices[$requestParams['Digits']]);
            } else {
                $userSurvey->setRating($requestParams['Digits']);
            }
            $userSurvey->setSurveyId($survey);
            $userSurvey->setUserId($user);
            $balance = $user->getBalance();
            if ($balance === null) {
                $user->setBalance(0);
            } else {
                $user->setBalance($balance + 10);
            }
            $this->em->persist($userSurvey);
            $this->em->persist($user);
            $this->em->flush();
        }

        return "success";
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
        $counts = array();
        $survey = $this->surveyRepo->findOneById($surveyId);
        $qb = $this->em->createQueryBuilder();

        if ($survey->getType() === "multiple choice") {
            $qb->select('c.choiceName, count(us.id)');
            $counts['type'] = 'multiple choice';
        } else {
            $qb->select('count(us.id)');
            $counts['type'] = 'rating';
        }
        $qb->from('AppBundle:UserSurvey', 'us')
            ->andWhere('us.surveyId = :surveyId')
            ->setParameter('surveyId', $surveyId);
        if ($survey->getType() === "multiple choice") {
            $qb->innerjoin('us.choiceId', 'c')
               ->groupBy('us.choiceId');
        } else {
            $qb->groupBy('us.rating');
        }
        $query = $qb->getQuery();
        $counts['count'] = $query->getResult();
        return $counts;
    }
}
