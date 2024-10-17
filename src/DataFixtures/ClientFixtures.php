<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\Client;
use App\Entity\Users;
use App\Entity\Dette;

class ClientFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
       
        for($i = 1; $i < 10; $i++) {
            $client = new Client();
            $client->setSurname('surname'.$i);
            $client->setTelephone('telephone'.$i);
            $client->setAdresse('adresse'.$i);
            if($i % 2 == 0) {
                $users = new Users();
                $users->setNom('nom'.$i);
                $users->setPrenom('prenoms'.$i);
                $users->setLogin('login'.$i);
                $plainTextPassword = 'password'.$i;
                $hashedPassword = $this->encoder->hashPassword($users, $plainTextPassword);
                $users->setPassword($hashedPassword);
                $users->setClient($client);
                $manager->persist($users);
                for($j = 1; $j < 2; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(mt_rand(500, 2000));
                    $dette->setMontantVerser(mt_rand(0, 500));
                    $dette->setClient($client);
                    $client->addDette($dette);
                   
                }
            }else {
                for($j = 0; $j < 2; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(mt_rand(500, 2000));
                    $dette->setMontantVerser(mt_rand(0, 500));
                    $dette->setClient($client);
                    $client->addDette($dette);
                }
            }
            $manager->persist($client);

        }

        $manager->flush();
    }
}
