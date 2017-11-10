<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table("results")
 * @ORM\Entity
 */
class Result
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
     * @ORM\Column(name="rank", type="integer")
     */
    protected $rank;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="results")
     * @ORM\JoinColumn(name="playerId", referencedColumnName="id")
     */
    protected $player;

    /**
     * @ORM\ManyToOne(targetEntity="Match", inversedBy="results")
     * @ORM\JoinColumn(name="matchId", referencedColumnName="id")
     */
    protected $match;

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param mixed $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param Match $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
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
}
