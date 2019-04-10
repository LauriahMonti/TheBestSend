<?php

namespace App\Repository;

use App\Entity\UserFavorites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserFavorites|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFavorites|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFavorites[]    findAll()
 * @method UserFavorites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFavoritesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFavorites::class);
    }
    /*public function addFavorite()
    {

    }
    */
    public function showFavorite($id)
    {

        $req = $this->createQueryBuilder('uf')->select('uf')->addSelect('annonce')
            ->innerJoin('uf.user', 'user', 'WITH', 'user.id = :id')
            ->innerJoin('uf.annonce', 'annonce')
            ->setParameter('id', $id);

        return $req->getQuery()->getResult();


        /*$dql = '
        SELECT ad
        FROM App\Entity\Ad ad
        INNER JOIN ad.userFavorites uf
        WHERE uf.user.id = :id
        ';
        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameter(
                'id',
                $id)
            ->getResult();*/

    }

    // /**
    //  * @return UserFavorites[] Returns an array of UserFavorites objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserFavorites
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
