<?php

namespace App\Controller;


use App\DTO\ClientDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Post;
use Symfony\Component\HttpFoundation\Get;
use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;



use App\Entity\Dette;
use App\Entity\Client;
use App\Form\ClientType;
use App\Form\DetteType;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Users;
use App\Form\UserType;





class ClientController extends AbstractController
{
    #[Route('/client', name: 'client.index',methods:['POST','GET'])]
    public function index(ClientRepository $clientRepository ,Request $request,PaginatorInterface $paginator): Response
    {
        $queryBuilder=$clientRepository->createQueryBuilder('c');
        $pagination=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            5
        );
        
        return $this->render('client/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
   

   #[Route('/client/store', name: 'client.store', methods: ['POST', 'GET'])]
   public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory): Response
{
    $client = new Client();
    $users=new Users();
    
    // Créez le formulaire ClientType (qui inclut maintenant UserType)
    $formClient = $this->createForm(ClientType::class, $client, ['mailer' => $mailer]);
    
    // Traitez le formulaire
    $formClient->handleRequest($request);
   
    if ($formClient->isSubmitted() && $formClient->isValid()) {
        // Gérer les champs du formulaire Client
        $client->setCreateAt(new \DateTimeImmutable());
        $client->setUpdateAt(new \DateTimeImmutable());
       
        if($request->request->get('toggleSwitch')==='on'){
            // $password=$client->getUsers()->getPassword()
            $users=$client->getUsers();
           
            $users->setCreateAt(new \DateTimeImmutable());
            $users->setUpdateAt(new \DateTimeImmutable());
            $users->setBlocked(false);
            $errors=$validator->validate($users);
            // dd($errors);
            if (count($errors) > 0) {
                return $this->render('client/form.html.twig', [
                    'formClient' => $formClient->createView(),
                    'error'=>$errors,
                    
                ]);
    
            }
            $entityManager->persist($users);
           
            $client->setUsers($users);
          
            $client->getUsers()->setPassword($passwordHasher->hashPassword($client->getUsers(), $client->getUsers()->getPassword()));
        }
        else{
            $client->setUsers(null);
        }
    
        // Valider l'entité Client
        $errors = $validator->validate($client);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            return $this->render('client/form.html.twig', [
                'formClient' => $formClient->createView(),
            ]);

        }
        

        // Hachage du mot de passe et persist
        
        $entityManager->persist($client);
        $entityManager->flush();

        return $this->redirectToRoute('client.index');
    }

    // Rendu du formulaire
    return $this->render('client/form.html.twig', [
        'formClient' => $formClient->createView(),
        
    ]);
}


        #[Route('/client/search', name: 'client.search', methods: ['GET'])]
        public function search(ClientRepository $clientRepository, Request $request, PaginatorInterface $paginator): Response
        {
            // Récupérer le terme de recherche
            $searchTerm = $request->query->get('search', '');
            $clientSearchDTO = new ClientDTO($searchTerm); // Passer le terme de recherche dans le DTO
            
            // Construire le QueryBuilder pour la recherche
          
    
            // Vérifier si le terme de recherche est fourni
            if (!empty($clientSearchDTO->getSearch())) {
                $array=$clientRepository->searchByTelephoneAndName($clientSearchDTO->getSearch());
            }

            if($request->get('filter') !==null){
                // ($request->get('filter'));
                $array=$clientRepository->findAccountentClient($request->get('filter'));
            }
    
            // Pagination
            $pagination = $paginator->paginate(
                $array,
                $request->query->getInt('page', 1),
                4 // Nombre d'éléments par page
            );
    
            return $this->render('client/index.html.twig', [
                'pagination' => $pagination,
                'search' => $clientSearchDTO->getSearch(), // Passer la valeur de recherche à la vue
            ]);
        }
        

        #[Route('/client/ajout/{id}', name:'client.ajout.form', methods: ['POST','GET'])]
        public function ajout(DetteRepository $detteRepository,ClientRepository $clientRepository, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
        {
            $dette=new Dette();
            $clientId=$request->get('id');
            $formDette = $this->createForm(DetteType::class, $dette);
            $dette->setclient($clientRepository->find($clientId));
            $formDette->handleRequest($request);
            if($formDette->isSubmitted() && $formDette->isValid()){
                $dette->setMontantVerser(0);
                $entityManager->persist($dette);
                $entityManager->flush();
                return $this->redirectToRoute('client.dette', ['id' => $clientId]);
            }

            return $this->render('dette/form.html.twig', [
                'formDette' => $formDette->createView(),
               
            ]);

           
        }
       


       #[Route('/client/delete/{id}', name: 'client_delete', methods: ['POST'])]
       public function delete(Client $client, EntityManagerInterface $entityManager): Response
       {

           $entityManager->remove($client);
           $entityManager->flush();
   
           return $this->json(['message' => 'Client supprimé avec succès'], Response::HTTP_OK);
       }

       #[Route('/client/dette/{id?}', name: 'client.dette', methods: ['GET'])]
       public function dette(DetteRepository $detteRepository,ClientRepository $clientRepository, EntityManagerInterface $entityManager, int|null $id=null, Request $request, PaginatorInterface $paginator): Response
       {
            if($request->get('filter') !==null){
                $clientId = $request->get('client');
                $client = $clientRepository->find($clientId);
                $array = $detteRepository->listerDetteByFilter($request->get('filter'), $client);
                
        
            }
            else{
                $client = $clientRepository->find($id);
                $array=$detteRepository->searchDetteForClient($client);
            }
           
            $pagination=$paginator->paginate(
              $array,
              $request->query->getInt('page', 1),
              4
            );
            
            return $this->render('dette/index.html.twig', [
                'pagination' => $pagination,
                'client' => $client
            ]) ;

        }


        
   




}