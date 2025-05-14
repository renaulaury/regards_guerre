<?php

namespace App\Form\BackOffice;

use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Entity\TicketPricing;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ExhibitAddEditBOType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*************** Formulaire du template exhibitAddEditBO ***************/
        $builder
            ->add('mainImage', FileType::class, [
                'label' => 'Image principale',
                'mapped' => false, 
                'constraints' => [
                        new Image([ //Gestion taille, format et erreur de téléversement
                            'maxSize' => '2G', 
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                            'mimeTypesMessage' => 'Veuillez télécharger une image valide au format JPEG, PNG ou WEBP et de moins de 2Go.',
                        ]),
                    ],
                ])

            ->add('mainImageAlt', TextType::class, [
                'label' => 'Courte description de l\'image',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'La description de l\'image ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            ->add('titleExhibit', TextType::class, [
                'label' => 'Titre de l\'exposition',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le titre de l\'exposition ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            ->add('subtitleExhibit', TextType::class, [
                'label' => 'Sous-titre de l\'exposition',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le sous-titre de l\'exposition ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            ->add('hookExhibit', TextType::class, [
                'label' => 'Accroche de l\'exposition',
                'required' => true,           
            ])

            ->add('descriptionExhibit', TextareaType::class, [
                'label' => 'Description de l\'exposition',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description de l\'exposition est obligatoire.',
                    ]),
                    new Length([
                        'min' => 100,
                        'minMessage' => 'La description de l\'exposition doit contenir au moins {{ limit }} caractères.',
                        'max' => 230,
                        'maxMessage' => 'La description de l\'exposition ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            ->add('dateWarBegin', DateType::class, [
                'label' => 'Début de la guerre',
                'widget' => 'single_text',     
                'required' => true,           
            ])

            ->add('dateWarEnd', DateType::class, [
                'label' => 'Fin de la guerre',
                'widget' => 'single_text',                
                'required' => false, 
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
                'data' => 150, //200m2
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 150,
                        'message' => 'Le nombre maximum de tickets ne peut pas dépasser {{ compared_value }}.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Positive([
                        'message' => 'Le stock maximum doit être un nombre positif.',
                    ]),
                ],
            ])

            ->add('stockAlert', IntegerType::class, [
                'label' => 'Stock d\'alerte',
                'data' => 10,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\PositiveOrZero([
                        'message' => 'Le stock d\'alerte doit être un nombre positif ou zéro.',
                    ]),
                ],
            ])
            
            ->add('ticketPricings', CollectionType::class, [ //Pour edit
                'entry_type' => TicketPricingType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
            ])
        ;

        //Pour add
        // Init tarifs par défaut pour une nouvelle expo
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) { //FormEvent = évent de form permet de choisir des données - PRE_SET_DATA = prépare les données avt de les afficher
            // Récupère l'entité Exhibition à partir des données du form ci dessus
            $exhibition = $event->getData();

            if ($exhibition->getId() === null) { //Vérif si l'expo est nouvelle
                // Récup tous les tickets
                $tickets = $this->entityManager->getRepository(Ticket::class)->findAll(); 

                foreach ($tickets as $ticket) {
                    $ticketPricing = new TicketPricing();
                    $ticketPricing->setTicket($ticket);

                    // Définir le tarif par défaut en fonction du type de ticket
                    $ticketTitle = trim($ticket->getTitleTicket()); 

                    switch ($ticketTitle) {
                        case 'Adulte':
                            $ticketPricing->setStandardPrice('10.00'); 
                            break;
                        case 'Enfant':
                            $ticketPricing->setStandardPrice('8.00'); 
                            break;
                        case 'Enfant -6ans':
                            $ticketPricing->setStandardPrice('0.00'); 
                            break;
                        default:
                            $ticketPricing->setStandardPrice('10.00'); // Tarif par défaut si non reconnu
                            break;
                    } 

                    $exhibition->addTicketPricing($ticketPricing);
                }

                $event->setData($exhibition); //Maj de l'expo
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exhibition::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'tokenCSRF',
            'csrf_token_id'   => 'task_item'
        ]);
    }
}
