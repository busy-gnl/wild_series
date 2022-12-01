<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class SeasonFixtures extends Fixture implements DependentFixtureInterface

{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();


        for ($i = 0; $i < 5; $i++) {

            $season = new Season();

            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base

            $season->setNumber($faker->numberBetween(1, 10));

            $season->setYear($faker->year());

            $season->setDescription($faker->paragraphs(3, true));


            $season->setProgram($this->getReference('program_' . $faker->numberBetween(0, 5)));

            $this->addReference($season->getProgram() . ' season ' . $season->getNumber(), $season);

            $manager->persist($season);
        }


        $manager->flush();
    }


    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
