<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();

            $actor->setName($faker->name());

            for ($j = 0; $j < 3; $j++) {
                $actor->addProgram($this->getReference('program_' . rand(0, 4)));
            }

            $manager->persist($actor);
        }

        $manager->flush();
    }

    	/**
	 * This method must return an array of fixtures classes
	 * on which the implementing class depends on
	 * @return array<string>
	 */
	public function getDependencies() 
    {
        return [
            ProgramFixtures::class,
        ];
	}
}
