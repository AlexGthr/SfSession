<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('startDate', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
                ], DateTime::class)
            ->add('endDate', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
                ], DateTime::class)
            ->add('nbPlace', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('closed', CheckboxType::class)
            
            ->add('inscription', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
