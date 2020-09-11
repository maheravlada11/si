<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\CommentService;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Comment Controller.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * Comment service.
     *
     * @var CommentService
     */
    private $commentService;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService Comment service
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Movie $movie Movie
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create-comment/{id}",
     *     methods={"GET", "POST"},
     *     name="comment_create",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function create(Request $request, Movie $movie): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setMovie($movie);
            $comment->setUser($this->getUser());
            $this->commentService->save($comment);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render(
            'comment/create.html.twig',
            [
                'form' => $form->createView(),
                'movie' => $movie,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/edit-comment/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_edit",
     * )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', comment)")
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $movie = $comment->getMovie();
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render(
            'comment/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'movie' => $comment->getMovie(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_delete",
     * )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('DELETE', comment)")
     */
    public function delete(Request $request, Comment $comment): Response
    {
        $movie = $comment->getMovie();
        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);
        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'movie' => $comment->getMovie(),
            ]
        );
    }
}
