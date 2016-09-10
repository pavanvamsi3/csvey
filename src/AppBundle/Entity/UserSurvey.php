<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSurvey
 *
 * @ORM\Table(name="user_survey")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserSurveyRepository")
 */
class UserSurvey
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false))
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userSurveys")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id", nullable=false)
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="userSurveys")
     */
    private $surveyId;

    /**
     * @var int
     *
     * @ORM\JoinColumn(name="choice_id", referencedColumnName="id", nullable=true)
     * @ORM\ManyToOne(targetEntity="Choice", inversedBy="userSurveys")
     */
    private $choiceId;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserSurvey
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set surveyId
     *
     * @param integer $surveyId
     *
     * @return UserSurvey
     */
    public function setSurveyId($surveyId)
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    /**
     * Get surveyId
     *
     * @return int
     */
    public function getSurveyId()
    {
        return $this->surveyId;
    }

    /**
     * Set choiceId
     *
     * @param integer $choiceId
     *
     * @return UserSurvey
     */
    public function setChoiceId($choiceId)
    {
        $this->choiceId = $choiceId;

        return $this;
    }

    /**
     * Get choiceId
     *
     * @return int
     */
    public function getChoiceId()
    {
        return $this->choiceId;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return UserSurvey
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }
}

