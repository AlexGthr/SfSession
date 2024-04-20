<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'M' => 'M',
                    'F' => 'F'
                ],
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new Regex('/^[0-9]*$/'),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Erreur avec votre numéro de téléphone.',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : 0388775544'
                ]
            ])

            // ->add('sessions', EntityType::class, [
            //     'class' => Session::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'buttonFormStudent'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}