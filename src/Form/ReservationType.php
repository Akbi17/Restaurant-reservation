<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $options['current_user'];

        $builder
        
            ->add(
                'name', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg custom-form-control',
                    'placeholder' => 'NAME',
                ],
                ]
            )
            ->add('email', HiddenType::class, [
                'mapped' => false, // Not mapped to the entity
                'data' => $currentUser ? $currentUser->getEmail() : '', // Set default value
                'constraints' => [
                    
                    new Assert\Email(),
                ],
                'attr' => [
                    'readonly' => true, // Make the field read-only
                ],
            ])
            ->add(
                'guestscount', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg custom-form-control',
                    'placeholder' => 'NUMBER OF GUESTS',
                    'max' => 20,
                    'min' => 0,
                ],
                ]
            )
            ->add(
                'clock', TimeType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg custom-form-control',
                    'placeholder' => 'TIME',
                ],
                'widget' => 'single_text',
                ]
            )
            ->add(
                'date', DateType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg custom-form-control',
                    'placeholder' => 'DATE',
                ],
                'widget' => 'single_text',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => Reservation::class,
            'current_user' => null,
            ]
        );
    }
}
