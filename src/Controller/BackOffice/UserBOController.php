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
    #[Route('/backOffice/userList', name: 'userList')]
    public function rolesBackOffice(UserBORepository $userBORepo): Response
    {
        $user = $this->getUser(); //User co
        $members = $userBORepo->findMembersByEmail(); //Membres de l'assoc
        $users = $userBORepo->findUsersByRole(); //Classement users par roles
        
        return $this->render('backOffice/user/userList.html.twig', [
            'user' => $user,
            'members' => $members,            
            'users' => $users,
        ]);
    }

    /************** Modifier le profil d'un user  *********************/
    #[Route('/backOffice/user/userEdit/{id}', name: 'userEdit')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserBOType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('submitNickname')) {
                $user->setUserNickname($form->get('userNickname')->getData());
            }

            if ($request->request->get('submitRoles')) {
                $user->setRoles($form->get('roles')->getData());
            }

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('userList');
        }

        return $this->render('backOffice/user/userEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
