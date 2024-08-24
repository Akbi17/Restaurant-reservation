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

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg custom-form-control',
                    'placeholder' => 'NAME',
                ],
                ]
            )
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
            ]
        );
    }
}
