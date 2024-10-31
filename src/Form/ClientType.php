<?php

namespace App\Form;
 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints as Assert;


use App\Entity\Client;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\EventSubscriber\ClientSubscriber1Subscriber;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\EventSubscriber\ClientSubscriber;
use Symfony\Component\Validator\Constraints\Regex;

 


class ClientType extends AbstractType
{
    private $slugger;
    private string $brochuresDirectory;

    public function __construct(SluggerInterface $slugger, #[Autowire('%brochures_directory')] string $brochuresDirectory)
    {
        $this->slugger = $slugger;
        $this->brochuresDirectory = $brochuresDirectory;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      

        $builder

        // ->addEventSubscriber(new ClientSubscriber($options['mailer']))

        ->add('telephone', TextType::class, [
            'attr' => [
                'class' => 'mt-1 mb-4 block w-full px-4 py-2 font-weight-bold  border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                'placeholder' => 'Téléphone',
            ],

            'required' => false, 
            'label' => 'Téléphone',
        ])
        ->add('surname', TextType::class, [
            'attr' => [
                'class' => 'mt-1 mb-4 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                'placeholder' => 'surname',
            ],
            'required' => false, 
            'label' => 'Surname',
        ])
        ->add('adresse', TextType::class, [
            'attr' => [
                'class' => 'mt-1 mb-4 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                'placeholder' => 'Adresse',
                'novalidate' => 'novalidate',
            ],
            'required' => false, 
            
            'label' => 'Adresse',
        ])

        ->addEventSubscriber(new ClientSubscriber1Subscriber($this->slugger, $this->brochuresDirectory))
        ->addEventSubscriber(new ClientSubscriber($options['mailer']))

        ->add('users', UserType::class)

        // Décommenter si necessário
        // ->add('createAt', DateTimeType::class, [
        //     'widget' => 'single_text',
        //     'attr' => [
        //         'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
        //     ],
        // ])
        // ->add('updateAt', DateTimeType::class, [
        //     'widget' => 'single_text',
        //     'attr' => [
        //         'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
        //     ],
        // ])
       
    
       

        ->add('save', SubmitType::class, [
            
            'label' => 'Enregistrer',
        ])

        ->add('cancel', SubmitType::class, [
           
        ]);

    //     ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event):void {
    //         $form_data = $event->getData();
    //         $form = $event->getForm();
    //         if(isset($form_data['toggleSwitch']) && $form_data['toggleSwitch'] == 'on'){
    //             $form->add('users', UserType::class, [
    //               'label'=> false, 
    //               'required'=>false,
    //             ]);
        
    //         }
    //     }
    // );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'mailer' => null, 
        ]);

        $resolver->setDefined('mailer');
    }
}
