<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findStagaireNonInscrit($id) {
        
        // em = entityManager
        $em = $this->getEntityManager();

        // sub = SubQuery (Sous requête DQL)
        $sub = $em->createQueryBuilder();

        // QueryBuilder 
        $qb = $sub;

        $qb->select('s')
            ->from('App\Entity\Student', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();

        $sub->select('st')
            ->from('App\Entity\Student', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))

            ->setParameter('id', $id)
            ->orderBy('st.name');

            $query = $sub->getQuery();
            return $query->getResult();
    }

    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
