<?php

namespace App\Form;

use App\Entity\Advertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

class SearchType extends AbstractType
{
    /**
     * @var $router
     */
    private $router;

    /**
     * SearchType constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Que recherchez vous ?'
                ],
                'required' => false,
            ])
            ->add('category')
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Adresse ou code postal'
                ],
                'required' => false,
            ])
            ->add('region')
            ->setAction($this->router->generate('search'))
            ->setMethod('GET')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class
        ]);
    }
}
