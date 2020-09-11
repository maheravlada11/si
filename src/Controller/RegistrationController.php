<?php
/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\RegistrationType;
use App\Service\RegistrationService;
use App\Service\UserDataService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * Registration service.
     *
     * @var \App\Service\RegistrationService
     */
    private $registrationService;

    /**
     * User data service.
     *
     * @var \App\Service\UserDataService
     */
    private $userDataService;

    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * RegistrationController constructor.
     *
     * @param \App\Service\RegistrationService $registrationService Registration service
     * @param \App\Service\UserDataService     $userDataService     User data service
     */
    public function __construct(RegistrationService $registrationService, UserDataService $userDataService, UserService $userService)
    {
        $this->registrationService = $registrationService;
        $this->userDataService = $userDataService;
        $this->userService = $userService;
    }

    /**
     * Register.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/registration",
     *     name="register"
     * )
     */
    public function register(Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('movie_index');
        }

        $user = new User();
        $userData = new UserData();
        $form = $this->createForm(RegistrationType::class, $userData);
        $form->handleRequest($request);

        /*
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('password')->getData()
                )
            );
        */
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword( // zakodowane hasło
                $this->registrationService->encodingPassword($user, $form->get('user')->get('password')->getData())
            );

            $user->setEmail($form->get('user')->get('email')->getData());
            $user->setUserData($userData);
            $user->setRoles(['ROLE_USER']);
            //$userDataRepository->save($userData);
            $this->userDataService->save($userData);
            //$userRepository->save($user);
            $this->userService->save($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($userData);
            $entityManager->flush();

            $this->addFlash('success', 'message_registered_successfully');

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'registration/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
