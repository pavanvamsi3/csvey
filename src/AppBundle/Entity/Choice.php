<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Choice
 *
 * @ORM\Table(name="choice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChoiceRepository")
 */
class Choice
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
     * @var string
     *
     * @ORM\Column(name="choice_name", type="string", length=255)
     */
    private $choiceName;

    /**
     * @var int
     *
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id", nullable=false)
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="choices")
     */
    private $surveyId;

    /**
     * @ORM\OneToMany(targetEntity="UserSurvey", mappedBy="choiceId")
     */
    private $userSurveys;

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
     * Set choiceName
     *
     * @param string $choiceName
     *
     * @return Choice
     */
    public function setChoiceName($choiceName)
    {
        $this->choiceName = $choiceName;

        return $this;
    }

    /**
     * Get choiceName
     *
     * @return string
     */
    public function getChoiceName()
    {
        return $this->choiceName;
    }

    /**
     * Set surveyId
     *
     * @param integer $surveyId
     *
     * @return Choice
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
}

