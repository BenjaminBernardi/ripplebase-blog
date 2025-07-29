<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Publication;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPublicationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de la publication',
                'attr' => [
                    'class' => 'form-control bg-dark text-light my-2',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Article',
                'attr' => [
                    'class' => 'form-control textarea-form bg-dark text-light my-2',
                    'rows' => '12'
                ]
            ])
            ->add('imagePath', null, [
                'label' => 'Lien de l\'image',
                'attr' => [
                    'class' => 'form-control bg-dark text-light my-2',
                ]
            ])
            ->add('releasedAt', DateTimeType::class, [
                'label' => 'Date de publication',
                'attr' => [
                    'class' => 'form-control bg-dark text-light my-2',
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'tag',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'my-2',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter / Modifier',
                'attr' => [
                    'class' => 'btn btn-warning mt-2',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
