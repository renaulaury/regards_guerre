<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {      
        // Root -> changement de rôle d'un membre
        if ($options['root']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
        }

        // Admin -> raison du changement et modification du pseudo
        if ($options['admin']) {
            $builder
                ->add('userNickname', TextType::class, [
                    'label' => 'Pseudo',
                    'required' => true,
                ])
                ->add('reasonNickname', TextareaType::class, [
                    'label' => 'Raison du changement',
                    'required' => true,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'root' => false,  // Ajout de l'option pour définir si c'est un ROOT
            'admin' => false, // Ajout de l'option pour définir si c'est un ADMIN
        ]);
    }

}
