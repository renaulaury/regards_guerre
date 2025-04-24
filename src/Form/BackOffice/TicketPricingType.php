<?php

namespace App\Form\BackOffice;


use App\Entity\TicketPricing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class TicketPricingType extends AbstractType
{
    // Utilisé pour imbriquer les détails des tickets dans ExhibitAddEditBOType

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('standardPrice', MoneyType::class, [
                'label' => 'Prix',
                'constraints' => [
                        new \Symfony\Component\Validator\Constraints\PositiveOrZero([
                            'message' => 'Le prix ne peut pas être négatif.',
                        ]),
                    ],
                ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketPricing::class,
        ]);
    }
}


