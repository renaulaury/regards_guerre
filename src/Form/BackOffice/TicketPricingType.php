<?php

namespace App\Form\BackOffice;

use App\Entity\Ticket;
use App\Entity\TicketPricing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TicketPricingType extends AbstractType
{
    // Utilisé pour imbriquer les détails des tickets dans ExhibitAddEditBOType

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('standardPrice', MoneyType::class, [
                'label' => 'Prix',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketPricing::class,
        ]);
    }
}
