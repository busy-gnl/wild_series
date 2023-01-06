<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {

        //Puis ici nous demandons à la Factory de nous fournir un Faker

        $faker = Factory::create();


        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                for ($k = 0; $k < 10; $k++) {


                    $episode = new Episode();
                    //Ce Faker va nous permettre d'alimenter l'instance de episode que l'on souhaite ajouter en base
                    $episode->setNumber($k);
                    $episode->setTitle($faker->words(3, true));
                    $slug = $this->slugger->slug($episode->getTitle());
                    $episode->setSlug($slug);
                    $episode->setSynopsis($faker->paragraph());
                    $episode->setDuration($faker->numberBetween(40, 60));
                    $episode->setSeason($this->getReference('program_' . $i . '_season_' . $j));
                    $manager->persist($episode);
                }
            }
        }


        $manager->flush();
    }


    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class
        ];
    }
}
