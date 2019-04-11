<?php

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ad::class);
    }
    public function search($zip)
    {
        $dql = '
            SELECT ad 
            FROM App\Entity\Ad ad 
            WHERE ad.zip LIKE :zip
            ORDER BY ad.dateCreated ASC
        ';

        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameter(
                'zip',
                '%'.$zip.'%'
            )
            ->getResult();

    }
    public function searchCategory($name)
    {
        $req = $this->createQueryBuilder('ad')->select('ad')
            ->innerJoin('ad.category', 'c', 'WITH', 'c.name = :name')
            ->setParameter('name', '%'.$name.'%');

        return $req->getQuery()->getResult();


    }

    public function findAllJoinCategory()
    {
        $dql = '
            SELECT a, c
            FROM App\Entity\Ad a
            LEFT JOIN a.category c 
        ';

        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->getResult();

    }


    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
