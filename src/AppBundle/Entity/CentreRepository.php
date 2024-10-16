<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CentreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CentreRepository extends EntityRepository
{
    
    public function recuperer() {
        try{ 
            $qb = $this->getEntityManager()->createQuery('SELECT r FROM AppBundle:Centre r ');
            $resultat = $qb->getSingleResult();
        }catch (\Doctrine\ORM\NonUniqueResultException $ex) {
            $resultat = null;
        }
        catch (\Doctrine\ORM\NoResultException $ex){
            $resultat = null;
        }
        return $resultat;
    }
    
     public function countRows() {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        return  $qb->getQuery()->getSingleScalarResult();
     }
    
     public function trouverParLibelle($libelle) {
       try{ 
         $result = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Caisse r WHERE r.numero = :libelle'
            )->setParameter("libelle",$libelle)
            ->getSingleResult();
       }catch (\Doctrine\ORM\NonUniqueResultException $ex) {
            $result = null;
        }
        catch (\Doctrine\ORM\NoResultException $ex){
            $result = null;
        }
        
        return $result; 
    }   
    
    public function recupererEtat() {
        $qb = $this->getEntityManager()
            ->createQuery('SELECT r.* FROM AppBundle:EtatJournalier r WHERE (r.synchro IS NULL OR r.synchro = 0)')
            ->setMaxResults(10);
        $arrayAss = $qb->execute(null, \Doctrine\ORM\Query::HYDRATE_SCALAR);
        return $arrayAss;
    }
    
    public function marquerEtatTraite($sql) {
        $qb = $this->getEntityManager()
            ->createQuery($sql);
        $arrayAss = $qb->execute();
        return $arrayAss;
    }
}
