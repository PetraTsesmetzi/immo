<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        UserFactory::createOne(['email'=>'admin@gmx.de','roles'=>['ROLE_ADMIN']]);
        UserFactory::createOne(['email'=>'user@gmx.de','roles'=>['ROLE_USER']]);
        UserFactory::createMany(20);


        $manager->flush();
    }
}
