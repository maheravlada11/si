<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\UserDataType;
use App\Service\UserDataService;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 *
 * @IsGranted("ROLE_USER")
 *
 * @Route(
 *     "/user"
 * )
 */
class UserController extends AbstractController
{

    /**
     * User Service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * User Data service.
     *
     * @var \App\Service\UserDataService
     */
    private $userDataService;

    /**
     * UserController constructor.
     *
     * @param UserService      $userService
     * @param UserDataService  $userDataService
     */
    public function __construct(UserService $userService, UserDataService $userDataService)
    {
        $this->userService = $userService;
        $this->userDataService = $userDataService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="user_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Show user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_show",
     * )
     * @IsGranted("ROLE_USER")
     */
    public function show(User $user): Response
    {
        return $this->render(
            'user/show.html.twig',
            ['user' => $user]
        );
    }
}
