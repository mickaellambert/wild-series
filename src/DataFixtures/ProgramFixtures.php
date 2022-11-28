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
            'category' => 'Action'
        ],
        [
            'title'    => 'Game of Thrones',
            'synopsis' => 'Du sang, du sexe et du suspense',
            'category' => 'Fantastique'
        ],
        [
            'title'    => 'One Punch Man',
            'synopsis' => 'Chauve, il explose tout le monde',
            'category' => 'Animation'
        ],
        [
            'title'    => 'Handmade\'s Tail',
            'synopsis' => 'Ne mangez pas devant.',
            'category' => 'Aventure'
        ],
        [
            'title'    => 'Le Seigneur des Anneaux - Les Anneaux de Pouvoir',
            'synopsis' => 'On veut des thunes, mais c\'est joli !',
            'category' => 'Aventure'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programArray) {
            $program = new Program();
            $program->setTitle($programArray['title']);
            $program->setSynopsis($programArray['synopsis']);
            $program->setCategory($this->getReference('category_' . $programArray['category']));
            $manager->persist($program);
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
