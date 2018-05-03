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

    /**
     * @Route("/vote/{faceId}", name="vote")
     */
    public function voteAction($faceId){

        $face = $this->getDoctrine()->getRepository('AppBundle:Face')->find($faceId);
        $faceUsers = $face->getUsers();

        $user = $this->getUser();
        $userId = $user->getId();

        // Retrieve flashbag from the controller
        $flashbag = $this->get('session')->getFlashBag();

        // Check if current user not allready vote for the current face
        foreach ($faceUsers as $user) {
           if ($user->getId() == $userId){
                $flashbag->add('warning', 'You can\'t vote twice for '.$face->getTitle());

                return $this->redirectToRoute('homepage', ['error']);
            }
        }

        // Add one vote to the total on the current face votes
        $vote = $face->getNbVote();
        $newVoteCount = $vote+1;
        $face->setNbVote($newVoteCount);

        // Add current user to the face and set his proposal
        $face->addUser($user);
        $user->addProposal($face);

        // Save data
        $em = $this->getDoctrine()->getManager();
        $em->persist($face);
        $em->flush();

        // Add success flash message
        $flashbag->add('success', 'Bravo! You vote for '.$face->getTitle().' :)');

        return $this->redirectToRoute('homepage', ['face' => $face, 'success']) ;
    }

}
