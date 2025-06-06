<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Security\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    /************* S'enregistrer ************************/
    #[Route('/security/register', name: 'register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        Security $security, 
        EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrement mot de passe
            $password = $form->get('password')->getData();

            $regex = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/'; //1 maj - 1 chiffre - 1 caractère spécial/word - 12 min

            if (!preg_match($regex, $password)) {
                // Ajouter un message d'erreur si le mot de passe ne correspond pas à la regex
                $this->addFlash('error', 'ERREUR : Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.');

                // Re-render le formulaire avec un message d'erreur
                return $this->render('security/register.html.twig', [
                    'registrationForm' => $form,
                ]);
            }

            // Empreinte numérique du mot de passe security.yaml : 
            // Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto' (auto = default)
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));

            // Attribuer le rôle par défaut
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            //Générer le slug
            $slug = $user->createSlugUser();

            // Définir le slug sur l'entité
            $user->setSlug($slug);

            $entityManager->persist($user);
            $entityManager->flush();

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
