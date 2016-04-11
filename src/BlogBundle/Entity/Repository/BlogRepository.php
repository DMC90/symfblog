<?php

namespace BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BlogRepository
 *
 */
class BlogRepository extends EntityRepository
{
    /**
     * @param null $limit
     * @return array
     */
    public function getLatestBlogs($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->addOrderBy('b.created', 'DESC');

        if (!$limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
