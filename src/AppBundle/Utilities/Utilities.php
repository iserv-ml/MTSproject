<?php

namespace AppBundle\Utilities;

use AppBundle\Entity\Facture;
use AppBundle\Entity\Affaire;



/**
 * Utilities
 *
 * 
 */
class Utilities
{
   static function createFacture(Affaire $affaire, $type, $plein = false) {
       $facture = null;
       switch($type){
           case "VENTE": $facture = Utilities::createFactureVente($affaire);break;
           case "LONGUE DUREE": $facture = Utilities::createFactureLocationLongue($affaire,$plein);break;
           case "JOURNALIERE": $facture = Utilities::createFactureLocationCourte($affaire);break;
       }
       return $facture;
   }
   
   static function createFactureVente(Affaire $affaire) {
       $facture = new Facture();
       $facture->setStatut("OUVERTE");
       $facture->setMontant($affaire->getMontant());
       $facture->setPartProprio($affaire->getMontant()*$affaire->getBien()->getPartProprio()/100);
       return $facture;
   }
   
    static function createFactureLocationLongue(Affaire $affaire, $plein) {
        $montant = ($plein) ? $affaire->getLoyer() : Utilities::prorater($affaire->getDateDebut(), $affaire->getLoyer());
        $facture = new Facture();
        $facture->setStatut("OUVERTE");
        $facture->setMontant($montant);
        $facture->setPartProprio($montant*$affaire->getBien()->getPartProprio()/100);
        return $facture;
    }
   
    static function prorater($dateDebut, $montant){
        $finmois = new \DateTime(date("Y-m-t", $dateDebut->getTimestamp()));
        $debutmois = new \DateTime(date("Y-m-01", $dateDebut->getTimestamp()));
        $jours = intval($dateDebut->diff($finmois)->format('%a'))+1;
        $total = intval($debutmois->diff($finmois)->format('%a'))+1;
        return $montant*$jours/$total;
    }
   
    static function createFactureLocationCourte(Affaire $affaire) {
        $debut = new \DateTime($affaire->getDateDebut());
        $jours = intval($debut->diff(new \DateTime($affaire->getDateFin()))->format('%a'))+1;
        $montant = $affaire->getLoyer()*$jours;
        $facture = new Facture();
        $facture->setStatut("OUVERTE");
        $facture->setMontant($montant);
        $facture->setPartProprio($montant*$affaire->getBien()->getPartProprio()/100);
        return $facture;
   }

}
