<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PersonController extends Controller
{
    /**
     * @Route("/viewpersons", name="viewpersonspage")
     */
    public function viewPersonsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entries = $em->getRepository("AppBundle:Person")->findAll();

        return $this->render('accounts/viewpersons.html.twig', array('persons'=>$entries));
    }


}