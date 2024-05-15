<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
                'label' => 'Nom de la session*',
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début*',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ]
                ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin*',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de place*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // ->add('closed', CheckboxType::class)


            // ->add('inscription', EntityType::class, [
            //     'class' => Student::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            //     ], CheckboxType::class)
            ->add('formation', EntityType::class, [
                'label' => 'Formation*',
                'class' => Formation::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('formateur', EntityType::class, [
                'label' => 'Formateur Référent*',
                'class' => Formateur::class,
                'choice_label' => 'identite',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'buttonFormSession'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'task_item',
        ]);
    }
}