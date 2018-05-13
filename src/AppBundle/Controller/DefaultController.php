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
        $faces = $this->getDoctrine()->getRepository('AppBundle:Face')->findAll();
        $categories = $this->getDoctrine()->getRepository('AppBundle:FaceCategory')->findAll();
        $highestFaces = $this->getHighestFaces();
        $topFaceCategories = $this->beautifyTopFaceCategories();

        return $this->render('default/top.html.twig', ['faces' => $faces, 'categories' => $categories, 'highestFaces' => $highestFaces, 'topFaceCategories' => $topFaceCategories]);
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
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM face ORDER BY createdAt DESC LIMIT 10");
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getTopFaceCategories($categoryId)
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM face WHERE category_id = ".$categoryId." ORDER BY nbVote DESC LIMIT 1");
        $statement->execute();
        $results = $statement->fetchAll();

        return $results[0];
    }

    public function beautifyTopFaceCategories()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:FaceCategory')->findAll();
        $beautifiedTopCategories = [];

        foreach ($categories as $category){
            $categoryId = $category->getId();
            $beautifiedTopCategories[$categoryId] = $this->getTopFaceCategories($categoryId);
        }

        return $beautifiedTopCategories;
    }

    /**
     * @Route("/category/{categoryId}", name="show_category")
     */
    public function showFaceAction($categoryId)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:FaceCategory')->find($categoryId);
        $highestFaces = $this->getTopFaceCategories($categoryId);

        return $this->render('category/show_category.html.twig', ['category' => $category, 'highestFace' => $highestFaces]) ;
    }
}
