<?php

namespace AppBundle\Utilities;

use AppBundle\Entity\Vehicule;
use AppBundle\Entity\Visite;



/**
 * Utilities
 *
 * 
 */
class Utilities
{
   public static function evaluerDemandeVisite($derniereVisite){
       if($derniereVisite){
           switch($derniereVisite->getStatut()){
               case 0 : case 1 : return 1;
               case 2 : case 4 : return 2;
               case 3 : return 3;
           }
       }else{
           return 0;
       }
       if(!$derniereVisite || $derniereVisite->getStatut() == 2 || $derniereVisite->getStatut() == 4){
            //il faudra tenir compte de la date de la dernière visite
            if($derniereVisite){
                verifierSiDateVisite();
            }
            $visiteParent = null;
        }elseif($derniereVisite->getStatut() == 3){
            $visiteParent = $derniereVisite;
        }else{
            $this->get('session')->getFlashBag()->add('notice', 'Visite déjà en cours.');
            return $this->render('visite/visite.html.twig', array(
            'visite' => $derniereVisite,
            ));
        }
   }
   
    public static function trouverChaineOptimale($chaines, $em){
        $min = 1000000000000000000000000;
        $chaineOptimale = null;
        $i=0;
        if(count($chaines)>0){
            foreach($chaines as $chaine){
                if($i == 0){
                    $chaineOptimale = $chaine;$i++;
                }
                $nb = $em->getRepository('AppBundle:Visite')->nbVisitesNonTerminees($chaine->getId());
                if($min>$nb){
                    $min = $nb;
                    $chaineOptimale = $chaine;
                }
            }
        }
        return $chaineOptimale;
   }

}
