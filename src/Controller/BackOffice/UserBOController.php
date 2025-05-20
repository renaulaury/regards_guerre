<?php

namespace App\Controller\BackOffice;


use App\Entity\User;
use App\Form\UserBOType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BackOffice\UserBORepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserBOController extends AbstractController
{
   

    /******************** Affiche les membres ***********************/
    #[Route('/backOffice/userListBO/{filter?}', name: 'userListBO')]
    public function rolesBackOffice(
        UserBORepository $userBORepo,
        ?string $filter): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        $allUsers = $userBORepo->findAll(); // Récupère tous les utilisateurs
        $adminsList = [];
        $usersList = [];

        
        foreach ($allUsers as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_ROOT', $user->getRoles()) && !in_array('ROLE_DELETE', $user->getRoles())) {
                $adminsList[] = $user;
            } elseif (in_array('ROLE_USER', $user->getRoles()) && !in_array('ROLE_ROOT', $user->getRoles()) && !in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_DELETE', $user->getRoles())) {
                $usersList[] = $user;
            }
        }
    
        // Filtre les utilisateurs selon le filtre
        $usersToDisplay = match ($filter) {
            'administrators' => $adminsList,
            'users' => $usersList,
            default => $allUsers,
        };

        $user = $this->getUser(); //User co
        $members = $userBORepo->findMembersByEmail(); //Membres de l'assoc
        $users = $userBORepo->findUsersByRole(); //Classement users par roles
        

        return $this->render('backOffice/user/userListBO.html.twig', [
            'user' => $user,
            'members' => $members,            
            'users' => $users, //Liste filtrée
            'currentFilter' => $filter,
        ]);
    }

/************** Modifier le profil d'un user  *********************/
    #[Route('/backOffice/user/userEditBO/{slug}', name: 'userEditBO')]
    public function userEditBO(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user = null, 
        Request $request, 
        EntityManagerInterface $entityManager): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        // Vérification des rôles de l'utilisateur connecté
        $root = $this->isGranted('ROLE_ROOT');
        $admin = $this->isGranted('ROLE_ADMIN');

        // Création du formulaire avec les options pour conditionner les champs affichés
        $form = $this->createForm(UserBOType::class, $user, [
            'root' => $root,
        ]);

        // Traite la requête HTTP et hydrate le formulaire avec les données soumises
        $form->handleRequest($request);
        

        

        if ($form->isSubmitted() && $form->isValid()) {           
            // $data = $form->getData();
            // $role = $data['roles'];

            // Root -> Gestion du changement de rôle 
            if ($root && $request->request->get('submitRoles')) {
                $user->setRoles($form->get('roles')->getData());

            }

            
            $entityManager->persist($user);//Marquage de l'entité pour qu'elle soit sauvegardée
            $entityManager->flush(); //Exécute les opérations de maj

            return $this->redirectToRoute('userListBO');
        }

        return $this->render('backOffice/user/userEditBO.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'root' => $root,
        ]);
    }

/******************** Suppression d'un user ***********************/
    //Envoi vers la confirm
    #[Route('/backOffice/user/userDeleteBO/{slug}', name: 'userDeleteBO')]
    public function userDeleteBO(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user = null, ): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        return $this->render('backOffice/user/userDeleteBO.html.twig', [
            'user' => $user,
        ]);
    }

    //Confirm définitive
    #[Route('/backOffice/user/userConfirmDeleteBO/{slug}', name: 'userConfirmDeleteBO')]
    public function userConfirmDeleteBO(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user = null, 
        EntityManagerInterface $entityManager): Response
    {    
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        // Si le user a des commandes on garde nom+prenom uniquement
        if ($user->getOrders()->count() > 0) {
            // L'utilisateur a des commandes, anonymisation
            $anonymizedEmail = 'utilisateur' . $user->getId() . '@supprime.fr';

            $user->setUserEmail($anonymizedEmail);

            // Vider les autres champs personnels
            $user->setRoles(['ROLE_DELETE']);
            $user->setPassword('');

            // Enregistrement des modifications
            $entityManager->flush();

        } else {
            // L'utilisateur n'a pas de commandes, suppression
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // Redirection après suppression
        return $this->redirectToRoute('userListBO');
    }


}
