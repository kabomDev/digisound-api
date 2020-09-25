<?php

namespace App\DataFixtures;

use App\DataFixtures\AbstractFixture;
use App\Entity\Artist;
use App\Entity\Event;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtistFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            EventFixture::class
        ];
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Artist::class, 30, function (Artist $artist) {
            $artist
                ->setName($this->faker->name)
                ->setDescription($this->faker->paragraphs(3, true))
                ->addEvent($this->getRandomReference(Event::class));
        });
    }
}
