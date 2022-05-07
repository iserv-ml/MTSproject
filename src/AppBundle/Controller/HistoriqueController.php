<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\Historique;

/**
 * Historique controller.
 *
 * @Route("admin/gestion/centre/historiques")
 */
class HistoriqueController extends Controller
{
    /**
     * @Route("/", name="admin_gestion_centre_historique_index")
     */
    public function indexAction(Request $request)
    {
        $logs = null;
        $em = $this->getDoctrine()->getManager();
        $immatriculation = \trim($request->get('immatriculation', ''));
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
        $vehicule =  $em->getRepository('AppBundle:Vehicule')->trouverParImmatriculation($immatriculation);
        if($vehicule){
            $logs = $repo->getLogEntries($vehicule);
        }
        return $this->render('historique/index.html.twig', array('vehicule'=>$vehicule, 'logs'=>$logs,'immatriculation'=>$immatriculation));
    }
    
    /**
     * @Route("/quittance", name="admin_gestion_centre_historique_quittance")
     */
    public function quittanceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $immatriculation = \trim($request->get('immatriculation', ''));
        $quittances = $em->getRepository('AppBundle:EtatJournalier')->trouverParImmatriculation($immatriculation);
        return $this->render('historique/quittance.html.twig', array('immatriculation'=>$immatriculation, 'quittances'=>$quittances));
    }
    
    /**
     * @Route("/initialiser", name="admin_gestion_centre_historique_initialiser")
     */
    public function initialiserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
        $vehicules =  $em->getRepository('AppBundle:Vehicule')->findAll();
        if($vehicules != null){
            foreach ($vehicules as $vehicule){
                $logs = $repo->getLogEntries($vehicule);
                if($logs == null){
                    $data = 'a:6:{s:7:"chassis";s:'.strlen($vehicule->getChassis()).':"'.$vehicule->getChassis().'";s:15:"immatriculation";s:'.strlen($vehicule->getImmatriculation()).':"'.$vehicule->getImmatriculation().'";s:19:"dateProchaineVisite";s:'.strlen($vehicule->getDateProchaineVisite()).':"'.$vehicule->getDateProchaineVisite().'";s:11:"typeChassis";s:'.strlen($vehicule->getTypeChassis()).':"'.$vehicule->getTypeChassis().'";s:19:"dateMiseCirculation";s:'.strlen($vehicule->getDateMiseCirculation()).':"'.$vehicule->getDateMiseCirculation().'";s:12:"typeVehicule";a:1:{s:2:"id";i:'.$vehicule->getTypeVehicule()->getId().';}}';
                    $sql = "INSERT INTO ext_log_entries (action, logged_at, object_id, object_class, version, data, username) VALUES ('create', now(), '".$vehicule->getId()."', 'AppBundle\\\Entity\\\Vehicule', '1', '".$data."', '".$vehicule->getCreePar()."')";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                }
            }
        }
        echo "good";
    }
    
    
}
