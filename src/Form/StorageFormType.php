<?php

namespace App\Form;

use App\Entity\Storage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('flowerId', EntityType::class, [
                'class' => \App\Entity\Flower::class, // Указываем сущность Flower
                'choice_label' => 'name', // Используем поле name для отображения
                'label' => 'Flower',
                'placeholder' => 'Выберите позицию',
                'mapped' => false,
            ])
            ->add('amount', null, [
                'label' => 'Amount',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Storage::class,
        ]);
    }
}
