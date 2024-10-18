<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('telephone', TextType::class, [
            'required' => false, 
            'label' => 'Téléphone',
            'attr' => [
                'class' => 'mt-1 mb-4 block w-full px-4 py-2 font-weight-bold  border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                'placeholder' => 'Téléphone',
            ],
        'constraints' => [
            new NotBlank([
                'message' => 'Please enter your Téléphone',
            ]),

        new NotNull([
            'message' => 'Please enter your telephone',
        ]),
        new Regex(
             '/^(77|78|76)[0-9]{7)$/',
             'le numéro de télephone doit commencer par 77, 78 ou 76 et avoir 10 chiffres'
        )     
        ]
    ]
    ->add('surname', TextType::class, [
        'required' => false, 
        'label' => 'Surname',
        'attr' => [
            
        ]])

    ->add('search', SubmitType::class,[
        'attr' => [
            'class'=> 'mt-1 mb-4 block w-full px-4 py-2 font-weight-bold  border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
        ]
    ] )

    ); }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientDTO::class,
        ]);
    }
}
