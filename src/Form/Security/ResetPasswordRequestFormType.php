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
        /**************** Formulaire du template resetPassword/reset *****************/
        $builder
            ->add('userEmail', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'autocomplete' => 'email',
                    'placeholder' => 'Votre email', // Placeholder défini ici
                ],
                'trim' => true, //Supprime les blancs
                'constraints' => [
                    new NotBlank([ //Vérifie que le champ n'est pas vide
                        'message' => 'Veuillez entrer votre email afin de réinitialiser votre mot de passe.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Email([
                        'message' => 'Veuillez entrer une adresse email valide.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 180, 
                        'maxMessage' => 'L\'adresse email ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
        ;
    }

    //Permettrait d'ajouter des options par défaut comme une entité associée
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => 'tokenCSRF',
            'csrf_token_id'   => 'task_item'
        ]);
    }
}
