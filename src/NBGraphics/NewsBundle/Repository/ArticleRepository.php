<?php

namespace NBGraphics\NewsBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findArticles($sort = 'DESC')
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.id', $sort)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countArticlesByState($state)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.state = :state')
            ->setParameter('state', $state)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function listArticles($state, $page = 1, $maxPerPage = 10, $sort = 'DESC')
    {
        $query = $this
            ->createQueryBuilder('a')
            ->where('a.state = :state')
            ->orderBy('a.id', $sort)
            ->setParameter('state', $state)
            ->setFirstResult(($page - 1) * $maxPerPage)
            ->setMaxResults($maxPerPage)
        ;

        return new Paginator($query);
    }
}
