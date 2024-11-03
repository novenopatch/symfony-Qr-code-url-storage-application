<?php

namespace App\Repository;

use App\Entity\Qrcode;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
    public function paginate($query, int $page = 1, int $limit = 10): array
    {
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        return [
            'items' => iterator_to_array($paginator),
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit,
        ];
    }
    public function findPaginated(int $page = 1, int $limit = 10, ?User $user = null): array
    {
        $queryBuilder = $this->filterByUser($user);

        $queryBuilder->orderBy('q.id', 'DESC');

        $query = $queryBuilder->getQuery();

        //dd($this->paginate($query, $page, $limit));
        return $this->paginate($query, $page, $limit);
    }


    public function countAll( ?User $user = null): int
    {

        return (int) $this->filterByUser($user)
            ->select('COUNT(q.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    private function filterByUser(?User $user = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('q');


        if ($user) {
            $queryBuilder->andWhere('q.author = :val')
                ->setParameter('val', $user);
        } else {
            $queryBuilder->andWhere('q.author IS NULL');
        }
        return $queryBuilder;
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
