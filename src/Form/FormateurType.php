<?php

namespace App\Form;

use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Prénom",
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Za]+$/',
                        'message' => 'Le prénom du formateur ne peux contenir que des lettres'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le formulaire ne peux être vide.',
                    ])
                    ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Za]+$/',
                        'message' => 'Le nom du formateur ne peux contenir que des lettres'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le formulaire ne peux être vide.',
                    ])
                    ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
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
            'data_class' => Formateur::class,
        ]);
    }
}
