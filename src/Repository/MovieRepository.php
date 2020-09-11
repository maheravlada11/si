<?php
/**
 * Movie repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class MovieRepository.
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * MovieRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * Query all records.
     *
     * @param array $filters Filters array
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial movie.{id, title}',
                'partial category.{id, title}',
                'partial tags.{id, title}'
            )
            ->join('movie.category', 'category')
            ->leftJoin('movie.tags', 'tags')
            ->orderBy('movie.updatedAt', 'DESC');
        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder $queryBuilder Query builder
     * @param array        $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }

    /**
     * Query movies by author.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByAuthor(UserInterface $author): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('movie.author = :author')
            ->setParameter('author', $author);

        return $queryBuilder;
    }


    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('movie');
    }



    /**
     * Save record.
     *
     * @param Movie $movie Movie entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Movie $movie): void
    {
        $this->_em->persist($movie);
        $this->_em->flush($movie);
    }



    /**
     * Delete record.
     *
     * @param \App\Entity\Movie $movie Movie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Movie $movie): void
    {
        $this->_em->remove($movie);
        $this->_em->flush($movie);
    }



}