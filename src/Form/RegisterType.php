<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => new Length([
                    'min' => 10,
                    'max' => 60,
                ]),
                'attr' => [
                    'placeholder' => "Votre email"
                ]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30,
                ]),
                'attr' => [
                    'placeholder' => "Votre nom"
                ]
            ])
            ->add('firstname', TextType::class, [
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30,
                ]),
                'attr' => [
                    'placeholder' => "Votre prénom"
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' =>PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' =>['attr' => [
                    'placeholder' => "Votre mot de passe"
                ]],
                'second_options' =>[ 'attr' => [
                    'placeholder' => "Confirmez votre mot de passe"
                ]],
            ])
            ->add('submit', SubmitType::class , [
                'label' => "S'inscrire"
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
