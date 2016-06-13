<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * Renders index html
     * @param $name
     */
    public function indexAction($name)
    {
        return $this->render('BlogBundle:Default:index.html.twig', [
            'name' => $name
        ]);
    }

    /**
     * Show a blog entry
     * @param $id
     * @param $slug
     */
    public function showAction($id, $slug)
    {
        /* var @em Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getEntityManager();

        $blog = $em->getRepository('BlogBundle:Blog')->find($id);

        if(!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $em->getRepository('BlogBundle:Comment')
                        ->getCommentsForBlog($blog->getId());

        return $this->render('BlogBundle:Blog:show.html.twig', [
            'blog'      => $blog,
            'comments'   => $comments
        ]);
    }
}