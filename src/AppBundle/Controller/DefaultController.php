<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $faces = $this->getDoctrine()->getRepository('AppBundle:Face')->findAll();
        $categories = $this->getDoctrine()->getRepository('AppBundle:FaceCategory')->findAll();

        return $this->render('default/index.html.twig', ['faces' => $faces, 'categories' => $categories]);
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
     * @Route("/top", name="top")
     */
    public function topAction()
    {
        return $this->render('default/top.html.twig');
    }

    /**
     * @Route("/search", name="searchhp", defaults={"word" = false})
     * @Route("/search/{word}", name="search")
     */
    public function searchAction(Request $request, $word)
    {
        $news = [];

        if($word){
            $news = $this->getDoctrine()->getRepository('AppBundle:Face')->search( $word );
        }
        $searchForm = $this->createFormBuilder()
            ->add('word', TextType::class, ['label' => 'Search'])
            ->add('submit', SubmitType::class, ['label' => 'Send research'])
            ->getForm();


        $searchForm->handleRequest($request);
        if($searchForm->isSubmitted() && $searchForm->isValid()){
            $data = $searchForm->getData();
            $news = $this->getDoctrine()->getRepository('AppBundle:Face')->search( $data['word'] );
        }

        return $this->render('default/search.html.twig', [
            'news' => $news,
            'form' => $searchForm->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
