<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserEditIdentityFormType extends AbstractType
{
    /* User Controller */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'trim' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50, 
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Zéèçàùïöë -]+$/i', // Autorise lettres, espaces, tirets
                        'message' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
                    ]),
                ],
            ])

            ->add('userFirstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'trim' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50, 
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Zéèçàùïöë -]+$/i', // Autorise lettres, espaces, tirets
                        'message' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, 
            'csrf_protection' => true,
            'csrf_field_name' => 'tokenCSRF',
            'csrf_token_id'   => 'task_item'
        ]);
    }
}