<?php

namespace App\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userEmail', EmailType::class, [
                'label' => 'Email',
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank([ //Vérifie que le champ n'est pas vide
                        'message' => 'Veuillez entrer votre email afin de réinitialiser votre mot de passe.',
                    ]),
                ],
            ])
        ;
    }

    //Permettrait d'ajouter des options par défaut comme une entité associée
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
