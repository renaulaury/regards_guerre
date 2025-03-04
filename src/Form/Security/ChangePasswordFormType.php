<?php

namespace App\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Mdp actuel -> Modification de mdp
        if (!$options['reset']) {
            $builder->add('oldPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe actuel.',
                    ]),
                ],
            ]);
        }

        //Reset du mdp -> suite de la modif du mdp ou Mdp oublié
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe.',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Votre mot de passe doit avoir {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([ // Ajout de la contrainte Regex
                            'pattern' => '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/', //1 maj - 1 chiffre - 1 caractère spécial/word - 12 min
                            'message' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.',
                        ]),
                        // new PasswordStrength(), // Vérifie la complexité du mot de passe (longueur, caractères).
                        // new NotCompromisedPassword(), // Vérifie si le mot de passe a été compromis en ligne.
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Répéter le nouveau mot de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                // Au lieu d'être placé directement sur l'objet, il est lu et encodé dans le contrôleur,
                // il est lu et encodé dans le contrôleur
                'mapped' => false,
            ])
        ;
    }
    
    //Permettrait d'ajouter des options par défaut comme une entité associée
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'reset' => false,
        ]);
    }
}
