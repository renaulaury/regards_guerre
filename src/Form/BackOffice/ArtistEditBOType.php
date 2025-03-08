<?php

namespace App\Form\BackOffice;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArtistEditBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artistName', TextType::class, [
                'label' => 'Nom',
            ])
->add('artistFirstname', TextType::class, [
            'label' => 'Prénom',
            ])
            ->add('artistBirthDate', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
            ->add('artistDeathDate', DateType::class, [
                'label' => 'Date de décès',
                'widget' => 'single_text',
                'required' => false,
            ])
->add('save', SubmitType::class, [
            'label' => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'entityManager' => null,
        ]);
    }
}
