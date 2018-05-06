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
        $lastFaces = $this->getLastFaces();

        return $this->render('default/index.html.twig', ['faces' => $faces, 'categories' => $categories, 'lastFaces' => $lastFaces]);
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
        $highestFaces = $this->getHighestFaces();

        return $this->render('default/top.html.twig', ['highestFaces' => $highestFaces]);
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

    public function getHighestFaces()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM face ORDER BY nbVote DESC LIMIT 5");
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;

    }

    public function getLastFaces()
    {
        $faces = $this->getDoctrine()->getRepository('AppBundle:Face')->findAll();
        $faceNumber = count($faces);
        $lastFaces = array_slice($faces, $faceNumber - 10, $faceNumber);

        return $lastFaces;
    }
}
