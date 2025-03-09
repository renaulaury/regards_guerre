<?php

namespace App\Form\BackOffice;

use App\Entity\Exhibition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExhibitAddEditBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mainImage', FileType::class, [
                'label' => 'Image principale',
                'mapped' => false, // Ce champ n'est pas directement lié à l'entité
                'required' => true,
            ])
            ->add('mainImageAlt', TextType::class, [
                'label' => 'Courte description de l\'image principale',
            ])
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
                'data' => new \DateTime('09:00')
            ])
            ->add('hourEnd', TimeType::class, [
                'label' => 'Heure de fin',
                'widget' => 'single_text',
                'data' => new \DateTime('16:00')
            ])
            ->add('stockMax', IntegerType::class, [
                'label' => 'Stock maximum',
                'data' => 150,
            ])
            ->add('stockAlert', IntegerType::class, [
                'label' => 'Stock d\'alerte',
                'data' => 10,
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
