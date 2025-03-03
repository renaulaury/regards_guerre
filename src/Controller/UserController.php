<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserEditEmailFormType;
use App\Form\UserEditIdentityFormType;
use App\Form\UserEditNicknameFormType;
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

        //Création du formulaire
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

    /*********** Permet l'édition de l'email de l'utilisateur ************************/
    #[Route('/user/userEditEmail/{id}', name: 'userEditEmail')]
    public function userEditEmail(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Création du form
        $form = $this->createForm(UserEditEmailFormType::class, $user, ['entityManager' => $entityManager]);

        // Traiter la requête HTTP
        $form->handleRequest($request);

        // Vérif du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la page de profil de l'utilisateur
            return $this->redirectToRoute('profile', ['id' => $user->getId()]);
        }

        // Rendre le template avec le formulaire
        return $this->render('user/userEditEmail.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /*********** Permet l'édition du pseudo de l'utilisateur ************************/
    #[Route('/user/userEditNickname/{id}', name: 'userEditNickname')]
    public function userEditNickname(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Création du form
        $form = $this->createForm(UserEditNicknameFormType::class, $user, ['entityManager' => $entityManager]);

        // Traiter la requête HTTP
        $form->handleRequest($request);

        // Vérif du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la page de profil de l'utilisateur
            return $this->redirectToRoute('profile', ['id' => $user->getId()]);
        }

        // Rendre le template avec le formulaire
        return $this->render('user/userEditNickname.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
