<?php
/**
 * Movie service.
 */

namespace App\Service;

use App\Entity\Movie;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class MovieService.
 */
class MovieService
{
    /**
     * Movie repository.
     *
     * @var \App\Repository\MovieRepository
     */
    private $movieRepository;

    /**
     * Paginator interface.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private $tagService;

    /**
     * MovieService constructor.
     *
     * @param \App\Repository\MovieRepository          $movieRepository  Movie repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator       Paginator interface
     * @param CategoryService    $categoryService   Category service
     * @param TagService         $tagService        Tag service
     */
    public function __construct(MovieRepository $movieRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->movieRepository = $movieRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *  @param array $filters Array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Pagination interface
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->movieRepository->queryAll($filters),
            $page,
            MovieRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save movie.
     *
     * @param \App\Entity\Movie $movie movie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Movie $movie): void
    {
        $this->movieRepository->save($movie);
    }

    /**
     * Delete movie.
     *
     * @param \App\Entity\Movie $movie Movie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Movie $movie): void
    {
        $this->movieRepository->delete($movie);
    }

//    /**
//     * Find id for the movie.
//     *
//     * @return \App\Entity\Movie Movie entity
//     */
//    public function findMovieId(int $id)
//    {
//        return $this->movieRepository->find($id);
//    }

    /**
     * Prepare filters for the movies list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category']) && is_numeric($filters['category'])) {
            $category = $this->categoryService->findOneById(
                $filters['category']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag']) && is_numeric($filters['tag'])) {
            $tag = $this->tagService->findOneById($filters['tag']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }



}
