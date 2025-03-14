<?php

namespace App\Form\Security;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType 
{
    /*************** Formulaire du template security/register ***************/

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userName', TextType::class, [
                'label' => 'Nom <sup>1</sup>',  
                'label_html' => true, // Active l'interprétation HTML
                'attr' => [
                        'placeholder' => 'Votre nom',
                    ],
                'required' => false,
                'trim' => true, //Enleve les espaces
            ])            
            ->add('userFirstname', TextType::class, [
                'label' => 'Prénom <sup>1</sup>',  
                'label_html' => true, // Active l'interprétation HTML
                'attr' => [
                        'placeholder' => 'votre prénom',
                    ],
                'required' => false, 
                'trim' => true, //Enleve les espaces
            ])


            ->add('userNickname', TextType::class, [
                'label' => 'Pseudo',  
                'attr' => [
                        'placeholder' => 'Votre pseudonyme',
                    ],
                'trim' => true, //Enleve les espaces
            ])
            ->add('userEmail', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                        'placeholder' => 'Votre email',
                    ],
                'trim' => true, //Enleve les espaces  
            ])


            ->add('password', RepeatedType::class, [
                'mapped' => false, //cela signifie que ce champ ne sera pas directement associé à une propriété de l'entité User
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                'options' => [
                    'label' => false,
                    'attr' => ['class' => '']
                ],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe <sup>2</sup>',
                                     'label_html' => true, 
                                     'attr' => [
                                            'placeholder' => 'Votre mot de passe',
                                        ],
                'constraints' => [ // Ajout des contraintes ici
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                    new Regex([ // Ajout de la contrainte Regex
                        'pattern' => '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/', //1 maj - 1 chiffre - 1 caractère spécial/word - 12 min
                        'message' => 'Le mot de passe doit contenir 12 caractères et au moins une majuscule, un chiffre et un caractère spécial.',
                    ]),
                ],
            ],
                'second_options' => [
                    'label' => 'Répétez le mot de passe <sup>2</sup>',
                    'label_html' => true,
                    'attr' => [
                        'placeholder' => 'Répéter votre mot de passe',
                    ],
                ],
            ])
            
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez être d\'accord avec notre politique de confidentialité.',
                    ]),
                ],
            ])
        ;
    }
    //Permettrait d'ajouter des options par défaut comme une entité associée
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

