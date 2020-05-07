<?php

namespace App\Repository;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    // /**
    //  * @return Commentaire[] Returns an array of Commentaire objects
    //  */

    /**
     * @param Actualite $id_actualite
     * @return mixed
     */
    protected function getCommentsForActualite(Actualite $id_actualite)
    {
        return $this->createQueryBuilder('c')
            ->select('c.commentaire')
            ->orderBy('c.date', 'DESC')
            ->setParameter('id_actualite', $id_actualite)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    /*
    public function findOneBySomeField($value): ?Commentaire
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Fonction avec requete sql classique pour afficher les commentaires dans l'ordre desc
     */
    public function nombreCommentaire()
    {
        $rawSql = "select count(*) from commentaire c
                   left join actualite a
                   on a.id = c.id_actualite_id
                  ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);

        return $stmt->fetch();
    }
}
