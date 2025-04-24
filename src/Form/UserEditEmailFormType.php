<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserEditEmailFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userEmail', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'trim' => true,                
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Email([
                        'message' => 'Veuillez entrer une adresse email valide.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 180, 
                        'maxMessage' => 'L\'adresse email ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Callback([$this, 'validateUniqueEmail']),//Evite les doublons
                ],
            ])           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
        $resolver->setDefined(['entityManager']); //Autorisation spéciale de entityManager
    }

    public function validateUniqueEmail($value, ExecutionContextInterface $context)
    {
        if (empty($value)) {
            return;
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['userEmail' => $value]);

        if ($user && $user !== $context->getObject()) {
            $context->buildViolation('Cette adresse email est déjà utilisée.')
                ->atPath('userEmail')
                ->addViolation();
        }
    }
}