<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Actor;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    const ACTOR_PICTURE = [
        'https://media.vogue.fr/photos/5cd945cd3296a520d015463d/2:3/w_2560%2Cc_limit/GettyImages-168793000.jpg',
        'https://www.bollywoodhungama.com/wp-content/uploads/2022/02/Brad-Pitt-starrer-Fight-Club-ending-restored-in-China-after-censorship-backlash.jpg',
        'https://i.pinimg.com/originals/c2/ff/bd/c2ffbd052bb4e56f1fe27461921bb4aa.jpg',
        'http://bamfstyle.com/wp-content/uploads/2013/05/o11cl-brn-main1.jpg',
        'https://www.themoviedb.org/t/p/w500/At3JgvaNeEN4Z4ESKlhhes85Xo3.jpg',
        'https://www.themoviedb.org/t/p/w500/fMDFeVf0pjopTJbyRSLFwNDm8Wr.jpg',
        'https://resize-elle.ladmedia.fr/rcrop/638,,forcex/img/var/plain_site/storage/images/loisirs/cinema/dossiers/dix-choses-que-vous-ne-saviez-peut-etre-pas-sur-robert-de-niro/16588568-4-fre-FR/Dix-choses-que-vous-ne-saviez-peut-etre-pas-sur-Robert-de-Niro.jpg',
        'https://img.pixers.pics/pho(s3:700/PI/74/11/700_PI7411_c2fb12d7a38abe2ef9dc9156d2687a34_5b7aba58ba663_.,526,700,jpg)/coussins-decoratifs-johnny-depp.jpg.jpg',
        'https://i.pinimg.com/originals/46/56/85/4656857a842a1719ab7b79429c5c7aa5.jpg',
        'https://fr.trace.tv/wp-content/uploads/sites/2/2017/05/jamie-django-tanrantino1.jpg',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (SELF::ACTOR_PICTURE as $picture) {

            $actor = new Actor();
            $actor->setName($faker->lastname() . ' ' . $faker->firstname());
            $actor->setPicture($picture);
            $actor->addProgram($this->getReference('program_' . $faker->numberBetween(0, 4)));
            $actor->addProgram($this->getReference('program_' . $faker->numberBetween(0, 4)));
            $actor->addProgram($this->getReference('program_' . $faker->numberBetween(0, 4)));
            $manager->persist($actor);
            $this->addReference($actor->getName(), $actor);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
