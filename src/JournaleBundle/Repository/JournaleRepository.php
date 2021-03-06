<?php

namespace JournaleBundle\Repository;

use JournaleBundle\Entity\Journale;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * JournaleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JournaleRepository extends \Doctrine\ORM\EntityRepository
{
    public function Finddql($id){
        $qb=$this->getEntityManager()
            ->createQuery("select j from JournaleBundle:Journale j where j.idchasseur=?1 ")
            ->setParameters(array(1=>$id));
        return $qb->getResult();
    }
    public function configure()
    {
        $this->setValidators(array(
            'nbchasse'    => new sfValidatorInteger(array('required' => true)), array(
                'required'   => 'Le champ message est obligatoire.',

            ))
        );
    }
    public function nbuser()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
          ->select(' COUNT(DISTINCT u.idchasseur)')
            ->from(Journale::class, 'u');
        $query = $queryBuilder->getQuery();
       return $query->getSingleScalarResult();
    }
    public function nbchasse($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select(' SUM(u.nbchasse)')
            ->from(Journale::class, 'u')
            ->where('u.idchasseur=?1')
            ->setParameters(array(1=>$id));
        $query = $queryBuilder->getQuery();
        return $query->getSingleScalarResult();
    }

    public function findbynbrechassePeriode($username , $datedebut,$datefin ){
        $dql_query = $this->_em->createQuery("
    SELECT SUM(j.nbchasse) as nbrechasse    FROM JournaleBundle:Journale j 
    WHERE 
        j.evenement IS NOT NULL and 
      j.idchasseur LIKE '".$username."' AND j.date  BETWEEN '".$datedebut."' AND '".$datefin."'"

        );
        $chasses = $dql_query->getResult();
        return $chasses;

    }
    public function findbynbrechassePeriodeAll($datedebut,$datefin,$typeevent){
         $dql_query = $this->_em->createQuery("
    SELECT j.idchasseur ,SUM(j.nbchasse) as nbrechasse  FROM JournaleBundle:Journale j
        WHERE 
         
        j.date  BETWEEN '".$datedebut."' AND '".$datefin."'
          GROUP BY j.idchasseur  order by nbrechasse DESC"
        );
        $chasses = $dql_query->getResult();
        return $chasses;

    }

}
