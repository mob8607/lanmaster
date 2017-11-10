<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Game;
use AppBundle\Entity\Match;
use AppBundle\Entity\Player;
use AppBundle\Entity\RankPoint;
use AppBundle\Entity\RankType;
use AppBundle\Entity\Result;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, UserPasswordEncoderInterface $encoder)
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
     * @Route("/create-password/{plainPW}", name="create-password")
     */
    public function createPasswordAction(UserPasswordEncoderInterface $encoder, $plainPW)
    {
        $user = new User();
        $plainPassword = $plainPW;
        $encoded = $encoder->encodePassword($user, $plainPassword);

        return new Response(
            '<html><body><p>' . $encoded . '</p></body></html>'
        );
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request)
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
        $results = $this->getDoctrine()->getRepository(Result::class)->findAllByEvent($eventId);

        $playerResults = [];
        $games = [];
        /** @var Result $result */
        foreach ($results as $result) {
            $game = $result->getMatch()->getGame();
            $games[$game->getName()] = $game->getId();
            $player = $result->getPlayer();

            $playerResults[$player->getId()]['player'] = $player;
            /** @var RankPoint $rankPoints */
            $rankPoints = $this->getDoctrine()->getRepository(RankPoint::class)->findOneBy([
                'place' => $result->getRank(),
                'rankType' => $game->getRankType(),
            ]);

            $points = 0;
            if ($rankPoints) {
                $points = $rankPoints->getPoints();
            }

            $playerResults[$player->getId()]['results'][$game->getId()] = [
                'rank' => $result->getRank(),
                'points' => $points,
            ];

            $currentScore = 0;
            if (array_key_exists('score', $playerResults[$player->getId()])) {
                $currentScore = $playerResults[$player->getId()]['score'];
            }
            $playerResults[$player->getId()]['score'] = $currentScore + $points;
        }

        usort($playerResults, function($b, $a) {
            return $a['score'] - $b['score'];
        });

        $beforeScore = 0;
        $beforePlace = 1;
        foreach ($playerResults as $key => $playerResult) {
            $place = $key + 1;

            if ($playerResult['score'] === $beforeScore) {
                $place = $beforePlace;
            }

            $playerResults[$key]['place'] = $place;

            $beforeScore = $playerResult['score'];
            $beforePlace = $place;
        }


        return $this->render(
            '@App/default/event.html.twig',
            [
                'results' => $playerResults,
                'games' => $games,
                'eventId' => $eventId,
            ]
        );
    }

    /**
     * @Route("/games/{gameId}", name="game")
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

    /**
     * @Route("/admin/add-game/", name="add-game")
     */
    public function addGameAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = '';

        if ($request->getMethod() == 'POST') {
            $name = $request->request->get('name');
            $rankTypeString = $request->request->get('rank-type');
            $rankType = $this->getDoctrine()->getRepository(RankType::class)->find($rankTypeString);

            $teamgameString = $request->request->get('teamgame');
            $teamgame = 'on' === $teamgameString;

            if ($name && $rankType) {
                $game = new Game();
                $game->setName($name);
                $game->setRankType($rankType);
                $game->setTeamgame($teamgame);
                $em->persist($game);
                $em->flush();

                $message = 'Spiel hinzugefügt';
            }
        }


        $rankTypes = $this->getDoctrine()->getRepository(RankType::class)->findAll();

        return $this->render(
            '@App/default/add-game.html.twig',
            [
                'rankTypes' => $rankTypes,
                'message' => $message,
            ]
        );
    }

    /**
     * @Route("/admin/add-event/", name="add-event")
     */
    public function addEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = '';

        if ($request->getMethod() == 'POST') {
            $name = $request->request->get('name');

            if ($name) {
                $event = new Event();
                $event->setName($name);
                $em->persist($event);
                $em->flush();

                $message = 'Event hinzugefügt';
            }
        }

        return $this->render(
            '@App/default/add-event.html.twig',
            [
                'message' => $message,
            ]
        );
    }


    /**
     * @Route("/admin/add-player/", name="add-player")
     */
    public function addPlayerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = '';

        if ($request->getMethod() == 'POST') {
            $firstName = $request->request->get('first-name');
            $lastName = $request->request->get('last-name');
            $nickname = $request->request->get('nickname');

            if ($firstName && $lastName && $nickname) {
                $player = new Player();
                $player->setFirstName($firstName);
                $player->setLastName($lastName);
                $player->setNickname($nickname);
                $em->persist($player);
                $em->flush();

                $message = 'Spieler hinzugefügt';
            }
        }

        return $this->render(
            '@App/default/add-player.html.twig',
            [
                'message' => $message,
            ]
        );
    }


    /**
     * @Route("/admin/add-result/{eventId}/{gameId}", name="add-result")
     */
    public function addResultAction($eventId, $gameId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = '';
        $match = $this->getDoctrine()->getRepository(Match::class)->findOneBy([
            'game' => $gameId,
            'event' => $eventId,
        ]);

        if ($request->getMethod() === 'POST') {
            $playerId = $request->request->get('player');
            $rank = $request->request->get('rank');

            $player = $this->getDoctrine()->getRepository(Player::class)->find($playerId);

            if (empty($match)) {
                $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);
                $game = $this->getDoctrine()->getRepository(Game::class)->find($gameId);
                $match = new Match();
                $match->setEvent($event);
                $match->setGame($game);
                $em->persist($match);
            }

            $oldResult = $this->getDoctrine()->getRepository(Result::class)->findOneBy([
                'match' => $match,
                'player' => $player,
            ]);

            if (empty($oldResult) && $player && $rank && $match) {
                $result = new Result();
                $result->setRank($rank);
                $result->setPlayer($player);
                $result->setMatch($match);

                $em->persist($result);
                $em->flush();

                $message = 'Resultat gespeichert';
            } else {
                $message = 'Fehler beim Speichern';
            }
        }

        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();
        $allPlayers = $this->getDoctrine()->getRepository(Player::class)->findAll();
        $players = [];

        foreach($allPlayers as $player) {
            $result = $this->getDoctrine()->getRepository(Result::class)->findOneBy([
                'player' => $player,
                'match' => $match,
            ]);

            if (empty($result)) {
                $players[] = $player;
            }
        }

        return $this->render(
            '@App/default/add-result.html.twig',
            [
                'message' => $message,
                'games' => $games,
                'players' => $players,
                'eventId' => $eventId,
            ]
        );
    }

    /**
     * @Route("/admin/select-game/{eventId}", name="select-game")
     */
    public function selectGameAction($eventId, Request $request)
    {
        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();

        return $this->render(
            '@App/default/select-game.html.twig',
            [
                'games' => $games,
                'eventId' => $eventId,
            ]
        );
    }
}
