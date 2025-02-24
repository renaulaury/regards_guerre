<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserBOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userEmail', EmailType::class, [
                'label' => 'Pseudo',
            ])
            ->add('roles', TextType::class, [
                'label' => 'Pseudo',
            ])
            ->add('password', TextType::class, [ //A modifier
                'label' => 'Pseudo',
            ])
            ->add('userNickname', TextType::class, [
                'label' => 'Pseudo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
