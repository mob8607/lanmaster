<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table("matches")
 * @ORM\Entity
 */
class Match
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
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="matches")
     * @ORM\JoinColumn(name="eventId", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="matches")
     * @ORM\JoinColumn(name="gameId", referencedColumnName="id")
     */
    private $game;

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
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
}
