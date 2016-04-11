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
        /* var @em Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getEntityManager();

        /* @var $blogs \BlogBundle\Entity\Repository\BlogRepository  */
        $blogs = $em->getRepository('BlogBundle:Blog');
        $blogs->getLatestBlogs();

        $blogs = $em->createQueryBuilder()
            ->select('b')
            ->from('BlogBundle:Blog', 'b')
            ->addOrderBy('b.created', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('BlogBundle:Page:index.html.twig', [
            'blogs' => $blogs
        ]);
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
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from Symfblog')
                    ->setFrom('info@symfblog.com')
                    ->setTo($this->container->getParameter('blog.emails.contact_email'))
                    ->setBody($this->renderView('BlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));

                $this->get('mailer')->send($message);

                $this->get('session')
                    ->getFlashBag()
                    ->add('email-notice', 'Your contact enquiry was successfully sent. Thank you!'
                    );

                return $this->redirect($this->generateUrl('blog_contact'));
            }
        }

        return $this->render('BlogBundle:Page:contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}