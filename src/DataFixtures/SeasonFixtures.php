<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface

{
    const SEASONS = [

        [
            'number' => "1",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Walking dead"
        ],
        [
            'number' => "2",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Walking dead"
        ],
        [
            'number' => "3",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Walking dead"
        ],
        [
            'number' => "1",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Breaking Bad"
        ],
        [
            'number' => "2",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Breaking Bad"
        ],
        [
            'number' => "3",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Breaking Bad"
        ],
        [
            'number' => "1",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Lastman"
        ],
        [
            'number' => "2",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Lastman"
        ],
        [
            'number' => "3",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Lastman"
        ],
        [
            'number' => "1",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Death note"
        ],
        [
            'number' => "2",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Death note"
        ],
        [
            'number' => "3",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Death note"
        ],
        [
            'number' => "1",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Better Call Saul"
        ],
        [
            'number' => "2",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Better Call Saul"
        ],
        [
            'number' => "3",
            'year' => "2005",
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, expedita?",
            'program' => "Better Call Saul"
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (SELF::SEASONS as $book) {

            $season = new Season();
            $season->setNumber($book['number']);

            $season->setYear($book['year']);

            $season->setDescription($book['description']);

            $season->setProgram($this->getReference($book['program']));

            $manager->persist($season);
            $this->addReference($book['program'] . ' season_' . $book['number'], $season);
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
