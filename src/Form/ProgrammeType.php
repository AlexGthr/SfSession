<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbDay', IntegerType::class, [
                'label' => 'Nombre de jours',
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('module', EntityType::class, [
                'label' => 'Choix du module',
                'class' => Module::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('session', EntityType::class, [
                'label' => 'Choix de la session',
                'class' => Session::class,
                'choice_label' => 'formation',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'buttonFormProgramme'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
