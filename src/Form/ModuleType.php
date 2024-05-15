<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du module*',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Za-z0-9]+$/',
                        'message' => 'Le nom du module ne peux contenir que des lettres et des chiffres'
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
            ->add('description', TextareaType::class, [
                'label' => 'Description du module*',
                'attr' => [
                    'class' => 'form-control'
                ]                
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie*',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-select'
                ],
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'buttonFormModule'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
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
