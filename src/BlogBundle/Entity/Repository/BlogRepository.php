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
            ->select('b,c')
            ->leftJoin('b.comments', 'c')
            ->addOrderBy('b.created', 'DESC');

        if (!$limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /*
     * @return array $tags or empty array
     */
    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
          ->select('b.tags')
          ->getQuery()
          ->getResult();

        if (!$blogTags) {
            return [];
        }

        $tags = [];
        foreach($blogTags as $blogTagProperty) {

            $tagsPerBlog = explode(",", $blogTagProperty['tags']);

            foreach ($tagsPerBlog as $tag) {
                $tags[] = trim($tag);
            }
        }

        return $tags;
    }

    /*
     * @param $tags
     * @return array $tagWeights or empty array
     */
    public function getTagWeights($tags)
    {
        $tagWeights = [];

        if (empty($tags)) {
            return $tagWeights;
        }

        foreach ($tags as $tag) {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }

        // Max of 5 weights
        $max = max($tagWeights);
        $multiplier = ($max > 5) ? 5 / $max : 1;
        foreach ($tagWeights as &$tag)
        {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }
}
