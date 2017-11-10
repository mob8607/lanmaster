<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ResultRepository extends EntityRepository
{
    public function findAllByEvent($eventId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('r')
            ->from('AppBundle:Result', 'r')
            ->innerJoin('AppBundle:Match', 'm', 'WITH', 'm.id = r.match')
            ->where('m.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery();

        return $query->getResult();
    }
}
