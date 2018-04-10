<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function getMenuAction($route)
    {
        return $this->render('page/navbar.html.twig', ['route' => $route]);
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentationAction()
    {
        return $this->render('default/presentation.html.twig');
    }

    /**
     * @Route("/faces", name="faces")
     */
    public function facesAction()
    {
        return $this->render('default/faces.html.twig');
    }

    /**
     * @Route("/top", name="top")
     */
    public function topAction()
    {
        return $this->render('default/top.html.twig');
    }
}
