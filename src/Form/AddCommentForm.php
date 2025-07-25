<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', null, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'class' => 'form-control bg-dark text-light'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter un commentaire',
                'attr' => [
                    'class' => 'btn btn-warning mt-2',
                ]
            ]);
//            ->add('notation', EntityType::class, [
//                'class' => Rating::class,
//                'choice_label' => 'notation',
//                'attr' => [
//                    'class' => 'form-control',
//                ],
//                'constraints' => [
//                    new Range(min: 0, max: 5),
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
