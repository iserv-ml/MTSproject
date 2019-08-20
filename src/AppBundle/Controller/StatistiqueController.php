<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
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
        if($etat){
            $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_caisse_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";        
        }
        return $action;
    }

    /**
     * Lists all SortieCaisse entities.
     *
     * @Route("/admin/gestion/centre/statistiques/liste", name="statistiquescaisseajax")
     * 
     * 
     */
    public function sortiecaisseajaxAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.type', 'r.montant', 'r.dateCreation');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:SortieCaisse')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:SortieCaisse')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['type'], $aRow['description'], $aRow['montant'], $aRow['dateCreation'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_gestion_centre_sorties_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                //$action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_gestion_centre_sorties_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                //$action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_gestion_centre_sorties_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
}
