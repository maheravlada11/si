<?php
/**
 * Movie controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\CommentType;
use App\Form\MovieType;
use App\Repository\CommentRepository;
use App\Repository\MovieRepository;
use App\Service\CommentService;
use App\Service\MovieService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



/**
 * Class MovieController.
 *
 * @Route("/movie")
 *
 *
 */
class MovieController extends AbstractController
{

    /**
     * Movie service.
     *
     * @var \App\Service\MovieService
     */
    private $movieService;


    /**
     * MovieController constructor.
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;

    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="movie_index",
     * )
     */
    public function index(Request $request): Response
    {
//        $filters = [];
//        $filters['category_id'] = $request->query->getInt('filters_category_id');
//        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $pagination = $this->movieService->createPaginatedList(
            $request->query->getInt('page', 1),
            $request->query->getAlnum('filters', [])
        );

        return $this->render(
            'movie/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Movie $movie Movie entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="movie_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted(
     *
     *     "ROLE_USER"
     *
     * )
     *
     */
    public function show(Movie $movie): Response
    {

        return $this->render(
            'movie/show.html.twig',
            ['movie' => $movie
            ]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="movie_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     *
     */
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $movie->setCreatedAt(new \DateTime()); //TODO sprawd. czy to nie powinno by
//            $movie->setUpdatedAt(new \DateTime());
            $movie->setAuthor($this->getUser());
            $this->movieService->save($movie);
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute('movie_index');
        }

        return $this->render(
            'movie/create.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Movie $movie Movie entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="movie_edit",
     * )
     * IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie->setUpdatedAt(new \DateTime());
            $this->movieService->save($movie);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('movie_index');
        }

        return $this->render(
            'movie/edit.html.twig',
            [
                'form' => $form->createView(),
                'movie' => $movie,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Movie $movie Movie entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="movie_delete",
     * )
     * IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Movie $movie): Response
    {

//        if ($movie->getMovies()->count()) {
//            $this->addFlash('warning', 'message_category_contains_movies');
//
//            return $this->redirectToRoute('movie_index');
//        }

        $form = $this->createForm(
            FormType::class,
            [
                'createdAt' => $movie->getCreatedAt(),
                'title' => $movie->getTitle(),
                'updatedAt' => $movie->getUpdatedAt(),
            ],
            ['method' => 'DELETE']
        );
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->movieService->delete($movie);

            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('movie_index');
        }

        return $this->render(
            'movie/delete.html.twig',
            [
                'form' => $form->createView(),
                'movie' => $movie,
            ]
        );
    }

}