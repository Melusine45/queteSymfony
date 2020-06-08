<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln' => [
            'programs' => [
                'program_0',
                'program_5',
            ]
        ],
        'Norman Reedus' => [
            'programs' => [
                'program_0',
            ]
        ],
        'Lauren Cohan' => [
            'programs' => [
                'program_0',
            ]
        ],
        'Danai Gurira' => [
            'programs' => [
                'program_0',
            ],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        foreach (self::ACTORS as $actorName => $data) {

            $actor = new Actor();
            $actor->setName($actorName);
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            foreach ($data['programs'] as $program) {
                $actor->addProgram($this->getReference($program));
            }

            $manager->persist($actor);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
