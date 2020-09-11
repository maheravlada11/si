<?php
/**
 * Movie fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class MovieFixtures.
 */
class MovieFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(100, 'movies', function ($i) {
            $movie = new Movie();
            $movie->setTitle($this->faker->sentence);
            $movie->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $movie->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $movie->setCategory($this->getRandomReference('categories'));
            $movie->setDirector($this->faker->name);
            $movie->setDescription($this->faker->sentence);


            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0, 5)
            );

            foreach ($tags as $tag) {
                $movie->addTag($tag);
            }

            $movie->setAuthor($this->getRandomReference('users'));

            return $movie;
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
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}