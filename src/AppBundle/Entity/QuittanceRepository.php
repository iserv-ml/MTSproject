<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * QuittanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuittanceRepository extends EntityRepository
{
    
    public function findAllAjax($start, $end, $sCol, $sdir, $search) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r.id, r.numero, r.montantVisite, r.tva, r.timbre, r.anaser, vh.immatriculation, v.statut, p.nom, p.prenom, ca.numero as caisse FROM AppBundle:Quittance r LEFT JOIN r.visite v LEFT JOIN v.vehicule vh LEFT JOIN vh.proprietaire p LEFT JOIN v.chaine c LEFT JOIN c.caisse ca'
                    . ' WHERE r.numero like :search or vh.immatriculation like :search'
                    . ' ORDER BY '.$sCol.' '.$sdir)
            ->setParameter('search', '%'.$search.'%')
            ->setFirstResult($start)
            ->setMaxResults($end);
        $arrayAss = $qb->execute(null, \Doctrine\ORM\Query::HYDRATE_SCALAR);
        return $arrayAss;
    }
    
    public function countRows() {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        return  $qb->getQuery()->getSingleScalarResult();
    }
    
    public function countRowsFiltre($search) {
        $qb = $this->createQueryBuilder('r');
        $qb->select('count(r.id)')->leftJoin('r.visite', 'v')->leftJoin('v.vehicule', 'vh')->where('r.numero like :search or vh.immatriculation like :search')->setParameter('search', '%'.$search.'%');
        return  $qb->getQuery()->getSingleScalarResult();
    }
    
     public function trouverQuittanceParVisite($visite) {
       try{ 
         $result = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Quittance r LEFT JOIN r.visite v WHERE v.id = :visite '
            )->setParameter("visite",$visite)
            ->getSingleResult();
       }catch (\Doctrine\ORM\NonUniqueResultException $ex) {
            $result = null;
        }
        catch (\Doctrine\ORM\NoResultException $ex){
            $result = null;
        }
        
        return $result; 
    } 
    
    public function recupererEncaisserParPeriode($debut, $fin) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Quittance r '
                    . ' WHERE r.paye =:paye AND r.rembourse =:rembourse AND r.dateEncaissement >= :debut AND r.dateEncaissement <= :fin '
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin)->setParameter('paye', true)->setParameter('rembourse', false);
        return $qb->getResult();
    }
}
