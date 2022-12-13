<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    private const PROGRAMS = [
        [
            'title'    => 'Walking Dead',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action',
            'country'  => 'USA',
            'year'     => '2010',
            'poster'   => 'https://static.posters.cz/image/1300/affiches-et-posters/the-walking-dead-city-i15032.jpg'
        ],
        [
            'title'    => 'Game of Thrones',
            'synopsis' => 'Du sang, du sexe et du suspense',
            'category' => 'Fantastique',
            'country'  => 'USA',
            'year'     => '2011',
            'poster'   => 'https://m.media-amazon.com/images/I/71pH7+c6YyL.jpg'
        ],
        [
            'title'    => 'One Punch Man',
            'synopsis' => 'Chauve, il explose tout le monde',
            'category' => 'Animation',
            'country'  => 'Japan',
            'year'     => '2016',
            'poster'   => 'https://static.posters.cz/image/750/affiches-et-posters/one-punch-man-collage-i33906.jpg'
        ],
        [
            'title'    => 'Handmade\'s Tail',
            'synopsis' => 'Ne mangez pas devant.',
            'category' => 'Aventure',
            'country'  => 'USA',
            'year'     => '2014',
            'poster'   => 'https://m.media-amazon.com/images/I/71HPGsdOBOL._AC_SY606_.jpg'
        ],
        [
            'title'    => 'Le Seigneur des Anneaux - Les Anneaux de Pouvoir',
            'synopsis' => 'On veut des thunes, mais c\'est joli !',
            'category' => 'Aventure',
            'country'  => 'USA',
            'year'     => '2022',
            'poster'   => 'https://pbs.twimg.com/media/Fd_9QhtakAEBpOk?format=jpg&name=small'
        ]
    ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {

        foreach (self::PROGRAMS as $key => $programArray) {
            $program = new Program();
            $program->setTitle($programArray['title']);
            $program->setSynopsis($programArray['synopsis']);
            $program->setCategory($this->getReference('category_' . $programArray['category']));
            $program->setCountry($programArray['country']);
            $program->setYear($programArray['year']);
            $program->setPoster($programArray['poster']);
            $program->setSlug($this->slugger->slug($programArray['title'])->lower());
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
