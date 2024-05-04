<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Prénom',
            'constraints' => [
                new Regex([
                    'pattern' => '/^[A-Za]+$/',
                    'message' => 'Votre prénom ne peux contenir que des lettres'
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
            'label' => 'Nom',
            'constraints' => [
                new Regex([
                    'pattern' => '/^[A-Za]+$/',
                    'message' => 'Votre nom ne peux contenir que des lettres'
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
            ->add('email', EmailType::class, [
                'label' => 'Votre E-mail',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Email incorrect.'
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
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "Condition d'utilisation",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes sont différent',
                'options' => ['attr' => ['class' => 'password-field', 'class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/'),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                ],              
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'buttonFormModule'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
