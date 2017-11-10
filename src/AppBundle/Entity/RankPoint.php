<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RankPoint
 *
 * @ORM\Table("rankPoints")
 * @ORM\Entity
 */
class RankPoint
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="place", type="integer")
     */
    protected $place;

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getRankType()
    {
        return $this->rankType;
    }

    /**
     * @param mixed $rankType
     */
    public function setRankType($rankType)
    {
        $this->rankType = $rankType;
    }


    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    protected $points;

    /**
     * @ORM\ManyToOne(targetEntity="RankType", inversedBy="rankPoints")
     * @ORM\JoinColumn(name="rankTypeId", referencedColumnName="id")
     */
    protected $rankType;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
