<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('price', null,  array(
                'attr' => ['min' => 0,
                    'max' => 999999]))
            ->add('minimalPrice', null,  array(
                'attr' => ['min' => 0,
                    'max' => 999999]))
            ->add('negotiationRatio',null, array(
                'attr' => ['min' => 0,
                    'max' => 99]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
