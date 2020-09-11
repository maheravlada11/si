<?php

/**
 * UserData fixtures.
 */

namespace App\DataFixtures;

use App\Entity\UserData;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UserDataFixtures.
 */
class UserDataFixtures extends AbstractBaseFixtures
{
    /**
     * Load.
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'userdata', function ($i) {
            $userData = new UserData();
            $userData->setName($this->faker->firstName);
            $userData->setSurname($this->faker->lastName);
            $userData->setNickname($this->faker->firstName);
            $userData->setUser($this->getReference('users_'.$i)); // users_0 ...

            return $userData;
        });

        $this->createMany(3, 'userdata-admin', function ($i) {
            $userData = new UserData();
            $userData->setName($this->faker->firstName);
            $userData->setSurname($this->faker->lastName);
            $userData->setNickname($this->faker->firstName);
            $userData->setUser($this->getReference('admins_'.$i)); // admins_0 ...

            return $userData;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
