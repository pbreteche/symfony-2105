<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[]
     */
    public function findLatest()
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT post FROM '.Post::class.' post ORDER BY post.createdAt DESC'
            )->setMaxResults(20)
            ->getResult()
        ;
    }

    /**
     * @return Post[]
     */
    public function findLatest2()
    {
        return $this->createQueryBuilder('post')
            ->orderBy('post.createdAt', 'DESC')
            ->getQuery()
            ->setMaxResults(20)
            ->getResult()
        ;
    }

    public function findByAuthor(Author $author)
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.writtenBy = :author')
            ->getQuery()
            ->setParameter('author', $author)
            ->getResult();
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
