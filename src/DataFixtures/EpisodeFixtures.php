<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    const EPISODES = [

        [
            'title' => "Episode 1",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "2",
            'season' => "1",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 2",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "2",
            'season' => "1",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 3",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "3",
            'season' => "1",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 1",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "1",
            'season' => "2",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 2",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "2",
            'season' => "2",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 3",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "3",
            'season' => "2",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 1",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "1",
            'season' => "3",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 2",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "2",
            'season' => "3",
            'program' => "Death note"
        ],
        [
            'title' => "Episode 3",
            'synopsis' => "Death note sit amet consectetur adipisicing elit. Tempora, soluta!",
            'number' => "3",
            'season' => "3",
            'program' => "Death note"
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (SELF::EPISODES as $chapter) {

            $episode = new Episode();

            $episode->setNumber($chapter['number']);

            $episode->setTitle($chapter['title']);

            $episode->setSynopsis($chapter['synopsis']);

            $episode->setSeason($this->getReference($chapter['program'] . ' season_' . $chapter['season']));

            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
            ProgramFixtures::class
        ];
    }
}
