<?php

namespace App\Form;

use App\Entity\NegotiationCategory;
use App\Entity\NegotiationExpression;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NegotiationExpressionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expression')
            ->add('negotiationCategory', EntityType::class, [
                'class' => NegotiationCategory::class,
                'choice_label' => function ($negotiationCategory) {
                    return $negotiationCategory->getName();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NegotiationExpression::class,
        ]);
    }
}
