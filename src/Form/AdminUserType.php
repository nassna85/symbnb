<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class, [
                    'label' => "Prénom",
                    'attr' => [
                    'placeholder' => "Modifier le prénom"
                ]
            ])
            ->add(
                'lastName',
                TextType::class, [
                    'label' => "Nom",
                    'attr' => [
                    'placeholder' => "Modifier le nom"
                ]
            ])
            ->add(
                'email',
                EmailType::class, [
                    'label' => "Email",
                    'attr' => [
                    'placeholder' => "Modifier l'email"
                ]
            ])
            ->add(
                'picture',
                TextType::class, [
                    'label' => "Avatar",
                    'attr' => [
                    'placeholder' => "Modifier l'avatar"
                ]
            ])
            ->add(
                'introduction',
                TextType::class, [
                    'label' => 'Introduction',
                    'attr' => [
                    'placeholder' => "Modifier l'introduction"
                ]
            ])
            ->add(
                'description',
                TextareaType::class, [
                    'label' => 'Description',
                    'attr' => [
                    'placeholder' => "Modifier la description"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
