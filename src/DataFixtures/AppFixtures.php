<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}


// public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator,UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, SluggerInterface $slugger,
// #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory): Response
//             {
              
//                 $client = new Client();
//                 $users = new Users();
                
//                 $formClient = $this->createForm(ClientType::class, $client,['mailer'=>$mailer]);
//                 $formUser = $this->createForm(UserType::class, $users);
              
//                 $formClient->handleRequest($request);
//                 $formUser->handleRequest($request);
//                 if ($formClient->isSubmitted() && $formClient->isValid()) {
                   
//                     $client->setCreateAt(new \DateTimeImmutable());
//                     $client->setUpdateAt(new \DateTimeImmutable());

                    
//                     if ($request->get('toggleSwitch') === 'on') {
                        
//                         $users->setCreateAt(new \DateTimeImmutable());
//                         $users->setUpdateAt(new \DateTimeImmutable());
//                         $users->setBlocked(false);
//                         // dd($formClient->getData());
//                         // $brochureFile = $formClient->get('brochure')->getData();
//                         // if ($brochureFile) {
//                         //     dd($brochureFile);
//                         //     $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//                         //     // this is needed to safely include the file name as part of the URL
//                         //     $safeFilename = $slugger->slug($originalFilename);
//                         //     $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            
//                         //     // Move the file to the directory where brochures are stored
//                         //     try {
//                         //         $brochureFile->move($brochuresDirectory, $newFilename);
//                         //     } catch (FileException $e) {
//                         //         // ... handle exception if something happens during file upload
//                         //     }
            
//                         //     // updates the 'brochureFilename' property to store the PDF file name
//                         //     // instead of its contents
//                         //     $users->setBrochureFilename($newFilename);
//                         // }
//                         $errors=$validator->validate($users);
//                         if (count($errors) > 0) {
//                             foreach ($errors as $error) {
//                                 $field = $error->getPropertyPath();
//                                 $formUser->get($field)->addError(new FormError($error->getMessage()));
//                             }
//                             return $this->render('client/form.html.twig', [
//                                 'formClient' => $formClient->createView(),
//                                 'formUser' => $formUser->createView(),
//                             ]);
//                         }

//                         $users->setPassword($passwordHasher->hashPassword($users, $users->getPassword()));
//                         // Persist the user and associate with the client
                      
//                         $entityManager->persist($users);
//                         $client->setUsers($users); 
                     
//                         // Optionally, you could add an else case to handle invalid user form submission if needed.
//                     }
//                     // Persist the client
//                     $errors = $validator->validate($client);
//                     if (count($errors) > 0) {
//                         // dd($errors);
//                         foreach ($errors as $error) {
//                             $this->addFlash('error', $error->getMessage());
//                         }
                       
//                         return $this->render('client/form.html.twig', [
//                             'formClient' => $formClient->createView(),
//                             'formUser' => $formUser->createView(),
//                         ]);
//                     }
                   
                 
//                     $entityManager->persist($client);
                  
                 
                    
//                     $entityManager->flush();
                  
                 
                   

//                     return $this->redirectToRoute('client.index');
//                 }

//                 // Render the form view
//                 return $this->render('client/form.html.twig', [
//                     'formClient' => $formClient->createView(),
//                     'formUser' => $formUser->createView(),
//                 ]);

// }

