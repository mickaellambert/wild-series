<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        $seasonNumber = 0;

        for($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $season = new Season();
                $season->setNumber($j + 1);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
    
                $season->setProgram($this->getReference('program_' . $i));
    
                $manager->persist($season);

                $this->addReference('season_' . $seasonNumber, $season);

                $seasonNumber++;
            }            
        }

        $manager->flush();
    }
    
	/**
	 * This method must return an array of fixtures classes
	 * on which the implementing class depends on
	 * @return array<string>
	 */
	public function getDependencies() {
        return [
            ProgramFixtures::class,
        ];
	}
}