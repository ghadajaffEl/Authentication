<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    /**
     * @Route("/candidate", name="candidate")
     */
    public function index()
    {
        $user =$this->getUser();
        return $this->render('candidate/index.html.twig', [
            'name' => $user->getName(),
        ]);
    }
}
