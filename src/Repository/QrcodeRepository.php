<?php

namespace App\Repository;

use App\Entity\Qrcode;
use App\Entity\Tag;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Qrcode>
 */
class QrcodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Qrcode::class);
    }
    public function findLatest(?User $author): ?Qrcode
    {
        if ($author === null) {
            return null;
        }

        return $this->createQueryBuilder('q')
            ->andWhere('q.author = :val')
            ->setParameter('val', $author)
            ->orderBy('q.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }


    //    /**
    //     * @return Qrcode[] Returns an array of Qrcode objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Qrcode
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
