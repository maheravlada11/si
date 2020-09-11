<?php
/**
 * Comment Repository.
 */

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Comment Repository.
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * CommentRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Save comment.
     *
     * @param Comment $comment Comment entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush($comment);
    }

    /**
     * Delete comment.
     *
     * @param Comment $comment Comment entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Comment $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush($comment);
    }
}


