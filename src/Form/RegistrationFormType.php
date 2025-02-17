<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\EqualTo;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userNickname', TextType::class, [
                'label' => 'Pseudo'  
            ])
            ->add('userEmail', EmailType::class, [
                'label' => 'Email'  
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez être d\'accord avec notre politique de confidentialité.',
                    ]),
                ],
            ])

            ->add('password', PasswordType::class, [
                //cela signifie que ce champ ne sera pas directement associé à une propriété de l'entité User
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([ // Si le champ est laissé vide, un message d'erreur sera affiché.
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',                     
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Mot de passe'  
            ])
        // Le champ de confirmation du mot de passe

        
        // Ajout du champ de confirmation du mot de passe -> ne fonctionne pas proposition de validateur personnalisé
        // ->add('passwordConfirm', PasswordType::class, [
        //     'mapped' => false,
        //     'attr' => ['autocomplete' => 'new-password'],
        //     'constraints' => [
        //         new NotBlank([
        //             'message' => 'Veuillez confirmer votre mot de passe.',
        //         ]),
        //         new EqualTo([
        //             'value' => $builder->getData()->getPassword(),
        //             'message' => 'Les mots de passe doivent être identiques.',
        //         ]),
        //     ],
        //     'label' => 'Confirmer le mot de passe'
        // ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
