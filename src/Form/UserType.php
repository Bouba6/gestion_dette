<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


        ->add('brochure', FileType::class, [
            'label' => 'Brochure (PDF file)',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using attributes
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'application/pdf',
                        'application/x-pdf',
                        'image/png', 
                        'image/jpeg', // Type MIME pour JPG
                    ],
                    'mimeTypesMessage' => 'Please upload a valid PDF document',
                ])
            ],
        ])
       
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 mb-4 block w-full px-4 py-2  border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'nom',
                ],
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 mb-4 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'prenom',
                ],
                'label' => 'Prenom',
                'required' => false,
            ])
            ->add('login', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 mb-4 block w-full px-4 py-2  border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'login',
                ],
                'label' => 'Login',
                'required' => false,
            ])

          

            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'mt-1 mb-4 block w-full px-4 py-2  border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'password',
                ],
                'label' => 'Password',
                'required' => false,

            ])

           

          
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            // 'validation_groups' => ['with_compte'],
        ]);
    }
}
