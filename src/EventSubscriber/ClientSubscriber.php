<?php

namespace App\EventSubscriber;

use App\Entity\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ClientSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmitEvent',
        ];
    }

    

    public function onPostSubmitEvent(FormEvent $event): void
    {
       
        // dd($event->getData());
        $client = $event->getData();
        // error_log("plante");     
       
        if ($client instanceof Client && $client->getUsers()) {
           
            $user=$client->getUsers();
            // $user->setLogin('seydinaaboubacarsadikhbathily@gmail.com');
            $email = (new Email())
                ->from('seydinaaboubacarsadikhbathily@gmail.com') // Remplacez par votre adresse email
                ->to('seydinaaboubacarsadikhbathily@gmail.com') // Assurez-vous que l'utilisateur a une méthode getEmail()
                ->subject('Bienvenue à bord!')
                ->text('Merci de vous être inscrit, ' . $client->getUsers()->getNom() . '!');
          
            $this->mailer->send($email);
        }
    }

}
