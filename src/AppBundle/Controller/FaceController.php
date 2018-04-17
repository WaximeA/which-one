<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Face;
use AppBundle\Entity\FaceCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FaceController extends Controller
{
    /**
     * @Route("/faces", name="faces")
     */
    public function facesAction(){
        $faces = $this->getDoctrine()->getRepository('AppBundle:Face')->findAll();
        $categories = $this->getDoctrine()->getRepository('AppBundle:FaceCategory')->findAll();

        return $this->render('face/faces.html.twig', ['faces' => $faces, 'categories' => $categories]);
    }
}
