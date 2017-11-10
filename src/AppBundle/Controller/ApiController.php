<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    /**
     * @Route("/events", name="events")
     */
    public function getEvents()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return new JsonResponse(array('name' => $events));
    }
}
