<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Form\UserEditIdentityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{

    /*********** Affiche l'index de l'utilisateur ************************/
    #[Route('/profile', name: 'index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [

        ]);
    }

/*********** Affiche le profil de l'utilisateur ************************/
    #[Route('/profile/{id}', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', [

        ]);
    }

/*********** Permet l'édition du nom et prénom de l'utilisateur ************************/
    #[Route('/profile/userEditIdentity/{id}', name: 'userEditIdentity')]
    public function editUserName(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(UserEditIdentityFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profile', ['id' => $id]);
        }

        return $this->render('user/userEditIdentity.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
