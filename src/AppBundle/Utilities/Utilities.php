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
    const digit = 7;
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
                $nb = $em->getRepository('AppBundle:Visite')->nbVisitesCaisse($chaine->getCaisse()->getId())+$em->getRepository('AppBundle:Visite')->nbVisitesPiste($chaine->getPiste()->getId());
                if($min>$nb){
                    $min = $nb;
                    $chaineOptimale = $chaine;
                }
            }
        }
        return $chaineOptimale;
   }
   
   public static function formaterSerie($serie){
        while(strlen($serie) < self::digit){
            $serie = "0".$serie;
        }
        return $serie;
   }

}
