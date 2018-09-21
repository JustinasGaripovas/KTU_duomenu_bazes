<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class LAKDReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Date', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'mapped'=> false,
                'attr' => [
                    'class' => 'js-datepicker-1',
                    'autocomplete'=>'off']
            ))
            ->add('reportFor', ChoiceType::class, array(
                'choices' => array(
                    '6:00' => '6:00',
                    '9:00' => '9:00',
                    '13:00' => '13:00',
                    '16:00' => '16:00'
                )
            ))
            ->add('Preview', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline-block btn btn-success'
                ]
            ))

            ->add('GenerateXLS', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline btn btn-success'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped'=>false,
            'translation_domain' => 'translation'
        ]);
    }
}