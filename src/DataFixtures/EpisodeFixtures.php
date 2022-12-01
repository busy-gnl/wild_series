<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        //Puis ici nous demandons à la Factory de nous fournir un Faker

        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {

            $episode = new Episode();

            //Ce Faker va nous permettre d'alimenter l'instance de episode que l'on souhaite ajouter en base

            $episode->setNumber($faker->numberBetween(1, 13));

            $episode->setTitle($faker->catchPhrase());

            $episode->setSynopsis($faker->paragraphs(3, true));


            $episode->setSeason($this->getReference($episode->getSeason()));


            $manager->persist($episode);
        }


        $manager->flush();
    }
    // public function load(ObjectManager $manager): void
    // {
    //     foreach (SELF::EPISODES as $chapter) {

    //         $episode = new Episode();

    //         $episode->setNumber($chapter['number']);

    //         $episode->setTitle($chapter['title']);

    //         $episode->setSynopsis($chapter['synopsis']);

    //         $episode->setEpisode($this->getReference($chapter['program'] . ' episode_' . $chapter['episode']));

    //         $manager->persist($episode);
    //     }

    //     $manager->flush();
    // }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            episodeFixtures::class,
            ProgramFixtures::class
        ];
    }
}
