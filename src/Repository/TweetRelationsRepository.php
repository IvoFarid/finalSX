<?php

namespace App\Repository;

use App\Entity\TweetRelations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TweetRelations>
 *
 * @method TweetRelations|null find($id, $lockMode = null, $lockVersion = null)
 * @method TweetRelations|null findOneBy(array $criteria, array $orderBy = null)
 * @method TweetRelations[]    findAll()
 * @method TweetRelations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetRelationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TweetRelations::class);
    }

    public function findIfExistsLike($tweetId, $userId): ?TweetRelations
   {
      return $this->createQueryBuilder('relation')
          ->andWhere('relation.tweet = :tweetId')
          ->andWhere('relation.user = :userId')
          ->andWhere('relation.actionType = 1')
          ->setParameter('tweetId', $tweetId)
          ->setParameter('userId', $userId)
          ->getQuery()
          ->getOneOrNullResult();
   }

   public function findIfExistsRt($tweetId, $userId): ?TweetRelations
   {
      return $this->createQueryBuilder('relation')
          ->andWhere('relation.tweet = :tweetId')
          ->andWhere('relation.user = :userId')
          ->andWhere('relation.actionType = 2')
          ->setParameter('tweetId', $tweetId)
          ->setParameter('userId', $userId)
          ->getQuery()
          ->getOneOrNullResult();
   }

   public function findIfExistsSave($tweetId, $userId): ?TweetRelations
   {
      return $this->createQueryBuilder('relation')
          ->andWhere('relation.tweet = :tweetId')
          ->andWhere('relation.user = :userId')
          ->andWhere('relation.actionType = 4')
          ->setParameter('tweetId', $tweetId)
          ->setParameter('userId', $userId)
          ->getQuery()
          ->getOneOrNullResult();
   }

   /**
    * @return Tweet[] Returns an array of User objects
    */
  public function findLikedByUser($user): array
  {
    $qb = $this->createQueryBuilder('r')
    ->select('tweet')
    ->andWhere('r.actionType = 1')
    ->andWhere('r.user = :user')
    ->setParameter('user', $user)
    ->leftJoin('App\Entity\Tweet', 'tweet', \Doctrine\ORM\Query\Expr\Join::WITH, 'r.tweet = tweet.id')
    ->getQuery()
    ->getResult();

    return $qb;
  }
  
  //  public function findLikeRelation($tweetId, $userId): ?TweetRelations
  //  {
  //     return $this->createQueryBuilder('relation')
  //         ->andWhere('relation.tweet = :tweetId')
  //         ->andWhere('relation.user = :userId')
  //         ->andWhere('relation.actionType = 2')
  //         ->setParameter('tweetId', $tweetId)
  //         ->setParameter('userId', $userId)
  //         ->getQuery()
  //         ->getOne();
  //  }

  //     public function findLikeRelation($tweetId, $userId): ?TweetRelations
  //  {
  //     return $this->createQueryBuilder('relation')
  //         ->andWhere('relation.tweet = :tweetId')
  //         ->andWhere('relation.user = :userId')
  //         ->setParameter('tweetId', $tweetId)
  //         ->setParameter('userId', $userId)
  //         ->getQuery()
  //         ->getOne();
  //  }
//    /**
//     * @return TweetRelations[] Returns an array of TweetRelations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TweetRelations
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
