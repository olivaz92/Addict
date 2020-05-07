<?php

namespace App\Repository;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;

/**
 * @method Actualite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actualite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actualite[]    findAll()
 * @method Actualite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actualite::class);
    }

    /**
     * Fonction avec requete sql classique pour afficher les commentaires dans l'ordre desc
     */
    public function nbreCommentaire()
    {
        $rawSql = "select count(c.id) as nbrec from commentaire c
                    inner join actualite a 
                    on c.id_actualite_id = a.id 
                    ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute();
        $nbre= $stmt->fetch();
        return $nbre['nbrec'];

    }

    /**
     * Fonction classique pour afficher les commentaires dans l'ordre desc
     */
    public function getCustomActualite()
    {
        return $this->createQueryBuilder('e')
            ->select( 'e ' )
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fonction classique pour afficher les commentaires dans l'ordre desc
     */
    public function getCustomCommentaire($value)
    {
        return $this->createQueryBuilder('a')
            ->join('c.commentaire', 'c')
            ->where ($value = c.id_actualite_id)
            ->setParameter('Actualite', $value)
            ->orderBy('a.date', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

///**
//     * Fonction classique pour afficher les commentaires dans l'ordre desc
//     */
//    public function getCustomCommentaire()
//    {
//        return $this->createQueryBuilder('e')
//            ->select( 'e.commentaires' )
//            ->orderBy('e.date', 'DESC')
//            ->setMaxResults(20)
//            ->getQuery()
//            ->getResult();
//    }
//



    public function findOneBySomeField(): ?Actualite
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    // /**
    //  * @return Actualite[] Returns an array of Actualite objects
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


    /*
    public function findOneBySomeField($value): ?Actualite
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
