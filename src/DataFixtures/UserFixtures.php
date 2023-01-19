<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {

        $this->passwordHasher = $passwordHasher;
    }



    public function load(ObjectManager $manager): void

    {

        // Création d’un utilisateur de type “contributeur” (= auteur)

        $contributor = new User();

        $contributor->setEmail('contributor@monsite.com');

        $contributor->setRoles(['ROLE_CONTRIBUTOR']);

        $hashedPassword = $this->passwordHasher->hashPassword(

            $contributor,

            'Wild4Ever'

        );


        $contributor->setPassword($hashedPassword);

        $manager->persist($contributor);


        // Création d’un utilisateur de type “administrateur”

        $admin = new User();

        $admin->setEmail('admin@monsite.com');

        $admin->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword(

            $admin,

            'admin'

        );

        $admin->setPassword($hashedPassword);

        $manager->persist($admin);
        $this->addReference($admin->getEmail(), $admin);


        // Sauvegarde des 2 nouveaux utilisateurs :

        $manager->flush();
    }
}
