<?php
namespace App\Form\BackOffice;

use App\Entity\Room;
use App\Entity\Show;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ShowAddInfosBO extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usedRoomIds = []; // Init à un tabl par défaut
        if (isset($options['usedRoom'])) { //Vérif que l'option != null
            $usedRoomIds = $options['usedRoom']; // Assignation si l'option existe
        }


        /****** Formulaire du template  exhibitShowBO ******/
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'titleRoom', 
                'label' => 'Salle',
                'placeholder' => '',
                'required' => true,
                // 'choice_filter' est une option du champ EntityType qui permet de filtrer les choix dispo
                //cette option sera appelée pour chaque salle
                // room = classe - usedRoomIds = tabl des ID des salles déjà utilisés
                'choice_filter' => function ($room) use ($usedRoomIds) {
                if (!$room instanceof Room) { //Vérif que room est bien une instance de Room
                    return false; 
                }
                return !in_array($room->getId(), $usedRoomIds); // Exclure si l'ID de la salle est dans le tableau
                },
            ])
            ->add('artistPhoto', FileType::class, [
                'label' => 'Photo de l\'artiste',
                'required' => true,
                'mapped' => false, 
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
            'usedRoom' => [],
        ]);
    }
}