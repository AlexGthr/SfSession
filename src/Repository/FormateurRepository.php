<?php

namespace App\Repository;

use App\Entity\Formateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formateur>
 *
 * @method Formateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formateur[]    findAll()
 * @method Formateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formateur::class);
    }

    public function findByWord($key) {
        $em = $this->getEntityManager();

        // sub = SubQuery (Sous requête DQL)
        $sub = $em->createQueryBuilder();

        // QueryBuilder 
        $qb = $sub;

        $qb->select('m')
            ->from('App\Entity\Formateur', 'm')
            ->where('m.name LIKE :key')->orWhere('m.lastName LIKE :key')
            ->setParameter('key', '%'.$key.'%');

            $query = $sub->getQuery();
            return $query->getResult();
    }

    //    /**
    //     * @return Formateur[] Returns an array of Formateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Formateur
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
