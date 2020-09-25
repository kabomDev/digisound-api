<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Faker\Factory;
use App\Entity\Event;
use Doctrine\Persistence\ObjectManager;

class EventFixture extends AbstractFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Event::class, 10, function (Event $event) {
            $startDate = $this->faker->dateTimeBetween('now', '+6 months');
            $endDate = (clone $startDate)->modify(mt_rand(0, 3) . 'days');

            $event
                ->setTitle("Festival " . $this->faker->company)
                ->setDescription($this->faker->paragraphs(3, true))
                ->setCity($this->faker->city)
                ->setImage("image-exemple.png")
                ->setCapacity($this->faker->numberBetween($min = 15000, $max = 30000))
                ->setPrice($this->faker->randomFloat(2, 49, 100))
                ->setStartDate($startDate)
                ->setEndDate($endDate);
        });
    }
}
