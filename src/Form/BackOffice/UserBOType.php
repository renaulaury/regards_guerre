<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {      
        /*************** Formulaire du template userEditBO ***************/

        // Root -> changement de rôle d'un membre
        if ($options['root']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'expanded' => false,
                'multiple' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'root' => false,  // Option ROOT
        ]);
    }

}
