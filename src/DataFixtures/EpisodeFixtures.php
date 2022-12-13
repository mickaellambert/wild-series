<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i = 0; $i < 25; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $episode = new Episode();

                $title = $faker->sentence(4);

                $episode->setNumber($j + 1);
                $episode->setSeason($this->getReference('season_' . $i));
                $episode->setSynopsis($faker->paragraphs(3, true));
                $episode->setTitle($title);
                $episode->setDuration(rand(30, 55));
                $episode->setSlug($this->slugger->slug($title)->lower());

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