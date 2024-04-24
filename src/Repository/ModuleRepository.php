<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 *
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findModuleNotUse($id) {
        
        // em = entityManager
        $em = $this->getEntityManager();

        // sub = SubQuery (Sous requête DQL)
        $sub = $em->createQueryBuilder();

        // QueryBuilder 
        $qb = $sub;

        $qb->select('m')
            ->from('App\Entity\Module', 'm')
            ->leftJoin('m.programmes', 'mp')
            ->where('mp.session = :id');

        $sub = $em->createQueryBuilder();

        $sub->select('mt')
            ->from('App\Entity\Module', 'mt')
            ->where($sub->expr()->notIn('mt.id', $qb->getDQL()))

            ->setParameter('id', $id);

            $query = $sub->getQuery();
            return $query->getResult();
    }

    public function findByWord($key) {
        $em = $this->getEntityManager();

        // sub = SubQuery (Sous requête DQL)
        $sub = $em->createQueryBuilder();

        // QueryBuilder 
        $qb = $sub;

        $qb->select('m')
            ->from('App\Entity\Module', 'm')
            ->where('m.name LIKE :key')
            ->setParameter('key', '%'.$key.'%');

            $query = $sub->getQuery();
            return $query->getResult();
    }

//    /**
//     * @return Module[] Returns an array of Module objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Module
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
