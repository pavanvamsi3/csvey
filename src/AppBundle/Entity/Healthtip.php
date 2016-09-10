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
     * @ORM\Column(name="min_age", type="integer")
     */
    private $minAge;

    /**
     * @var string
     *
     * @ORM\Column(name="max_age", type="integer")
     */
    private $maxAge;



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
     * Set minAge
     *
     * @param string $minAge
     *
     */
    public function setMinAge($minAge)
    {
        $this->minAge = $minAge;
    }

    /**
     * Get minAge
     *
     * @return integer
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * Set maxAge
     *
     * @param string $maxAge
     *
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;
    }

    /**
     * Get maxAge
     *
     * @return integer
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }
}

