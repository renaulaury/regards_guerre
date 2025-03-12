<?php
namespace App\Form\BackOffice;

use App\Entity\Room;
use App\Entity\Show;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ShowAddInfosBO extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'titleRoom', 
                'label' => 'Salle',
                'placeholder' => '',
                'required' => true,
            ])
            ->add('artistPhoto', TextType::class, [
                'label' => 'Photo de l\'artiste',
                'required' => true,
                // 'mapped' => false, 
            ])
            ->add('artistPhotoAlt', TextType::class, [
                'label' => 'Description de la photo',
                'required' => true,
            ])
            ->add('artistTextArt', TextareaType::class, [
                'label' => 'Parcours artistique',
                'required' => true,
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