<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table("events")
 * @ORM\Entity
 */
class Event
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
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * Many Events have Many Players.
     * @ORM\ManyToMany(targetEntity="Player", mappedBy="events")
     */
    protected $players;


    /**
     * Many Events have Many Games.
     * @ORM\ManyToMany(targetEntity="Game", mappedBy="events")
     */
    protected $games;

    public function __construct() {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @return array
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param array
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * @param Player
     */
    public function addPlayer($player)
    {
        $this->players[] = $player;
    }

    /**
     * @param Game
     */
    public function addGame($game)
    {
        $this->games[] = $game;
    }
}
