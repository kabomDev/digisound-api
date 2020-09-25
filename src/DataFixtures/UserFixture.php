<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends AbstractFixture
{

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 10, function (User $user, $i) {

            $user
                ->setFullName($this->faker->name)
                ->setEmail("user$i@gmail.com")
                ->setPassword("password");
        });
    }
}
