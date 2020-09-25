<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Ticket;
use App\DataFixtures\UserFixture;
use App\DataFixtures\EventFixture;
use App\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TicketFixture extends AbstractFixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            EventFixture::class,
            UserFixture::class
        ];
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Ticket::class, 3, function (Ticket $ticket) {
            $event = $this->getRandomReference(Event::class);

            $user = $this->getRandomReference(User::class);

            $ticket->setEventName($event);
            $ticket->setTicketClient($user);
            $ticket->setQuantity($this->faker->numberBetween(1, 5));

            $amount = $event->getPrice() * $ticket->getQuantity();

            $ticket->setAmount($amount);

            $eventDate = clone $event->getStartDate();
            $createdAt = clone $eventDate;

            $ticket->setCreatedAt($this->faker->dateTimeInInterval($createdAt, '-30 days'));
        });
    }
}
