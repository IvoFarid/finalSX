<?php

namespace App\Repository;

use App\Entity\UserRelations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Tweet;
/**
 * @extends ServiceEntityRepository<UserRelations>
 *
 * @method UserRelations|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRelations|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRelations[]    findAll()
 * @method UserRelations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRelationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRelations::class);
    }

//    /**
//     * @return UserRelations[] Returns an array of UserRelations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findIfExistsRelation($follower, $following): ?UserRelations
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.follower = :follower')
           ->andWhere('u.following = :following')
           ->setParameter('follower', $follower)
           ->setParameter('following', $following)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   /**
    * @return User[] Returns an array of User objects
    */
   public function findFollowedUsers($appuser): array
   {
    $qb = $this->createQueryBuilder('r')
    ->select('u')
    ->andWhere('r.follower = :appuser')
    ->setParameter('appuser', $appuser)
    ->leftJoin('App\Entity\User', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'r.following = u.id')
    // ->leftJoin('App\Entity\Tweet', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.author = r.following')
    ->getQuery()
    ->getResult();

    return $qb;
   }

   // RETURN TWEETS BASED ON LIKES RELATION TYPE.

   
    /**
    * @return User[] Returns an array of User objects
    */
   public function findFollowingUsers($appuser): array
   {
    $qb = $this->createQueryBuilder('r')
    ->select('u')
    ->andWhere('r.following = :appuser')
    ->setParameter('appuser', $appuser)
    ->leftJoin('App\Entity\User', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'r.follower = u.id')
    ->getQuery()
    ->getResult();

    return $qb;
   }
}
