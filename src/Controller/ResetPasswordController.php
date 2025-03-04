<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Security\ChangePasswordFormType;
use App\Form\Security\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    // Injection des dépendances pour gérer la réinitialisation du mot de passe et l'entité
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Afficher et traiter le formulaire de demande de réinitialisation du mot de passe.
     */
    #[Route('', name: 'forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        // Création du formulaire de demande de réinitialisation
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // Récupère l'email de l'utilisateur à partir du formulaire
            /** @var string $email */
            $email = $form->get('userEmail')->getData();

            // Traite l'envoi du mail pour réinitialiser le mot de passe
            return $this->processSendingPasswordResetEmail($email, $mailer, $translator
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    /******************* Page de confirmation après qu'un utilisateur a demandé la réinitialisation de son mot de passe. ************************/
     
    #[Route('/check-email', name: 'check_email')]
    public function checkEmail(): Response
    {
        // Générer un faux jeton si l'utilisateur n'existe pas ou si quelqu'un a accédé à cette page directement.
        // Cela évite de révéler si un utilisateur a été trouvé avec l'adresse e-mail donnée ou non.
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        // Affiche la page de confirmation avec le jeton
        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /******************* Valide et traite l'URL de réinitialisation sur laquelle l'utilisateur a cliqué dans son courrier électronique. .************************/

    #[Route('/reset/{token}', name: 'reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, ?string $token = null): Response
    {
        if ($token) {
             // Nous stockons le jeton dans la session et le supprimons de l'URL, afin d'éviter que l'URL ne soit
            // chargée dans un navigateur et que le jeton ne soit pas divulgué à un JavaScript tiers.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('reset_password');
        }

        // Récupère le jeton depuis la session
        $token = $this->getTokenFromSession();

        // Si aucun jeton n'est trouvé, renvoie une erreur
        if (null === $token) {
            throw $this->createNotFoundException('Aucun jeton de réinitialisation du mot de passe n\'a été trouvé dans l\'URL ou dans la session.');
        }

        try { // Valide le jeton et récupère l'utilisateur associé
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);

        } catch (ResetPasswordExceptionInterface $e) { // Si le jeton est invalide, on affiche un message d'erreur
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('forgot_password_request');
        }

        // Le jeton est valide, autorisation à l'utilisateur à modifier son mot de passe
        $form = $this->createForm(ChangePasswordFormType::class, null, ['reset' => true]); //Rajout de reset pour gérer mdp oublié ou modif mdp dans ChangePasswordFormType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Un jeton de réinitialisation de mot de passe ne doit être utilisé qu'une seule fois
            $this->resetPasswordHelper->removeResetRequest($token);

            /** @var string $password */
            $password = $form->get('password')->getData();

            // Empreinte numérique du mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $this->entityManager->flush();

            // La session est nettoyée après la modification du mot de passe
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form,
        ]);
    }


    /************************* Méthode pour envoyer un email de réinitialisation de mot de passe ***************************/
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'userEmail' => $emailFormData,
        ]);

        
        // Ne pas indiquer si un compte d'utilisateur a été trouvé ou non
        if (!$user) {
            return $this->redirectToRoute('check_email');
        }
        
        try { // Génère le jeton de réinitialisation pour l'utilisateur
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            
        } catch (ResetPasswordExceptionInterface $e) { 
            // Si vous voulez dire à l'utilisateur pourquoi un email de réinitialisation n'a pas été envoyé -> non
            
            
            return $this->redirectToRoute('check_email');
        }
        
        // Prépare l'email pour la réinitialisation
        $email = (new TemplatedEmail())
        ->from(new Address('regardsguerre@gmail.com', 'Regards de Guerre'))
        ->to((string) $user->getUserEmail())
        ->subject('Votre demande de réinitialisation du mot de passe')
        ->htmlTemplate('reset_password/email.html.twig')
        ->context([
            'resetToken' => $resetToken,
            ])
            ;
        
        // Envoie l'email
        $mailer->send($email);

        // Stocker l'objet « token » dans la session pour le récupérer dans la route « check-email »
        $this->setTokenObjectInSession($resetToken);

        // Redirige vers la page de confirmation
        return $this->redirectToRoute('check_email');
    }
}


