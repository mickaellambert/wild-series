<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private const PROGRAMS = [
        [
            'title'    => 'Walking Dead',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action',
            'country'  => 'USA',
            'year'     => '2010'
        ],
        [
            'title'    => 'Game of Thrones',
            'synopsis' => 'Du sang, du sexe et du suspense',
            'category' => 'Fantastique',
            'country'  => 'USA',
            'year'     => '2011'
        ],
        [
            'title'    => 'One Punch Man',
            'synopsis' => 'Chauve, il explose tout le monde',
            'category' => 'Animation',
            'country'  => 'Japan',
            'year'     => '2016'
        ],
        [
            'title'    => 'Handmade\'s Tail',
            'synopsis' => 'Ne mangez pas devant.',
            'category' => 'Aventure',
            'country'  => 'USA',
            'year'     => '2014'
        ],
        [
            'title'    => 'Le Seigneur des Anneaux - Les Anneaux de Pouvoir',
            'synopsis' => 'On veut des thunes, mais c\'est joli !',
            'category' => 'Aventure',
            'country'  => 'USA',
            'year'     => '2022'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $key => $programArray) {
            $program = new Program();
            $program->setTitle($programArray['title']);
            $program->setSynopsis($programArray['synopsis']);
            $program->setCategory($this->getReference('category_' . $programArray['category']));
            $program->setCountry($programArray['country']);
            $program->setYear($programArray['year']);
            $manager->persist($program);

            $this->addReference('program_' . $key, $program);
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
            CategoryFixtures::class,
        ];
	}
}
