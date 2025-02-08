<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Indiquez prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Indiquez nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre addresse (numero et rue)',
                'attr' => [
                    'placeholder' => 'Indiquez adresse'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postale',
                'attr' => [
                    'placeholder' => 'Indiquez postal code'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Indiquez ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre numero de telephone',
                'attr' => [
                    'placeholder' => 'Indiquez numero'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder adresse',
                'attr' => [
                    'class' => 'btn btn-success',
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
