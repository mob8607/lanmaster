<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table("games")
 * @ORM\Entity
 */
class Game
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
     * @var boolean
     *
     * @ORM\Column(name="teamgame", type="boolean")
     */
    protected $teamgame;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="RankType", inversedBy="matches")
     * @ORM\JoinColumn(name="rankTypeId", referencedColumnName="id")
     */
    protected $rankType;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isTeamgame()
    {
        return $this->teamgame;
    }

    /**
     * @param bool $teamgame
     */
    public function setTeamgame($teamgame)
    {
        $this->teamgame = $teamgame;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
