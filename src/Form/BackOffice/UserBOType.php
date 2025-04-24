<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {      
        /*************** Formulaire du template userEditBO ***************/

        // Root -> changement de rôle d'un membre
        if ($options['root']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'expanded' => false,
                'multiple' => true,
            ]);
        }

        // Admin -> raison du changement et modification du pseudo
        if ($options['admin']) {
            $builder
                ->add('userNickname', TextType::class, [
                    'label' => 'Pseudo',
                    'required' => true,
                    'trim' => true, 
                    'constraints' => [
                        new Length([
                            'min' => 2,
                            'max' => 50, 
                            'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                            'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.',
                        ]),
                        new Regex([
                            'pattern' => '/^[a-zA-Z0-9_-]+$/',
                            'message' => 'Le pseudo ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
                        ]),
                    ],
                ])

                ->add('reasonNickname', TextareaType::class, [
                    'label' => 'Raison du changement',
                    'required' => true,
                    'constraints' => [
                        new Length([
                            'max' => 500, 
                            'maxMessage' => 'La raison du changement ne peut pas dépasser {{ limit }} caractères.',
                        ]),
                    ],
                ]);
            }
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'root' => false,  // Option ROOT
            'admin' => false, // Option ADMIN
        ]);
    }

}
