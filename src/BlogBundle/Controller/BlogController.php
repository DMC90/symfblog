<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BlogBundle:Default:index.html.twig', ['name' => $name]);
    }
}