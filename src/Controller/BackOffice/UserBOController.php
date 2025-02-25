<?php

namespace App\Controller\BackOffice;


use App\Entity\User;
use App\Form\UserBOType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BackOffice\UserBORepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserBOController extends AbstractController
{
   

    /******************** Affiche les membres ***********************/
    #[Route('/backOffice/userListBO', name: 'userList')]
    public function rolesBackOffice(UserBORepository $userBORepo): Response
    {
        $user = $this->getUser(); //User co
        $members = $userBORepo->findMembersByEmail(); //Membres de l'assoc
        $users = $userBORepo->findUsersByRole(); //Classement users par roles
        
        return $this->render('backOffice/user/userListBO.html.twig', [
            'user' => $user,
            'members' => $members,            
            'users' => $users,
        ]);
    }

/************** Modifier le profil d'un user  *********************/
    #[Route('/backOffice/user/userEditBO/{id}', name: 'userEditBO')]
    public function editUserBO(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérification des rôles de l'utilisateur connecté
        $root = $this->isGranted('ROLE_ROOT');
        $admin = $this->isGranted('ROLE_ADMIN');

        // Création du formulaire avec les options pour conditionner les champs affichés
        $form = $this->createForm(UserBOType::class, $user, [
            'root' => $root,
            'admin' => $admin,
        ]);

         // Vider les champs du formulaire avant de les afficher
        $form->get('userNickname')->setData('');  
        $form->get('reasonNickname')->setData(''); 

        // Traite la requête HTTP et hydrate le formulaire avec les données soumises
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Admin -> Gestion du changement de pseudo 
            if ($admin && $request->request->get('submitNickname')) {

                $newNickname = $form->get('userNickname')->getData();

                // Vérif différence des 2 pseudos
                if ($user->getUserNickname() !== $newNickname) {
                    // Stockage de la raison du changement 
                    $reasonNickname = $form->get('reasonNickname')->getData();

                    // Mettre à jour le pseudo
                    $user->setUserNickname($newNickname);
                    // Stockage de la raison du changement en bdd
                    $user->setReasonNickname($reasonNickname);
                } 
            }

            // Root -> Gestion du changement de rôle 
            if ($root && $request->request->get('submitRoles')) {
                $user->setRoles([$form->get('roles')->getData()]);
            }

            
            $entityManager->persist($user);//Marquage de l'entité pour qu'elle soit sauvegardée
            $entityManager->flush(); //Exécute les opérations de maj

            return $this->redirectToRoute('userListBO');
        }

        return $this->render('backOffice/user/userEditBO.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'root' => $root,
            'admin' => $admin,
        ]);
    }

/******************** Suppression d'un user ***********************/
    #[Route('/backOffice/user/userDeleteBO/{id}', name: 'userDeleteBO')]
    public function userDeleteBO(User $user): Response
    {
        
        return $this->render('backOffice/user/userDeleteBO.html.twig', [
            'user' => $user,
        ]);
    }

/******************** Confirmation suppression d'un user ***********************/
    #[Route('/backOffice/user/userDeleteBO/{id}', name: 'userDeleteBO')]
    public function userConfirmDeleteBO(User $user, EntityManagerInterface $entityManager): Response
    {    
        // Anonymisation du profil en utilisant le numéro d'id
        $anonymizedEmail = 'utilisateur' . $user->getId() . '@supprime.fr';
        $anonymizedNickname = 'Utilisateur' . $user->getId();

        $user->setUserEmail($anonymizedEmail);
        $user->setUserNickname($anonymizedNickname);

        // Vous pouvez aussi vider d'autres champs personnels si nécessaire
        $user->setRoles([]);
        $user->setPassword('');
        $user->setReasonNickname(null);

        // Enregistrement des modifications
        $entityManager->flush();

        // Redirection après suppression
        return $this->redirectToRoute('userList');
    }


}
