<?php

namespace App\Form;

use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class AddRatingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('notation', NumberType::class, [
                'label' => 'Noter cet événement (1 à 5 étoiles)',
                'attr' => [
                    'class' => 'form-control bg-dark text-light',
                ],
                'constraints' => [
                    new Range(min: 1, max: 5),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn bg-yellow-ripple mt-2',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}
