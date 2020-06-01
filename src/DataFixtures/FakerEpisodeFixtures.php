<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Faker;

class FakerEpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i <50; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->text);
            $episode->setNumber($faker->numberBetween(1,22));
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . rand(0,49)));
            $manager->persist($episode);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [FakerSeasonFixtures::class];
    }
}
