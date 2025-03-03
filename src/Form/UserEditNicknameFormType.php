<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserEditNicknameFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userNickname', TextType::class, [
                'label' => 'Pseudo',
                'required' => false,
                'trim' => true,
                //Eviter les doublons
                'constraints' => [
                    new Callback([$this, 'validateUniqueEmail']),
                ],
            ])           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
        $resolver->setDefined(['entityManager']); //Autorisation spéciale de entityManager
    }

    public function validateUniqueNickname($value, ExecutionContextInterface $context)
    {
        if (empty($value)) {
            return;
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['userNickname' => $value]);

        if ($user && $user !== $context->getObject()) {
            $context->buildViolation('Ce pseudo est déjà utilisé.')
                ->atPath('userNickname')
                ->addViolation();
        }
    }
}