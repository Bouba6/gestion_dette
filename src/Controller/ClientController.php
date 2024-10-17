<?php

namespace App\Controller;


use App\DTO\ClientDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Post;
use Symfony\Component\HttpFoundation\Get;
use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;



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
public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
            {
                $client = new Client();
                $users = new Users();
                
                $formClient = $this->createForm(ClientType::class, $client);
                $formUser = $this->createForm(UserType::class, $users);
                
                $formClient->handleRequest($request);
                // dd($formClient);
                if ($formClient->isSubmitted() && $formClient->isValid()) {
                    
                    $client->setCreateAt(new \DateTimeImmutable());
                    $client->setUpdateAt(new \DateTimeImmutable());

                    
                    if ($request->get('toggleSwitch') === 'on') {
                        $formUser->handleRequest($request);
                        $users->setCreateAt(new \DateTimeImmutable());
                        $users->setUpdateAt(new \DateTimeImmutable());
                        $users->setBlocked(false);
                        
                       
                        // Persist the user and associate with the client
                        $entityManager->persist($users);
                        $client->setUsers($users); 
                     
                        // Optionally, you could add an else case to handle invalid user form submission if needed.
                    }
                    // Persist the client
                    $errors = $validator->validate($client);
                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }
                        return $this->render('client/form.html.twig', [
                            'formClient' => $formClient->createView(),
                            'formUser' => $formUser->createView(),
                        ]);
                    }
                    $entityManager->persist($client);
                    $entityManager->flush();

                    return $this->redirectToRoute('client.index');
                }

                // Render the form view
                return $this->render('client/form.html.twig', [
                    'formClient' => $formClient->createView(),
                    'formUser' => $formUser->createView(),
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