<?php

namespace App\Controller\BackOffice;


use App\Entity\User;
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
    #[Route('/backOffice/userEdit{id}', name: 'userEdit')]
    public function editUser(Request $request, User $user): Response
    {
        
        

        return $this->render('backOffice/user/userEdit.html.twig', [
           
        ]);
    }

}
