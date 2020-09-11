<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [
           MovieFixtures::class,
            UserFixtures::class,
        ];
    }

    /**
     * Load data.
     *
     * @param ObjectManager $manager Object Manager
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            80,
            'comments',
            function () {
                $comment = new Comment();
                $comment->setContent($this->faker->sentence);
                $comment->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
                $comment->setMovie($this->getRandomReference('movies'));
                $comment->setUser($this->getRandomReference('users'));

                return $comment;
            }
        );

        $manager->flush();
    }
}
