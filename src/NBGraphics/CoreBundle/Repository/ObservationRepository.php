<?php

namespace NBGraphics\CoreBundle\Repository;

use NBGraphics\CoreBundle\Entity\Observation;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByFamily($taxref, $status)
    {
        return $this
            ->createQueryBuilder('o')
            ->leftJoin('o.taxref', 't')
            ->where('t.famille = :famille')
            ->andWhere('o.status = :status')
            ->setParameter('famille', $taxref->getFamille())
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLatestObservations($status)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status', $status)
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findDistinctDepartementQB($sort = 'ASC')
    {
        return $this
            ->createQueryBuilder('o')
            ->orderBy('o.departement', $sort)
            ->groupBy('o.departement')
        ;
    }

    public function myObservationsFromQB($user)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
        ;
    }

    /**
     * @return Observation[]
     */
    public function findMyObservations($user, $sort = 'DESC')
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.user = :user')
            ->orderBy('o.id', $sort)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Observation[]
     */
    public function findMyLastObservations($user)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countMyObservations($user)
    {
        return $this
            ->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countObservations()
    {
        return $this
            ->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /* LISTS */

    public function findObservations($sort = 'DESC')
    {
        return $this
            ->createQueryBuilder('o')
            ->orderBy('o.id', $sort)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findObservationsByStatusAndOrder($status, $sort = 'DESC')
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.status = :status')
            ->orderBy('o.id', $sort)
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }

    public function exportAll()
    {
        return $this
            ->createQueryBuilder('o')
            ->getQuery()
            ->iterate()
        ;
    }

}
