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

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {

            $actor = new Actor();
            $actor->setName($faker->lastname() . ' ' . $faker->firstname());
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
