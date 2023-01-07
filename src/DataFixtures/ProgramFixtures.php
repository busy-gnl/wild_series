<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [

        [
            'title' => "Walking dead",
            'synopsis' => "Des zombies envahissent la terre",
            'poster' => "http://serieall.fr/images/shows/the-walking-dead.jpg",
            'category' => "category_Horreur",
            'country' => "USA",
            'year' => '2010'
        ],
        [
            'title' => "Breaking Bad",
            'synopsis' => "Un professeur de chimie qui a le cancer des poumons devient méchant",
            'poster' => "https://www.pause-canap.com/media/wysiwyg/serie-breaking-bad.JPG",
            'category' => "category_Action",
            'country' => "USA",
            'year' => '2005'
        ],
        [
            'title' => "Lastman",
            'synopsis' => "Un homme se retrouve contraint de combattre sur le ring pour sauver la fille de son ami mort",
            'poster' => "https://www.numerama.com/wp-content/uploads/2017/12/lastman-une.jpg",
            'category' => "category_Animation",
            'country' => "France",
            'year' => '2019'
        ],
        [
            'title' => "Better Call Saul",
            'synopsis' => "La vie d\' un ancien arnaqueur amateur qui tente de devenir avocat",
            'poster' => "https://images.rtl.fr/~c/2000v2000/rtl/www/1093495-better-call-saul-bat-des-records-des-son-premier-episode.jpg",
            'category' => "category_Action",
            'country' => "USA",
            'year' => '2015'
        ],
        [
            'title' => 'Death note',
            'synopsis' => 'Un étudiant très intelligent trouve un cahier au pouvoir mystérieux',
            'poster' => 'https://static.actugaming.net/media/2017/03/Death-Note.jpg',
            'category' => 'category_Animation',
            'country' => "Japan",
            'year' => '2005'
        ],
    ];

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        foreach (SELF::PROGRAMS as $key => $serie) {

            $program = new Program();
            $program->setTitle($serie['title']);
           
            $program->setSynopsis($serie['synopsis']);

            $program->setPoster($serie['poster']);
            $program->setCountry($serie['country']);
            $program->setYear($serie['year']);

            $program->setCategory($this->getReference($serie['category']));
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
