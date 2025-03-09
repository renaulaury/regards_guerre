<?php

namespace App\Form\BackOffice;

use App\Entity\Exhibition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExhibitAddEditBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleExhibit', TextType::class, [
                'label' => 'Titre de l\'exposition',
            ])
            ->add('subtitleExhibit', TextType::class, [
                'label' => 'Sous-titre de l\'exposition',
            ])
            ->add('hookExhibit', TextType::class, [
                'label' => 'Accroche de l\'exposition',
            ])
            ->add('descriptionExhibit', TextareaType::class, [
                'label' => 'Description de l\'exposition',
            ])
            ->add('dateExhibit', DateType::class, [
                'label' => 'Date de l\'exposition',
                'widget' => 'single_text',
            ])
            ->add('hourBegin', TimeType::class, [
                'label' => 'Heure de début',
                'widget' => 'single_text',
            ])
            ->add('hourEnd', TimeType::class, [
                'label' => 'Heure de fin',
                'widget' => 'single_text',
            ])
            ->add('stockMax', IntegerType::class, [
                'label' => 'Stock maximum',
            ])
            ->add('stockAlert', IntegerType::class, [
                'label' => 'Stock d\'alerte',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exhibition::class,
        ]);
    }
}
