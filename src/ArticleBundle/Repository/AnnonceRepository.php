<?php

namespace ArticleBundle\Repository;

/**
 * AnnonceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnnonceRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ArticleBundle:Annonce p
                WHERE p.nomArticle LIKE :str'

            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }
    public function findDescByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ArticleBundle:Annonce p
                WHERE p.description LIKE :str'

            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }
    public function findDateByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ArticleBundle:Annonce p
                WHERE p.date LIKE :str'

            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }
    public function Finddql($id){
        $qb=$this->getEntityManager()
            ->createQuery("select j from ArticleBundle:Annonce j where j.ClientId=?1 ")
            ->setParameters(array(1=>$id));
        return $qb->getResult();
    }
}