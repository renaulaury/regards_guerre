<?php

namespace App\Form\BackOffice;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ArtistAddEditBOType extends AbstractType
{
    /*************** Formulaire du template artistAddEditBO ***************/
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artistName', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom de l\'artiste.',
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le nom de l\'artiste ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Zéèçàùïöë -]+$/i',
                        'message' => 'Le nom de l\'artiste ne peut contenir que des lettres, des espaces et des tirets.',
                    ]),
                ],
            ])

            ->add('artistFirstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le prénom de l\'artiste.',
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le prénom de l\'artiste ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Zéèçàùïöë -]+$/i',
                        'message' => 'Le prénom de l\'artiste ne peut contenir que des lettres, des espaces et des tirets.',
                    ]),
                ],
            ])

            ->add('artistBirthDate', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La date de naissance est obligatoire.']),
                    new Type(['type' => \DateTimeInterface::class, 'message' => 'Veuillez entrer une date valide.']),               
                    new LessThanOrEqual([
                        'value' => new \DateTime('today'),
                        'message' => 'La date de naissance doit être antérieure à la date d\'aujourd\'hui.',
                    ]),
                ],
            ])  

            ->add('artistDeathDate', DateType::class, [
                'label' => 'Date de décès',
                'widget' => 'single_text',
                'required' => false,
                'constraints' => [
                        new Type(['type' => \DateTimeInterface::class, 'message' => 'Veuillez entrer une date valide.']),
                        new LessThanOrEqual([
                            'value' => new \DateTime('today'),
                            'message' => 'La date de décès doit être antérieure ou égale à la date d\'aujourd\'hui.',
                        ]),
                        new GreaterThan([
                            'propertyPath' => 'parent.children[birthDate].data',
                            'message' => 'La date de décès doit être postérieure à la date de naissance.',
                        ]),
                    ],
                ])

            ->add('artistJob', TextType::class, [
                'label' => 'Métier(s)',
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le ou les métiers de l\'artiste ne peuvent pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            ->add('artistBio', TextareaType::class, [
                'label' => 'Biographie',
                'constraints' => [
                        new NotBlank([
                            'message' => 'La biographie de l\'artiste est obligatoire.',
                        ]),
                        new Length([
                            'min' => 100, 
                            'minMessage' => 'La biographie doit contenir au moins {{ limit }} caractères.',
                            'max' => 400, 
                            'maxMessage' => 'La biographie ne peut pas dépasser {{ limit }} caractères.',
                        ]),
                    ],
                ]);
            }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
