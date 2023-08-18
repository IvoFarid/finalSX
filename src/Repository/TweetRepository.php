<?php

namespace App\Repository;

use App\Entity\Tweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tweet>
 *
 * @method Tweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tweet[]    findAll()
 * @method Tweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tweet::class);
    }

    public function add(Tweet $tweet, bool $flush = false): void {
      $this->getEntityManager()->persist($tweet);
      if($flush){
        $this->getEntityManager()->flush();
      }
    }
   /**
    * @return Tweet[] Returns an array of Tweet objects
    */
   public function findLatests(): array
   {
       return $this->createQueryBuilder('t')
           ->orderBy('t.createdAt', 'DESC')
          //  ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Tweet[] Returns an array of Tweet objects
    */
   public function findLatestsByUser($profileuser): array
   {
       return $this->createQueryBuilder('t')
           ->andWhere('t.author = :profileuser')
           ->setParameter('profileuser', $profileuser)
           ->orderBy('t.createdAt', 'DESC')
          //  ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Tweet
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
