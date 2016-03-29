<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Enquiry;
use BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlogBundle:Page:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('BlogBundle:Page:about.html.twig');
    }

    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                return $this->redirect($this->generateUrl('blog_contact'));
            }
        }

        return $this->render('BlogBundle:Page:contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}