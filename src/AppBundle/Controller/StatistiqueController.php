<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Caisse;

/**
 * Statistique controller.
 *
 * @Route("admin/gestion/centre/statistique")
 */
class StatistiqueController extends Controller
{
    /**
     * Lists all sortieCaisse entities.
     *
     * @Route("/", name="admin_gestion_centre_statistiques_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('statistique/caisse/index.html.twig');
    }
    
    /**
     * Accueil etat journalier des caisses.
     *
     * @Route("/", name="admin_gestion_centre_statistique_caisse_index")
     * @Method("GET")
     */
    public function caisseindexAction()
    {
        return $this->render('statistique/caisse/index.html.twig');
    }
    
    /**
     * Lists all Caisse entities.
     *
     * @Route("/admin/gestion/centre/statistique/caisse/liste", name="caissepouretatajax")
     * 
     * 
     */
    public function caissepouretatAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.numero', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Caisse')->trouverPourEtat($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Caisse')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Caisse')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $ouvert = ($aRow->getOuvert() == 1) ? "O" : "F";
            $action = $this->genererCaisseAction($aRow->getId(), $aRow->getActif());
            $affectation = $aRow->getAffectationActive();
            $caissier = ($affectation) ? $affectation->getAgent()->getNom()." ".$affectation->getAgent()->getPrenom() : "";
            $output['aaData'][] = array($aRow->getNumero(), $caissier, $aRow->getSoldeInitial(), $ouvert, $aRow->getSolde(),$action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererCaisseAction($id, $etat){
        $action = "";
        if($etat){
            $action = "<a title='Etat journalier' class='btn btn-success' href='".$this->generateUrl('centre_gestion_statistiques_caisse_etat', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";        
        }
        return $action;
    }
    
    /**
     * Finds and displays a proprietaire entity.
     *
     * @Route("/{id}", name="centre_gestion_statistiques_caisse_etat")
     * @Method("GET")
     */
    public function etatAction(Caisse $caisse)
    {
        if (!$caisse) {
            throw $this->createNotFoundException("La caisse demandée n'est pas disponible.");
        }
        $em = $this->getDoctrine()->getManager();
        $usages = $em->getRepository('AppBundle:Usage')->findAll();
        $resultat = array();
        $i = 0;
        $nv = 0;
        $nr = 0;
        $mv = 0;
        $mr = 0;
        foreach($usages as $usage){
            $ligne = array();
            $ligne[0] = $usage->getLibelle();
            $etat = $em->getRepository('AppBundle:EtatJournalier')->etatJournalier($usage->getLibelle(), \date('d-m-Y'), $caisse->getNumero());
            if($etat && count($etat)>0){
                $ligne[1] = \intval($etat[0][1]);
                $ligne[2] = \intval($etat[0][2]);//$visites['nbRevisite'];
                $ligne[3] = \intval($etat[0][3]);//$visites['mVisite'];
                $ligne[4] = \intval($etat[0][4]);//$visites['mVisite'];
                $ligne[5] = \intval($etat[0][3])+\intval($etat[0][4]);//$visites['mVisite']+$visites['mRevisite'];
            }else{
                $ligne[1] = 0;
                $ligne[2] = 0;
                $ligne[3] = 0;
                $ligne[4] = 0;
                $ligne[5] = 0;
            }
            $resultat[] = $ligne;
            $nv += $ligne[1];
            $nr += $ligne[2];
            $mv += $ligne[3];
            $mr += $ligne[4];
        } 
        $resultat[] = ['TOTAL', $nv, $nr, $mv, $mr, $mv+$mr];
       return $this->render('statistique/caisse/etat.html.twig', array(
            'resultats' => $resultat,'caisse' => $caisse,
        ));
    }
    
    /**
     * Finds and displays a proprietaire entity.
     *
     * @Route("/caisse/etat", name="centre_gestion_statistiques_caissier_etat")
     * @Method("GET")
     */
    public function caissierAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $affectation = $em->getRepository('AppBundle:Affectation')->derniereAffectation($user->getId());
        if (!$affectation) {
            $this->get('session')->getFlashBag()->add('error', "Vous n'êtes affecté à aucune caisse. Contacter l'administrateur.");
            return $this->redirectToRoute('homepage');
        }
        $usages = $em->getRepository('AppBundle:Usage')->findAll();
        $resultat = array();
        $i = 0;
        $nv = 0;
        $nr = 0;
        $mv = 0;
        $mr = 0;
        foreach($usages as $usage){
            $ligne = array();
            $ligne[0] = $usage->getLibelle();
            $etat = $em->getRepository('AppBundle:EtatJournalier')->etatJournalier($usage->getLibelle(), \date('d-m-Y'), $affectation->getCaisse()->getNumero());
            if($etat && count($etat)>0){
                $ligne[1] = \intval($etat[0][1]);
                $ligne[2] = \intval($etat[0][2]);//$visites['nbRevisite'];
                $ligne[3] = \intval($etat[0][3]);//$visites['mVisite'];
                $ligne[4] = \intval($etat[0][4]);//$visites['mVisite'];
                $ligne[5] = \intval($etat[0][3])+\intval($etat[0][4]);//$visites['mVisite']+$visites['mRevisite'];
            }else{
                $ligne[1] = 0;
                $ligne[2] = 0;
                $ligne[3] = 0;
                $ligne[4] = 0;
                $ligne[5] = 0;
            }
            $resultat[] = $ligne;
        $nv += $ligne[1];
            $nr += $ligne[2];
            $mv += $ligne[3];
            $mr += $ligne[4];
        } 
        $resultat[] = ['TOTAL', $nv, $nr, $mv, $mr, $mv+$mr]; 
       return $this->render('statistique/caisse/caissier.html.twig', array(
            'resultats' => $resultat,'caisse' => $affectation->getCaisse(),
        ));
    }
    
}
