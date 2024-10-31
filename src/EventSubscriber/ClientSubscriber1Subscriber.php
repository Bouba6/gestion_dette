<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ClientSubscriber1Subscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger; // Déclarer le type pour une meilleure clarté
    private string $brochuresDirectory; // Ajouter une propriété pour le répertoire
   
    public function __construct(SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory)
    {
        $this->slugger = $slugger;
        $this->brochuresDirectory = $brochuresDirectory; // Initialiser le répertoire
      
    }

    public function onPostSubmitEvent(PostSubmitEvent $event): void
    {
        $form = $event->getForm();
        $client = $form->getData();
        // dd($client);
        $brochureFile = $form->get('users')->get('brochure')->getData();

        if ($brochureFile) {
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename); // Utiliser $this->slugger
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            // Déplacer le fichier vers le répertoire spécifié
            try {
                $brochureFile->move($this->brochuresDirectory, $newFilename); // Utiliser $this->brochuresDirectory
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se produit lors du téléchargement
                // Vous pourriez envisager de logger l'erreur ou d'ajouter un message flash ici
            }

            // Mettre à jour la propriété 'brochureFilename' de l'utilisateur
            $client->getUsers()->setBrochureFilename($newFilename);
        }
        

    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmitEvent',
        ];
    }
}
