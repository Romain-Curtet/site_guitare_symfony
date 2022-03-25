<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('lastname', TextType::class, [
            'attr' => [
                'placeholder' => "Votre nom"
            ]
        ])
        ->add('firstname', TextType::class, [
            'attr' => [
                'placeholder' => "Votre prénom"
            ]
        ])
        ->add('email', EmailType::class, [
            'attr' => [
                'placeholder' => "Votre email"
            ]
        ])
        ->add('content', TextareaType::class, [
            'attr' => [
                'placeholder' => "En quoi pouvons nous vous aider?"
            ]
        ])
        ->add('submit', SubmitType::class , [
            'label' => "Envoyer"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
