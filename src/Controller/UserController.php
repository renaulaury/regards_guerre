<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserEditEmailFormType;
use App\Form\UserEditIdentityFormType;
use App\Form\UserEditNicknameFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Form\Security\ChangePasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class UserController extends AbstractController
{

    /*********** Affiche l'accueil' de l'utilisateur ************************/
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
    public function userEditName(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
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

/*********** Permet l'édition du mdp l'utilisateur ************************/
    #[Route('/userEditPassword/{id}', name: 'userEditPassword')]
    public function userEditPassword(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Vérification que l'utilisateur connecté est celui qu'il tente de modifier
        if ($this->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier ce mot de passe.');
            return $this->redirectToRoute('profile'); 
        }


        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('password')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
                return $this->redirectToRoute('profile', ['id' => $user->getId()]);
            } else {
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
            }
        }
       
        return $this->render('user/userEditPassword.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
 
    }

/*********** Suppression profil de l'utilisateur ************************/
    //Envoi vers la confirm
    #[Route('/userDeleteProfile/{id}', name: 'userDeleteProfile')]
    public function userDeleteProfile(User $user): Response
    {
        return $this->render('user/userDeleteProfile.html.twig', [
            'user' => $user,
        ]);
    }
  

    //Confirm définitive
    #[Route('/userDeleteConfirmProfile/{id}', name: 'userDeleteConfirmProfile')]
    public function userDeleteConfirmProfile(
        User $user,
        EntityManagerInterface $entityManager,
        Security $security,
        RequestStack $requestStack
        ): Response {

      // Vérifie que l'utilisateur connecté est bien celui qui demande la suppression
      if ($this->getUser() !== $user) {
          throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce profil.');
      }
  
      // Si l'utilisateur a des commandes, on anonymise ses données
      if ($user->getOrders()->count() > 0) {
          $user->setUserEmail('utilisateur' . $user->getId() . '@supprime.fr');
          $user->setUserNickname('Utilisateur' . $user->getId());
          $user->setRoles(['ROLE_DELETE']);
          $user->setPassword('');
          $user->setReasonNickname(null);
      } else {
          // Sinon, on supprime l'utilisateur
          $entityManager->remove($user);
      }
  
      // Sauvegarde les modifications
      $entityManager->flush();
  
      // Déconnecte l'utilisateur
      $security->logout(false); // Déconnexion propre
  
      // Invalide la session
      $request = $requestStack->getCurrentRequest();
      if ($request) {
          $request->getSession()->invalidate();
      }
  
      // Redirige vers la page d'accueil
      return $this->redirectToRoute('home');
  }

    
    
}

       

       

       
