<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i = 0; $i < 25; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $episode = new Episode();

                $episode->setNumber($j + 1);
                $episode->setSeason($this->getReference('season_' . $i));
                $episode->setSynopsis($faker->paragraphs(3, true));
                $episode->setTitle($faker->sentence(4));

                $manager->persist($episode);
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
            SeasonFixtures::class,
        ];
	}
}