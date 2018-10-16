<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name1')
            ->add('name2', ChoiceType::class, [
                'choices' => [
                    'test' => 'lorem eugze',
                    'test1' => 'lorem eugze',
                ]
            ])
            ->add('name3')
            ->add('name4', ChoiceType::class, [
                'choices' => [
                    'test' => 'lorem eugze',
                    'test1' => 'lorem eugze',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
