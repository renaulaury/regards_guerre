<?php
namespace App\Form;

use App\Entity\Show;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artistPhoto', TextType::class, [
                'label' => 'Photo de l\'artiste',
                'mapped' => false, 
            ])
            ->add('artistPhotoAlt', TextType::class, [
                'label' => 'Description de la photo',
            ])
            ->add('artistTextArt', TextareaType::class, [
                'label' => 'Parcours artistique',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Show::class,
        ]);
    }
}