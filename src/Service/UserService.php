<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * User repository.
     *
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var
     */
    private $paginator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param \App\Repository\UserRepository $userRepository User repository
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
          $this->userRepository->queryAll(),
          $page,
          UserRepository::PAGINATOR_ITEMS_FOR_PAGE
        );
    }

    /**
     * Save user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user)
    {
        $this->userRepository->save($user);
    }

    /**
     * Encoding user's password.
     *
     * @param $user
     * @param $form
     *
     * @return string
     */
    public function encodingPassword(User $user)
    {
        return $this->passwordEncoder->encodePassword(
            $user,
            $user->getPassword()
        );
    }

    /**
     * Find by email.
     *
     * @param $array
     * @return \App\Entity\User|null User entity
     */
    public function findByEmail($array)
    {
        return $this->userRepository->findOneBy($array);
    }
}
