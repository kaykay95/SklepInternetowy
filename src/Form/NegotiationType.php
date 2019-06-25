<?php

namespace App\Form;

use App\Entity\Negotiation;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class NegotiationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('desiredDiscount', MoneyType::class, [
                'attr' => [
                    'placeholder' => 'x.xx',
                    'constraints' => [
                        new Regex( array( 'pattern' => '/[0-9]{1,}\.[0-9]{2}/'))]]])
            ->add('description', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Negotiation::class,
        ]);
    }
}
