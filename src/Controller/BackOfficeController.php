<?php

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BackOfficeController extends AbstractController
{
    #[Route('/back_office', name: 'back_office')]
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig', [
        ]);
    }

    #[Route('/back_office/roles', name: 'roles')]
    public function rolesBackOffice(UserRepository $userRepo): Response
    {
        $user = $this->getUser(); //User co
        $members = $userRepo->findMembersByEmail(); //Membres de l'assoc
        $users = $userRepo->findAll(); //All users

        return $this->render('back_office/roles.html.twig', [
            'user' => $user,
            'members' => $members,            
            'users' => $users,
        ]);
    }
}
