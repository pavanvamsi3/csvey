<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Healthtip
 *
 * @ORM\Table(name="healthtip")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HealthtipRepository")
 */
class Healthtip
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
     * @ORM\Column(name="tip", type="string", length=1024)
     */
    private $tip;

    /**
     * @var string
     *
     * @ORM\Column(name="age_range", type="string", length=255)
     * @Assert\Choice(choices={"18-30", "30-40", "40-50", "50-60", "60+"})
     */
    private $ageRange;


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
     * Set tip
     *
     * @param string $tip
     *
     * @return Healthtip
     */
    public function setTip($tip)
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * Get tip
     *
     * @return string
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * Set ageRange
     *
     * @param string $ageRange
     *
     * @return Healthtip
     */
    public function setAgeRange($ageRange)
    {
        $this->ageRange = $ageRange;

        return $this;
    }

    /**
     * Get ageRange
     *
     * @return string
     */
    public function getAgeRange()
    {
        return $this->ageRange;
    }
}

