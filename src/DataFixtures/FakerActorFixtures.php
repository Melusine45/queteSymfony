<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class FakerActorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        $faker = Faker\Factory::create('en_US');
        $j=0;
        for ($i = 0; $i <50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $actor->setImage($faker->imageUrl());
            $this->addReference('actor_' . $j, $actor);
            $manager->persist($actor);
            $j++;

        }

        $manager->flush();
    }

}
