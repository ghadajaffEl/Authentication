<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;

class CandidateController extends AbstractController
{
    /**
     * @Route("/candidate", name="candidate")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['name' => $user->getName(), 'email' => $user->getEmail()]);
        }
        return $this->render('candidate/index.html.twig');
    }

    /**
     * @Route("/candidateList", name="candidateList")
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function candidateList(ObjectManager $manager)
    {
        $form = $this->createForm(UserType::class);
        $candidates = $manager->getRepository(User::class)->findAll();
        return $this->render('candidateList.html.twig', ['candidates' => $candidates, 'form' => $form->createView()]);

    }

    /**
     * @Route("/addCandidate",name="addCandidate")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function addCandidate(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($passwordEncoder->encodePassword($user, 'engage'));
                $user->setRoles(['ROLE_USER']);
                $manager->persist($user);
                $manager->flush();
                $newUser = $this->renderView('candidateCard.html.twig',['candidate'=>$user]);
                return new JsonResponse(['newUser'=>$newUser]);
            }
            else{
                return new JsonResponse(['response'=>'fail']);
            }




        }

    }




}
