<?php
/**
 * User data service.
 */

namespace App\Service;

use App\Entity\UserData;
use App\Repository\UserDataRepository;

/**
 * Class UserDataService.
 */
class UserDataService
{
    /**
     * User data repository.
     *
     * @var \App\Repository\UserDataRepository
     */
    private $userDataRepository;

    /**
     * UserDataService constructor.
     *
     * @param \App\Repository\UserDataRepository $userDataRepository User data repository
     */
    public function __construct(UserDataRepository $userDataRepository)
    {
        $this->userDataRepository = $userDataRepository;
    }

    /**
     * Save user data.
     *
     * @param \App\Entity\UserData $userData User data entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserData $userData):void
    {
        $this->userDataRepository->save($userData);
    }
}