<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Game;
use AppBundle\Entity\Match;
use AppBundle\Entity\Player;
use AppBundle\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render(
            '@App/default/index.html.twig',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * @Route("/events/{eventId}", name="event")
     */
    public function eventAction($eventId)
    {
        $gameResults = [];
        $matches = $this->getDoctrine()->getRepository(Match::class)->findBy(
            [
                'event' => $eventId,
            ]
        );

        foreach ($matches as $match) {
            $gameResults[$match->getGame()->getName()] = $this->getDoctrine()->getRepository(Result::class)->findBy(
                [
                    'match' => $match->getId(),
                ]
            );
        }

        return $this->render(
            '@App/default/event.html.twig',
            [
                'results' => $gameResults,
            ]
        );
    }

    /**
     * @Route("/games/{$gameId}", name="game")
     */
    public function gameAction($gameId)
    {
        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();

        return $this->render(
            '@App/default/game.html.twig',
            [
                'players' => $players,
            ]
        );
    }
}
