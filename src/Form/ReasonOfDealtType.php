<?php

namespace App\Form;

use App\Entity\ReasonOfDealt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReasonOfDealtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', ChoiceType::class, [
                'choices' => [
                    'Veuillez sélectionner une raison' => [
                        'Je souhaite modifier le texte de mon annonce / ajouter des photos.' => ' Je souhaite modifier le texte de mon annonce / ajouter des photos.',
                        'Je souhaite renouveler mon annonce pour la faire apparaître en tête de liste ' => ' Je souhaite renouveler mon annonce pour la faire apparaître en tête de liste ',
                        'J\'ai vendu / loué mon bien sur Lebonpoint.com ' => 'J\'ai vendu / loué mon bien sur Lebonpoint.com',
                        ' J\'ai vendu / loué mon bien par un autre moyen ' => ' J\'ai vendu / loué mon bien par un autre moyen ',
                        'Autres' => 'Autres',
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReasonOfDealt::class,
        ]);
    }
}
