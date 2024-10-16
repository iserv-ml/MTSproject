<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VisiteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VisiteRepository extends EntityRepository
{
    
    public function findAllAjax($start, $end, $sCol, $sdir, $search) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r.id, v.immatriculation, v.typeChassis, v.chassis, r.revisite, r.statut, p.nom, p.prenom,pi.numero as piste, ca.numero as caisse FROM AppBundle:Visite r LEFT JOIN r.vehicule v LEFT JOIN v.proprietaire p LEFT JOIN r.chaine c LEFT JOIN c.piste pi LEFT JOIN c.caisse ca'
                    . ' WHERE v.immatriculation like :search '
                    . ' ORDER BY '.$sCol.' '.$sdir)
            ->setParameter('search', '%'.$search.'%')
            ->setFirstResult($start)
            ->setMaxResults($end);
        $arrayAss = $qb->execute(null, \Doctrine\ORM\Query::HYDRATE_SCALAR);
        return $arrayAss;
    }
    
    public function findQuittancesAjax($start, $end, $sCol, $sdir, $search, $caisse) {
        $controle = ($caisse == 0) ? 'ca.id > :caisse ' : ' ca.id = :caisse ';
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r.id, v.immatriculation, r.revisite, p.nom, p.prenom, ca.numero as caisse, ca.ouvert FROM AppBundle:Visite r LEFT JOIN r.quittance q LEFT JOIN r.vehicule v LEFT JOIN v.proprietaire p LEFT JOIN r.chaine c LEFT JOIN c.caisse ca '
                    . ' WHERE r.statut <= 1 AND r.contreVisite = false AND (v.immatriculation like :search OR q.numero like :search) AND '.$controle
                    . ' ORDER BY '.$sCol.' '.$sdir)
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('caisse', $caisse)
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
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)')->leftJoin('c.vehicule', 'v')->where('v.immatriculation like :search')->setParameter('search', '%'.$search.'%');
        return  $qb->getQuery()->getSingleScalarResult();
    }
    
    public function countQuittanceRows($caisse) {
        $controle = ($caisse == 0) ? 'ca.id > :caisse ' : ' ca.id = :caisse ';
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c LEFT JOIN c.caisse ca '
                    . ' WHERE r.statut <= 1 AND r.contreVisite = false AND r.statut < 2 AND '.$controle)
               ->setParameter('caisse', $caisse);
        return  $qb->getSingleScalarResult();
    }
    
    public function countQuittanceRowsFiltre($caisse, $search) {
        $controle = ($caisse == 0) ? 'ca.id > :caisse ' : ' ca.id = :caisse ';
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c LEFT JOIN c.caisse ca LEFT JOIN r.vehicule v '
                    . ' WHERE r.statut <= 1 AND r.contreVisite = false AND r.statut < 2 AND v.immatriculation like :search AND '.$controle)
               ->setParameter('caisse', $caisse)->setParameter('search', '%'.$search.'%');
        return  $qb->getSingleScalarResult();
    }
    
    public function trouverParLibelle($libelle) {
        try{ 
            $result = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Carrosserie r WHERE r.libelle = :libelle'
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
    
    public function nbVisitesNonTerminees($chaine) {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c WHERE c.id = :chaine AND r.statut < 2'
            )->setParameter("chaine",$chaine)
            ->getSingleScalarResult(); 
        return $result; 
    }
    
    public function visiteParent($vehicule) {
        try{ 
            $result = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r LEFT JOIN r.vehicule v WHERE v.id = :vehicule AND r.statut = 3'
            )->setParameter("vehicule",$vehicule)
            ->getSingleResult();
       }catch (\Doctrine\ORM\NonUniqueResultException $ex) {
            $result = -1;
        }
        catch (\Doctrine\ORM\NoResultException $ex){
            $result = null;
        }
        
        return $result; 
    } 
    
    public function derniereVisite($vehicule, $encours = 0) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r LEFT JOIN r.vehicule v '
                    . ' WHERE v.id = :vehicule AND r.id <> :encours AND r.statut < 5'
                    . ' ORDER BY r.date DESC')
            ->setParameter('vehicule', $vehicule)
            ->setParameter('encours', $encours);
        $result = $qb->getResult();
        return  $result!= null ? $result[0] : null;
    }
    
    public function findControlesAjax($start, $end, $sCol, $sdir, $search, $piste) {
        $controle = ($piste == 0) ? 'pi.id > :piste ' : 'pi.id = :piste ';
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r.id, r.contreVisite, r.contreVisiteVisuelle, v.immatriculation, v.typeChassis, r.revisite, r.statut, p.nom, p.prenom,pi.numero as piste, v.id as vehicule FROM AppBundle:Visite r LEFT JOIN r.vehicule v LEFT JOIN v.proprietaire p LEFT JOIN r.chaine c LEFT JOIN c.piste pi '
                    . ' WHERE r.statut IN (1,2, 3) AND v.immatriculation like :search AND '.$controle
                    . ' ORDER BY '.$sCol.' '.$sdir)
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('piste', $piste)
            ->setFirstResult($start)
            ->setMaxResults($end);
        $arrayAss = $qb->execute(null, \Doctrine\ORM\Query::HYDRATE_SCALAR);
        return $arrayAss;
    }
    
    public function countControlesRows($piste) {
        $controle = ($piste == 0) ? 'pi.id > :piste ' : ' pi.id = :piste ';
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c LEFT JOIN c.piste pi '
                    . ' WHERE r.statut IN (1,2,3) AND '.$controle)
               ->setParameter('piste', $piste);
        return  $qb->getSingleScalarResult();
    }
    
    public function countControlesRowsFiltre($piste, $search) {
        $controle = ($piste == 0) ? 'pi.id > :piste ' : ' pi.id = :piste ';
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c LEFT JOIN c.piste pi '
                    . ' WHERE r.statut IN (1,2,3) AND v.immatriculation like :search AND '.$controle)
               ->setParameter('piste', $piste)->setParameter('search', '%'.$search.'%');
        return  $qb->getSingleScalarResult();
    }
    
    public function nbVisitesParStatut() {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT r.statut, count(r.id) FROM AppBundle:Visite r GROUP BY r.statut ORDER BY r.statut asc'
            )
            ->getResult();
        return $result; 
    }
    
    public function recupererEchecParPeriode($debut, $fin) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r '
                    . ' WHERE r.statut = 3 AND ( r.contreVisite IS NULL OR r.contreVisite = false) AND r.dateControle >= :debut AND r.dateControle <= :fin '
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin);
        return $qb->getResult();
    }
    
    public function recupererReussiteParPeriode($debut, $fin) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r '
                    . ' WHERE (r.statut = 2 OR r.statut = 4) AND ( r.contreVisite IS NULL OR r.contreVisite = false) AND r.dateControle >= :debut AND r.dateControle <= :fin '
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin);
        return $qb->getResult();
    }
    
    public function recupererParPeriode($debut, $fin) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r '
                    . ' WHERE r.statut IN (2,3) AND r.dateControle >= :debut AND r.dateControle <= :fin'
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin);
        return $qb->getResult();
    }
    public function recupererParPeriodeControlleur($debut, $fin, $controleur) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r '
                    . ' WHERE r.statut IN (2,3,4) AND r.dateControle >= :debut AND r.dateControle <= :fin AND r.controlleur = :controleur AND (r.contrevisiteCree IS NULL OR r.contrevisiteCree = false)'
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin)->setParameter('controleur', $controleur);
        return $qb->getResult();
    }
    
    public function recupererControleurPeriode($debut, $fin) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT r.controlleur FROM AppBundle:Visite r '
                    . ' WHERE r.statut IN (2,3,4) AND r.dateControle >= :debut AND r.dateControle <= :fin'
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin);
        
        return $qb->execute(null, \Doctrine\ORM\Query::HYDRATE_SCALAR);
    }
    
    public function annulerVisitesEnAttentes() {
        $qb = $this->getEntityManager()
            ->createQuery("UPDATE AppBundle:Visite r SET r.statut = 5, r.modifierPar = 'fermeture_du_centre', r.dateModification = CURRENT_TIMESTAMP() WHERE r.statut = 0");
        return  $qb->execute();
    }
    
    /**public function annulerVisitesExpirees() {
        $qb = $this->getEntityManager()
            ->createQuery("UPDATE AppBundle:Visite r SET r.statut = 5, r.modifierPar = 'ouverture_du_centre', r.dateModification = CURRENT_TIMESTAMP() WHERE r.statut =1 ");
        $qb->execute();
    }**/
    
    public function recupererVisitesEncours() {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r '
                    . ' WHERE r.statut = 1'
                    );
        return $qb->getResult();
    }
    
    public function annuler($id) {
        $qb = $this->getEntityManager()
            ->createQuery("UPDATE AppBundle:Visite r SET r.statut = 5, r.modifierPar = 'overture_du_centre', r.dateModification = CURRENT_TIMESTAMP() WHERE r.id = :id")->setParameter('id', \intval($id));
        return  $qb->execute();
    }
    
    public function nbVisitesCaisse($caisse) {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c LEFT JOIN c.caisse ca WHERE ca.id = :caisse AND r.statut = 0'
            )->setParameter("caisse",$caisse)
            ->getSingleScalarResult(); 
        return $result; 
    }
    
    public function nbVisitesPiste($piste) {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.chaine c LEFT JOIN c.piste ca WHERE ca.id = :piste AND r.statut = 1'
            )->setParameter("piste",$piste)
            ->getSingleScalarResult(); 
        return $result; 
    }
    
    public function findDelivranceAjax($start, $end, $sCol, $sdir, $search) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r.id, v.immatriculation, v.typeChassis, v.chassis, r.revisite, r.statut, p.nom, p.prenom,pi.numero as piste, ca.numero as caisse FROM AppBundle:Visite r LEFT JOIN r.vehicule v LEFT JOIN v.proprietaire p LEFT JOIN r.chaine c LEFT JOIN c.piste pi LEFT JOIN c.caisse ca'
                    . ' WHERE r.statut IN (2,3,4) AND v.immatriculation like :search '
                    . ' ORDER BY '.$sCol.' '.$sdir)
            ->setParameter('search', '%'.$search.'%')
            ->setFirstResult($start)
            ->setMaxResults($end);
        $arrayAss = $qb->execute(null, \Doctrine\ORM\Query::HYDRATE_SCALAR);
        return $arrayAss;
    }
    
    public function countDelivrancesRows() {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r '
                    . ' WHERE r.statut IN (2,3,4) ');
        return  $qb->getSingleScalarResult();
    }
    
    public function countDelivrancesRowsFiltre($search) {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT count(r.id) FROM AppBundle:Visite r LEFT JOIN r.vehicule v '
                    . ' WHERE r.statut IN (2,3,4) AND v.immatriculation like :search ')
               ->setParameter('search', '%'.$search.'%');
        return  $qb->getSingleScalarResult();
    }
    
    public function findControlesAjaxAlleger($immatriculation, $piste) {
        try{
            $controle = ($piste == 0) ? 'pi.numero > :piste ' : 'pi.numero = :piste ';
            $conn = $this->getEntityManager()->getConnection();
            $sql = 'SELECT r.id, r.contre_visite, r.contre_visite_visuelle,r.immatriculation_v, v.immatriculation, v.type_chassis, v.chassis, r.revisite, r.statut, p.nom, p.prenom,pi.numero as piste, v.id as vehicule, g.code as genre FROM visite r LEFT JOIN vehicule v ON r.vehicule_id = v.id LEFT JOIN proprietaire p ON v.proprietaire_id = p.id LEFT JOIN chaine c on r.chaine_id = c.id LEFT JOIN piste pi on c.piste_id = pi.id LEFT JOIN typevehicule tp on v.type_vehicule_id = tp.id LEFT JOIN genre g on tp.genre_id = g.id'
                        . ' WHERE r.statut IN (1,2, 3) AND v.immatriculation = :immatriculation AND '.$controle.' ORDER BY r.dateControle DESC LIMIT 1';
            
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('immatriculation' => $immatriculation, 'piste' => $piste));
            $result = $stmt->fetchAll();  
             
        }catch (\Exception $ex) {
            $result = null;
        }
        return $result; 
    }
    
    public function findDelivranceAjaxAlleger($immatriculation) {
        try{
            $conn = $this->getEntityManager()->getConnection();
            $sql = 'SELECT r.id, r.contre_visite, r.contre_visite_visuelle, r.dateControle, v.immatriculation, v.type_chassis, v.chassis, r.revisite, r.statut, p.nom, p.prenom,pi.numero as piste, ca.numero as caisse, g.code as genre FROM visite r LEFT JOIN vehicule v ON r.vehicule_id = v.id LEFT JOIN proprietaire p ON v.proprietaire_id = p.id LEFT JOIN chaine c ON r.chaine_id = c.id LEFT JOIN piste pi ON c.piste_id = pi.id LEFT JOIN caisse ca ON c.caisse_id = ca.id LEFT JOIN typevehicule tp on v.type_vehicule_id = tp.id LEFT JOIN genre g on tp.genre_id = g.id'
                        . ' WHERE r.statut IN (2,3,4) AND v.immatriculation = :immatriculation ORDER BY r.dateControle DESC LIMIT 1';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('immatriculation' => $immatriculation));
            $result = $stmt->fetchAll();  
        }catch (\Exception $ex) {
            $result = null;
        }
        return $result; 
    }
    
    public function recupererToutParPeriode($debut, $fin, $immatriculation="", $controleur="") {
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r'
                    . ' WHERE r.dateControle >= :debut AND r.dateControle <= :fin AND r.immatriculation_v LIKE :immatriculation AND r.controlleur = :controleur'
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin)->setParameter('controleur', $controleur)->setParameter('immatriculation', "%".$immatriculation."%");
        return $qb->getResult();
    }
    
    public function recupererToutParPeriodeFiltre($debut, $fin, $immatriculation="", $controleur="", $type=0) {
        switch ($type){
            case 1: $statut = ' AND r.statut IN (2,4)';break;
            case 2: $statut = ' AND r.statut = 3';break;
            case 3: $statut = ' AND r.statut IN (0,1)';break;
            case 4: $statut = ' AND r.statut =5';break;
            default: $statut= "";
        }
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r'
                    . ' WHERE r.dateControle >= :debut AND r.dateControle <= :fin AND r.immatriculation_v LIKE :immatriculation AND r.controlleur LIKE :controleur AND (r.contrevisiteCree IS NULL OR r.contrevisiteCree = false)'
                    .$statut
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin)->setParameter('controleur', "%".$controleur."%")->setParameter('immatriculation', "%".$immatriculation."%");
        return $qb->getResult();
    }
    
    public function recupererToutFiltre($debut, $fin, $immatriculation="", $controleur="", $caissier="", $type=0) {
        switch ($type){
            case 1: $statut = ' AND r.statut IN (2,4)';break;
            case 2: $statut = ' AND r.statut = 3';break;
            case 3: $statut = ' AND r.statut IN (0,1)';break;
            case 4: $statut = ' AND r.statut =5';break;
            default: $statut= "AND r.statut <>5";
        }
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r LEFT JOIN r.quittance q'
                    . ' WHERE r.dateControle >= :debut AND r.dateControle <= :fin AND r.immatriculation_v LIKE :immatriculation AND r.controlleur LIKE :controleur AND q.nomCaissier LIKE :caissier AND (r.contrevisiteCree IS NULL OR r.contrevisiteCree = false)'
                    .$statut
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin)->setParameter('controleur', "%".$controleur."%")->setParameter('caissier', "%".$caissier."%")->setParameter('immatriculation', "%".$immatriculation."%");
        return $qb->getResult();
    }
    
    public function recupererGratuiteParPeriodeFiltre($debut, $fin, $immatriculation="", $controleur="", $type=0) {
        switch ($type){
            case 1: $statut = ' AND r.statut IN (2,4)';break;
            case 2: $statut = ' AND r.statut = 3';break;
            case 3: $statut = ' AND r.statut IN (0,1)';break;
            case 4: $statut = ' AND r.statut =5';break;
            default: $statut= "";
        }
        $qb = $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AppBundle:Visite r'
                    . ' WHERE r.gratuite = true AND r.dateControle >= :debut AND r.dateControle <= :fin AND r.immatriculation_v LIKE :immatriculation AND r.controlleur LIKE :controleur AND (r.contrevisiteCree IS NULL OR r.contrevisiteCree = false)'
                    .$statut
                    )
           ->setParameter('debut', $debut)->setParameter('fin', $fin)->setParameter('controleur', "%".$controleur."%")->setParameter('immatriculation', "%".$immatriculation."%");
        return $qb->getResult();
    }
}
