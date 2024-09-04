<?php

namespace App\Controller;



use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profiles', name: 'app_profiles')]
    public function showListUsers(EntityManagerInterface $entityManager): Response
    {
        $users= $entityManager-> getRepository(User::class)->findAll();

        return $this->render('profile/list_users.html.twig', [ 'users' => $users]);
    }


    /**
     *@Route("/detail/{id}" , name ="_detail_id")
     */
    #[Route('/profile/{id}', name: 'app_profile_id')]
    public function UserDetails(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        return $this->render('profile/user_detail.html.twig',['user' => $user] );
    }

}
