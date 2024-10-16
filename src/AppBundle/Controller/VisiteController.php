<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Historique;

/**
 * Visite controller.
 *
 * @Route("visite")
 */
class VisiteController extends Controller
{
    
    /**
     * Lists all visite entities.
     *
     * @Route("/quittance", name="visite_quittance")
     * @Method({"GET", "POST"})
     */
    public function quittanceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:Affectation')->derniereAffectation($user->getId());
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        $chefCentre = $this->get('security.authorization_checker')->isGranted('ROLE_CHEF_CENTRE');
        if(!$centre || (!$admin && (!$affectation || $affectation === -1))){
            if($chefCentre){
                return $this->redirectToRoute('visite_delivrance');
            }
            $this->get('session')->getFlashBag()->add('error', "Vous n'êtes affecté à aucune caisse. Contacter l'administrateur.");
            return $this->redirectToRoute('homepage');
        }
        if(!$admin){
            $caisse = $affectation->getCaisse();
            $profil = "CAISSE N° ".$caisse->getNumero(); 
            $idCaisse = $caisse->getId();
        }else{
            $profil = "ADMIN"; 
            $caisse = null;
            $idCaisse = 0;
        }
        $immatriculation = \trim($request->get('immatriculation', ''));
        $vehicules = (\strlen($immatriculation) > 3) ? $em->getRepository('AppBundle:Vehicule')->trouverParImmatriculationSimilaire($immatriculation) : null;
        return $this->render('visite/quittance.html.twig', array('profil'=>$profil, 'caisse'=>$caisse, 'centre'=>$centre, 'idCaisse'=>$idCaisse, 'vehicules'=>$vehicules, 'immatriculation'=>$immatriculation));
    }
    
    /**
     * Lists all visite entities.
     *
     * @Route("/", name="visite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $visites = $em->getRepository('AppBundle:Visite')->findAll();

        return $this->render('visite/index.html.twig', array(
            'visites' => $visites,
        ));
    }
    
    /**
     * Lists all visite entities.
     *
     * @Route("/controles", name="visite_controle")
     * @Method({"GET", "POST"})
     */
    public function visitecontroleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        $chefCentre = $this->get('security.authorization_checker')->isGranted('ROLE_CHEF_CENTRE');
        if(!$affectation && !$admin && !$chefCentre){
            $this->get('session')->getFlashBag()->add('error', "Vous n'êtes affecté à aucune piste. Contacter l'administrateur.");
            return $this->redirectToRoute('homepage');
        }
        $piste = $admin || $chefCentre ? "ADMIN" : "PISTE N° ".$affectation->getPiste()->getNumero();
        $piste_id = $admin || $chefCentre ? 0 :$affectation->getPiste()->getNumero();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $immatriculation = \trim($request->get('immatriculation', ''));
        $visites = (\strlen($immatriculation) > 3) ? $em->getRepository('AppBundle:Visite')->findControlesAjaxAlleger($immatriculation, $piste_id) : null;
        
        return $this->render('visite/controles.html.twig', array('piste'=>$piste, 'centre'=>$centre->getEtat(), 'visites'=>$visites, 'immatriculation'=>$immatriculation, 'maha'=>$centre->getMaha()));
    }
    
    /**
     * Lists all visite entities.
     *
     * @Route("/delivrance", name="visite_delivrance")
     * @Method({"GET", "POST"})
     */
    public function delivranceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $immatriculation = \trim($request->get('immatriculation', ''));
        $visites = (\strlen($immatriculation) > 3) ? $em->getRepository('AppBundle:Visite')->findDelivranceAjaxAlleger($immatriculation) : null;
        return $this->render('visite/delivrance.html.twig', array('centre'=>$centre->getEtat(),'immatriculation'=>$immatriculation, 'visites'=>$visites));
    }

    /**
     * Creates a new visite entity.
     *
     * @Route("/new", name="visite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $visite = new Visite();
        $form = $this->createForm('AppBundle\Form\VisiteType', $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($visite);
            $em->flush();

            return $this->redirectToRoute('visite_show', array('id' => $visite->getId()));
        }

        return $this->render('visite/new.html.twig', array(
            'visite' => $visite,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new visite entity.
     *
     * @Route("/aiguiller", name="aiguiller")
     * @Method({"GET", "POST"})
     */
    public function aiguillerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', "Le centre n'est pas encore ouvert.");
            return $this->render('vehicule/index.html.twig', array('centre' => $centre,));
        }
        $vehicule = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }
        if($vehicule->visiteArrive()){
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
            switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                case 1: 
                    $this->get('session')->getFlashBag()->add('notice', 'Visite déjà en cours.');
                    return $this->render('visite/visite.html.twig', array('visite' => $derniereVisite,));
                case 0: case 2: $visiteParent = null; break;
                case 3 : $visiteParent = $derniereVisite;break;
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', "Vérifier le certificat de visite technique. La prochaine visite est prévue pour le ".$vehicule->getDateProchaineVisite().".");
            return $this->redirectToRoute('vehicule_index');
        }        
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives($request->get('type'));
        $chaineOptimale = \AppBundle\Utilities\Utilities::trouverChaineOptimale($chaines, $em);
        if($chaineOptimale != null){
            $visite = new Visite();
            $retour = $visite->aiguiller($vehicule, 0, $chaineOptimale, $visiteParent, $centre);
            $em->persist($visite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', $retour);
            return $this->render('visite/visite.html.twig', array(
                'visite' => $visite,
                ));
        }else{
            throw $this->createNotFoundException("Aucune chaine active. Merci de contacter l'administrateur.");
        }
    }

    /**
     * Finds and displays a visite entity.
     *
     * @Route("/{id}", name="visite_show")
     * @Method("GET")
     */
    public function showAction(Visite $visite)
    {
        $deleteForm = $this->createDeleteForm($visite);
        if($visite->getStatut()==3){
            $date1 = $visite->getDate()->format('Y-m-d');
            $date = new \DateTime($date1);
            $date->add(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getDelai().'D')); // P1D means a period of 1 day
            $date2 = $date->format('Y-m-d');
        }else{
            $date2 = null;
        }
        $em = $this->getDoctrine()->getManager();
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($visite->getId());
        $pistes = $em->getRepository('AppBundle:Chaine')->pisteChainesActives();
        return $this->render('visite/show.html.twig', array(
            'visite' => $visite,
            'delete_form' => $deleteForm->createView(),
            'dateRevisite' => $date2, 'quittance'=>$quittance,
            'pistes' => $pistes,
        ));
    }
    
    /**
     * Finds and displays a visite entity.
     *
     * @Route("/{id}/delivrance", name="visite_show_delivrance")
     * 
     */
    public function showdelivranceAction(Visite $visite)
    {
        $deleteForm = $this->createDeleteForm($visite);
        if($visite->getStatut()==3){
            $date1 = $visite->getDate()->format('Y-m-d');
            $date = new \DateTime($date1);
            $date->add(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getDelai().'D')); // P1D means a period of 1 day
            $date2 = $date->format('Y-m-d');
        }else{
            $date2 = null;
        }
        $em = $this->getDoctrine()->getManager();
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($visite->getId());
        return $this->render('visite/show.delivrance.html.twig', array(
            'visite' => $visite,
            'delete_form' => $deleteForm->createView(),
            'dateRevisite' => $date2, 'quittance' => $quittance,
        ));
    }

    /**
     * Displays a form to edit an existing visite entity.
     *
     * @Route("/{id}/edit", name="visite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Visite $visite)
    {
        $deleteForm = $this->createDeleteForm($visite);
        $editForm = $this->createForm('AppBundle\Form\VisiteType', $visite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visite_edit', array('id' => $visite->getId()));
        }

        return $this->render('visite/edit.html.twig', array(
            'visite' => $visite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a visite entity.
     *
     * @Route("/{id}", name="visite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Visite $visite)
    {
        $form = $this->createDeleteForm($visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($visite);
            $em->flush();
        }

        return $this->redirectToRoute('visite_index');
    }

    /**
     * Creates a form to delete a visite entity.
     *
     * @param Visite $visite The visite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Visite $visite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('visite_delete', array('id' => $visite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Visites entities.
     *
     * @Route("/admin/caisse/quittanceajax/liste", name="quittanceajax")
     * 
     * 
     */
    public function quittanceAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'v.immatriculation', 'p.nom', 'p.prenom', 'r.revisite');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:Affectation')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$affectation && !$admin){
            throw $this->createNotFoundException("Vous n'êtes affecté à aucune caisse. Contacter l'administrateur.");
        }
        if($admin){
            $rResult = $em->getRepository('AppBundle:Visite')->findQuittancesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, 0);
            $iTotal = $em->getRepository('AppBundle:Visite')->countQuittanceRows(0);  
            $iTotalFiltre = $em->getRepository('AppBundle:Visite')->countQuittanceRowsFiltre(0, $searchTerm);
        }else{
            $rResult = $em->getRepository('AppBundle:Visite')->findQuittancesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, $affectation->getCaisse()->getId());
            $iTotal = $em->getRepository('AppBundle:Visite')->countQuittanceRows($affectation->getCaisse()->getId()); 
            $iTotalFiltre = $em->getRepository('AppBundle:Visite')->countQuittanceRows($affectation->getCaisse()->getId(), $searchTerm);
        }
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($aRow['id']);
            $action = $aRow['ouvert'] ? $this->genererAction($aRow['id'], $quittance) : "Caisse Fermée";
            $revisite = $aRow['revisite'] == 1 ? "REVISITE" : "NORMALE";
            $output['aaData'][] = array(\strtoupper($aRow['immatriculation']),$aRow['nom'].' '.$aRow['prenom'],$revisite,$aRow['caisse'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id, $quittance){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER')){
            if($quittance != null){
                $action .= "<a title='Quittance' class='btn btn-success' href='".$this->generateUrl('caisse_quittance_show', array('id'=> $quittance->getId()))."'><i class='fa fa-credit-card' ></i> Voir</a>";
            }else{
                $action .= "<a onclick='loadDynamicContentModal(".$id.")' title='Quittance' class='btn btn-success' href='#'><i class='fa fa-credit-card' ></i> Générer</a>";
            }
        }
        return $action;
    }
    
    /**
     * Export all Proprietaire entities.
     *
     * @Route("/admin/parametres/proprietaire/download", name="proprietaire_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Proprietaire')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Proprietaire".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Proprietaire'.$date->format('Y_m_d_H_i_s').'.xls';
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        return $response;        
    }

    
    private function writeRapport($phpExcelObject, $entities) {
        $phpExcelObject->setActiveSheetIndex(0);
        $col = 0;
        $objWorksheet = $phpExcelObject->getActiveSheet();
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("N° pièce");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Nom");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Prénom");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Téléphone");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Autre Téléphone");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Email");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Adresse");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Type");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getNumpiece());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getNom());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPrenom());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getTelephone());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAutreTelephone());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getEmail());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAdresse());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPersonneMorale());$col++;
            $ligne++;
        }
    }
    
    
    /**
     * Liste tous les controles en cours
     *
     * @Route("/visite/controleajax/liste", name="controleajax")
     * 
     * 
     */
    public function controleAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'v.immatriculation', 'p.nom', 'p.prenom', 'r.revisite', 'r.statut');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$affectation && !$admin){
            throw $this->createNotFoundException("Vous n'êtes affecté à aucune piste. Contacter l'administrateur.");
        }
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if($admin){
            $rResult = $em->getRepository('AppBundle:Visite')->findControlesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, 0);
            $iTotal = $em->getRepository('AppBundle:Visite')->countControlesRows(0);    
            $iTotalFiltre = $em->getRepository('AppBundle:Visite')->countControlesRows(0, $searchTerm);
        }
        else {
            $rResult = $em->getRepository('AppBundle:Visite')->findControlesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, $affectation->getPiste()->getId());
            $iTotal = $em->getRepository('AppBundle:Visite')->countControlesRows($affectation->getPiste()->getId());
            $iTotalFiltre = $em->getRepository('AppBundle:Visite')->countControlesRows($affectation->getPiste()->getId(), $searchTerm);
        }
        
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = ($centre->getEtat())?$this->genererPisteAction($aRow['id'], $aRow['statut'], $aRow['contreVisiteVisuelle'],  $centre->getMaha()) : "Centre fermé";
            if($aRow['contreVisite']){
                $revisite = $aRow['contreVisiteVisuelle'] == 1 ? "Contre visite visuelle" : "Contre visite";
            }else{
                $revisite = $aRow['revisite'] == 1 ? "Revisite" : "Normale";
            }
            if($aRow['statut'] == 1){
                $etat = "A controller";
            }else if($aRow['statut'] == 2){
                $etat = "Succès";
            }else if($aRow['statut'] == 3){
                $etat = "Echec";
            }else if($aRow['statut'] == 4){
                $etat = "Délivré";
            }
            $output['aaData'][] = array(\strtoupper($aRow['immatriculation']),$aRow['typeChassis'],$aRow['nom'].' '.$aRow['prenom'],$revisite,$etat,$aRow['piste'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererPisteAction($id, $statut, $visuelle, $maha){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CONTROLLEUR')){
            if($statut == 1){
                if($visuelle == 1 || !$maha){
                    $action .= " <a title='Controller' class='btn btn-success' href='".$this->generateUrl('visite_controleur', array('id'=> $id ))."'><i class='fa fa-config' ></i> Controler</a>";
                }else{
                    $action .= " <a title='Controller' class='btn btn-success' href='".$this->generateUrl('visite_maha', array('id'=> $id ))."'><i class='fa fa-config' ></i> Controler</a>";
                }  
                if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER_PRINCIPAL')){
                    //$action .= " <a title='Annuler' class='btn btn-success' href='".$this->generateUrl('visite_maha_annuler', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-cancel' ></i> Annuler</a>";
                }
            }elseif($statut > 1){
                $action .= " <a title='Détail' class='btn btn-success' href='".$this->generateUrl('visite_show', array('id'=> $id ))."'><i class='fa fa-plus' ></i> Voir le rapport</a>";
            }
            
        }
        return $action;
    }
    
    /**
     * Liste toutes les visites 
     *
     * @Route("/visite/delivranceajax/liste", name="delivranceajax")
     * 
     * 
     */
    public function delivranceajaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'v.immatriculation', 'p.nom', 'p.prenom', 'r.revisite', 'r.statut');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Visite')->findDelivranceAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
        $iTotal = $em->getRepository('AppBundle:Visite')->countDelivrancesRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Visite')->countDelivrancesRowsFiltre($searchTerm);
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = ($centre->getEtat())?$this->genererDelivranceAction($aRow['id'], $aRow['statut']):"Centre fermé";
            $revisite = $aRow['revisite'] == 1 ? "REVISITE" : "NORMALE";
            switch($aRow['statut']){
                case 0 : $etat = "QUITTANCE A PAYER";break;
                case 1 : $etat = "A CONTROLER";break;
                case 2 : $etat = "SUCCES";break;
                case 3 : $etat = "ECHEC";break;
                case 4 : $etat = "CERTIFICAT DELIVRE";break;
                case 5 : $etat = "ANNULEE";break;
            }
            
            $output['aaData'][] = array(\strtoupper($aRow['immatriculation']),$aRow['typeChassis'],$aRow['nom'].' '.$aRow['prenom'],$revisite,$etat,$aRow['caisse'].'/'.$aRow['piste'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererDelivranceAction($id, $statut){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_DELIVRANCE')){
            if($statut > 1){
                $action .= " <a title='Détail' class='btn btn-success' href='".$this->generateUrl('visite_show_delivrance', array('id'=> $id ))."'><i class='fa fa-plus' ></i> Voir le rapport</a>";
            }
        }
        return $action;
    }
    
    /**
     * Récupération des résultats MAHA.
     *
     * @Route("/controleur/maha", name="visite_maha")
     * @Method({"GET", "POST"})
     */
    public function mahaAction(Request $request)
    {
        //$id = $request->get('id');echo $id;exit;
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$visite || $visite->getStatut() == 0 || (!$admin && $visite->getChaine()->getPiste()->getId()!= $affectation->getPiste()->getId())){
            throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
        }
        if($visite->getRevisite() && $visite->getVisiteParent() != null && $visite->getVisiteParent()->getSuccesMaha()){
            
            return $this->redirectToRoute('visite_controleur', array('id' => $visite->getId()));
        }else{
            $contenu = $visite->lireFichierResultatMaha();
            if($contenu != null){
                while(!feof($contenu)) {
                    $ligne = fgets($contenu);
                    if(strpos($ligne, 'CRC') !== false ) break;
                    $valeurs = $visite->lireLigneMaha($ligne);
                    if($valeurs != null){
                        $controle = $em->getRepository('AppBundle:Controle')->trouverParCodeGenre($valeurs[0], $visite->getVehicule()->getTypeVehicule()->getGenre()->getCode());
                        if($controle != null){
                            $resultat = $em->getRepository('AppBundle:Resultat')->trouverResultatVisiteCode($visite->getId(), $controle);
                            if($resultat == null){
                                switch ($controle->getType()){
                                    case "MESURE - VALEUR" : $codeResultat = $em->getRepository('AppBundle:CodeMahaResultat')->trouverParControleResultat($controle->getId(), $valeurs[1]);break;
                                    case "MESURE - INTERVALLE" : $codeResultat = $em->getRepository('AppBundle:CodeMahaResultat')->trouverParControleIntervalle($controle->getId(), $valeurs[1]);break;
                                    default :$codeResultat = null;
                                }
                                if($codeResultat != null){
                                    $resultat = new \AppBundle\Entity\Resultat();
                                    $resultat->generer($visite, $codeResultat, $valeurs[1]);
                                    $em->persist($resultat);
                                }
                            }
                           
                        }else{
                            //$this->get('session')->getFlashBag()->add('error', "Le fichier est corrompu!");break;
                        }
                    }else{
                        continue;
                    }
                }
                $visite->setStep(1);
                $em->flush();
                $visite->fermerFichierResultatMaha($contenu);
                return $this->redirectToRoute('visite_controleur', array('id' => $visite->getId()));
            }else{
                $this->get('session')->getFlashBag()->add('error', "Le fichier de résultat maha nexiste pas!");
            }
            return $this->render('visite/maha.html.twig', array(
                'visite' => $visite,
            ));
        }
        
        
    }
    
    /**
     * Annuler une visite.
     *
     * @Route("/visite/annuler", name="visite_maha_annuler")
     * @Method({"GET", "POST"})
     */
    public function visiteannulerAction(Request $request)
    {
        echo "process à définir";
    }
    
    /**
     * Formulaire de visit technique manuelle.
     *
     * @Route("/controleur/formulaire", name="visite_controleur")
     * @Method({"GET", "POST"})
     */
    public function controleurAction(Request $request)
    {
        //$id = $request->get('id');echo $id;exit;
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$visite || $visite->getStatut() == 0 || (!$admin && $visite->getChaine()->getPiste()->getId()!= $affectation->getPiste()->getId())){
            throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
        }
        if($visite->getStep() == 0 && $centre->getMaha()){
            $historique = new Historique("Fraude MAHA", "Visite", "step=0", "maha=1", $user);
            $em->persist($historique);
            $em->flush();
            throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");            
        }
        $controles = $em->getRepository('AppBundle:Controle')->trouverVisuelActif($visite->getVehicule()->getTypeVehicule()->getGenre()->getCode());
        if($request->get('controleur')){
            if($visite->getStatut() != 1 ){
                throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
            }
            $succes = true;
            $succesMaha = true;
            $date = new \DateTime();
            foreach($controles as $controle){
                if($request->get($controle->getCode())!=null){
                    $resultatMaha = $em->getRepository('AppBundle:CodeMahaResultat')->trouverParControleResultat($controle->getId(), $request->get($controle->getCode()));
                    if($resultatMaha == null){
                        throw $this->createAccessDeniedException("Cette opération n'est pas autorisée null");
                    }else{
                        $resultat = new \AppBundle\Entity\Resultat();
                        $resultat->generer($visite, $resultatMaha, $resultatMaha->getValeur());
                        $em->persist($resultat);
                        $em->flush();
                    }
                }else{
                    throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
                }
            }
            $resultats = $em->getRepository('AppBundle:Resultat')->trouverResultatVisite($visite->getId());
            foreach ($resultats as $resultat){
                if(!$resultat->getSucces()){
                    $succes = false;
                    if($resultat->getControle()->getType() == "MESURE - VALEUR" || $resultat->getControle()->getType() == "MESURE - INTERVALLE"){
                        $succesMaha = false;
                    }
                }
            }
            if($succes){
                $visite->setStatut(2);
                $date1 = $visite->getDate()->format('Y-m-d');
                $date = new \DateTime($date1);
                $date->add(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getValidite().'M')); // P1D means a period of 1 day
                $date->sub(new \DateInterval('P1D'));
                $visite->setDateValidite($date);
                $visite->setNumeroCertificat($centre->getCode().\time());
                //$centre->decrementerCarteVierge();
                $visite->getVehicule()->setDateProchaineVisite($date->format('Y-m-d'));
                $visite->getVehicule()->setCompteurRevisite(0);
            }else{
                $visite->setStatut(3);
                if(!$visite->getContreVisite() && ! $visite->getContreVisiteVisuelle() )
                    $visite->getVehicule()->incrementerCompteurRevisite();
                $visite->setSuccesMaha($succesMaha);
            }
            $visite->setSuccesMaha($succesMaha);
            $user = $this->container->get('security.context')->getToken()->getUser();
            $visite->setControlleur($user->getNomComplet());
            $visite->setDateControle(new \DateTime());
            $visite->getVehicule()->setVerrou(false);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Controle terminé. Merci de consulter le résultat');
            if($visite->getStatut()==3){
                $date1 = $visite->getDate()->format('Y-m-d');
                $date = new \DateTime($date1);
                $date->add(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getDelai().'D')); // P1D means a period of 1 day
                $date2 = $date->format('Y-m-d');
            }else{
                $date2 = null;
            }
            $pistes = $em->getRepository('AppBundle:Chaine')->pisteChainesActives();
            return $this->render('visite/show.html.twig', array(
                'controles' => $controles,
                'visite' => $visite,
                'dateRevisite' => $date2,
                'quittance' => $visite->getQuittance(),
                'pistes' => $pistes,
            ));
        }
        return $this->render('visite/controle.html.twig', array(
            'controles' => $controles,
            'visite' => $visite,
        ));
        
    }
    
    /**
     * Creates a new visite entity.
     *
     * @Route("/contrevisite/creer", name="contrevisite")
     * @Method({"GET", "POST"})
     */
    public function contrevisiteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', "Le centre n'est pas encore ouvert.");
            return $this->render('vehicule/index.html.twig', array('centre' => $centre,));
        }
        $vehicule = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        $chaine = $em->getRepository('AppBundle:Chaine')->trouverChaineParPiste($request->get('piste'));
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }  
        if($vehicule->visiteArrive()){
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
            switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                case 1:  
                    $this->get('session')->getFlashBag()->add('notice', 'Visite déjà programée.');
                    return $this->redirectToRoute('visite_controle');
            }
        }
        if($derniereVisite == null){
            $this->get('session')->getFlashBag()->add('error', "Il faut d'abord une visite avant de pouvoir faire une contre visite!");
            return $this->redirectToRoute('visite_controle');
        } 
        if($chaine == null){
            $chaine = $derniereVisite->getChaine();
        }
        $visite = new Visite();
        $quittance = new \AppBundle\Entity\Quittance();
        $visite->initialiserContreVisite(false, $quittance);
        $visite->setVisiteParent($derniereVisite);
        $derniereVisite->setContrevisiteCree(true);
        $visite->setRevisite($derniereVisite->getRevisite());
        $quittance->setVisite($visite);
        $visite->aiguiller($vehicule, 1, $chaine, null, $centre);
        $em->persist($visite);
        $em->persist($quittance);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Contre visite créée.');
        $visite->genererFichierMaha();
        return $this->redirectToRoute('visite_controle');
    }
    
    /**
     * Aiguiller un vehicule sur la meme piste qu'un autre.
     *
     * @Route("/grouper", name="grouper")
     * @Method({"GET", "POST"})
     */
    public function grouperAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', "Le centre n'est pas encore ouvert.");
            return $this->render('vehicule/index.html.twig', array('centre' => $centre,));
        }
        $vehiculeInitial = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        $vehiculeAjoute = $em->getRepository('AppBundle:Vehicule')->trouverParImmatriculation($request->get('carteGrise'));
        if(!$vehiculeInitial){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }
        $visite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehiculeInitial->getId());
        if(!$vehiculeAjoute){
            $this->get('session')->getFlashBag()->add('error', "Le véhicule ".$request->get('carteGrise')." n'existe pas. Il faut l'enregistrer.");
            return $this->render('visite/visite.html.twig', array(
            'visite' => $visite,
        ));
        }else{
            if($vehiculeAjoute->visiteArrive()){
                $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehiculeAjoute->getId());
                switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                    case 1: 
                        $this->get('session')->getFlashBag()->add('notice', 'Visite déjà en cours.');
                        return $this->render('visite/visite.html.twig', array('visite' => $derniereVisite,));
                    case 0: case 2: $visiteParent = null; break;
                    case 3 : $visiteParent = $derniereVisite;break;
                }
            }else{
                $this->get('session')->getFlashBag()->add('error', "Vérifier le certificat de visite technique. La prochaine visite est prévue pour le ".$vehicule->getDateProchaineVisite().".");
                return $this->redirectToRoute('vehicule_index');
            }
            $visiteGroupe = new Visite();
            $visiteGroupe->aiguiller($vehiculeAjoute, 0, $visite->getChaine(), $visiteParent, $centre);
            $em->persist($visiteGroupe);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', $vehiculeAjoute->getImmatriculation().' aiguillé sur la piste '.$visiteGroupe->getChaine()->getPiste()->getNumero());
        }
        
        return $this->render('visite/visite.html.twig', array(
            'visite' => $visiteGroupe,
        ));
    }
    
    /**
     * Creates a new visite entity.
     *
     * @Route("/contrevisitevisuelle/creer", name="contrevisitevisuelle")
     * @Method({"GET", "POST"})
     */
    public function contrevisitevisuelleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', "Le centre n'est pas encore ouvert.");
            return $this->render('vehicule/index.html.twig', array('centre' => $centre,));
        }
        $vehicule = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }  
        if($vehicule->visiteArrive()){
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
            switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                case 1:  
                    $this->get('session')->getFlashBag()->add('notice', 'Visite déjà programmée.');
                    return $this->redirectToRoute('visite_controle');
            }
        }
        if($derniereVisite == null || !$derniereVisite->getSuccesMaha()){
            $this->get('session')->getFlashBag()->add('error', "Il faut d'abord une visite ayant réussi le test MAHA avant de pouvoir faire une contre visite visuelle!");
            return $this->redirectToRoute('visite_controle');
        }
        $chaine = $derniereVisite->getChaine();
        $visite = new Visite();
        $quittance = new \AppBundle\Entity\Quittance();
        $visite->initialiserContreVisite(true, $quittance);
        $quittance->setVisite($visite);
        $visite->aiguiller($vehicule, 1, $chaine, null, $centre);
        $visite->setVisiteParent($derniereVisite);
        $derniereVisite->setContrevisiteCree(true);
        $visite->setRevisite($derniereVisite->getRevisite());
        $em->persist($visite);
        $em->persist($quittance);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Contre visite créée.');
        return $this->redirectToRoute('visite_controleur', array('id' => $visite->getId()));
        
    }
    
    /**
     * Creates a new visite entity.
     *
     * @Route("/aiguiller/caissier", name="aiguillerCaissier")
     * @Method({"GET", "POST"})
     */
    public function aiguillerCaissierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', "Le centre n'est pas encore ouvert.");
            return $this->render('vehicule/index.html.twig', array('centre' => $centre,));
        }
        $vehicule = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }
        $visite = null;
        if($vehicule->visiteArrive()){
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
            switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                case 1: 
                    $chaines = $em->getRepository('AppBundle:Chaine')->toutesChainesActivesCaisse($request->get('caisse'));
                    $chaineOptimale = \AppBundle\Utilities\Utilities::trouverChaineOptimale($chaines, $em);
                    $derniereVisite->setChaine($chaineOptimale);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'La visite à été redirigée vers votre caisse!');
                    return $this->redirectToRoute('visite_quittance');
                case 0: case 2: $visiteParent = null; break;
                case 3 : $visiteParent = $derniereVisite;break;
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', "Vérifier le certificat de visite technique. La prochaine visite est prévue pour le ".$vehicule->getDateProchaineVisite().".");
            return $this->redirectToRoute('visite_quittance');
        }  
        $chaines = $em->getRepository('AppBundle:Chaine')->toutesChainesActivesCaisse($request->get('caisse'));
        $chaineOptimale = \AppBundle\Utilities\Utilities::trouverChaineOptimale($chaines, $em);
        if($chaineOptimale != null){
            $visite = new Visite();
            $visite->aiguiller($vehicule, 0, $chaineOptimale, $visiteParent, $centre);
            $em->persist($visite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Aiguillage effectué sur votre caisse.');
            return $this->redirectToRoute('visite_quittance');
        }else{
            $this->get('session')->getFlashBag()->add('error', "Aucune chaine active. Merci de contacter l'administrateur.");
            return $this->redirectToRoute('visite_quittance');
        }
    }
    
    /**
     * Regénération du fichier CG des résultats MAHA.
     *
     * @Route("/controleur/cg", name="generer_cg")
     * @Method({"GET", "POST"})
     */
    public function cgAction(Request $request)
    {
        //$id = $request->get('id');echo $id;exit;
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$visite || $visite->getStatut() == 0 || (!$admin && $visite->getChaine()->getPiste()->getId()!= $affectation->getPiste()->getId())){
            throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
        }
        $visite->genererFichierMaha();   
        $this->get('session')->getFlashBag()->add('notice', 'Le fichier a été généré. Vous pouvez reprendre le controle.');
        return $this->render('visite/maha.html.twig', array(
                'visite' => $visite,
        ));
    }
    
    /**
     * Imprimer le certificat
     *
     * @Route("/visite/imprimer", name="visite_certificat")
     * 
     */
    public function certificatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        $certificat = $em->getRepository('AppBundle:Certificat')->trouverParNumero($request->get('certificat'));
        $visite->setStatut(4);
        $certificat->setImmatriculation($visite->getImmatriculation_v());
        $certificat->setUtilise(true);
        $centre->decrementerCarteVierge();
        $em->flush(); 
         return new Response(
            'OK'
        );
    }
    
    /**
     * Aiguillage gratuit
     *
     * @Route("/aiguiller/gratuit", name="aiguillerGratuit")
     * @Method({"GET", "POST"})
     */
    public function aiguillerGratuitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', "Le centre n'est pas encore ouvert.");
            return $this->render('vehicule/index.html.twig', array('centre' => $centre,));
        }
        $vehicule = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }
        if($vehicule->visiteArrive()){
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
            switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                case 1: 
                    $this->get('session')->getFlashBag()->add('notice', 'Visite déjà en cours.');
                    return $this->render('visite/visite.html.twig', array('visite' => $derniereVisite,));
                case 0: case 2: $visiteParent = null; break;
                case 3 : $visiteParent = $derniereVisite;break;
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', "Vérifier le certificat de visite technique. La prochaine visite est prévue pour le ".$vehicule->getDateProchaineVisite().".");
            return $this->redirectToRoute('vehicule_index');
        }        
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives($request->get('type'));
        $chaineOptimale = \AppBundle\Utilities\Utilities::trouverChaineOptimale($chaines, $em);
        if($chaineOptimale != null){
            $visite = new Visite();
            $quittance = new \AppBundle\Entity\Quittance();
            $visite->initialiserVisiteGratuite($quittance);
            $quittance->setVisite($visite);
            $visite->aiguiller($vehicule, 1, $chaineOptimale, $visiteParent, $centre);
            $em->persist($visite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Aiguillage effectué.');
            return $this->render('visite/visite.html.twig', array(
                'visite' => $visite,
                ));
        }else{
            throw $this->createNotFoundException("Aucune chaine active. Merci de contacter l'administrateur.");
        }
    }
    
    /**
     * Choix numéro certificat.
     *
     * @Route("/numero/{id}", name="visite_modal_certificat")
     * @Method("GET")
     */
    public function modalcertificatAction(Visite $visite)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $certificat = $em->getRepository('AppBundle:Certificat')->recuperer($user->getId());
        return $this->render('visite/modalcertificat.html.twig', array(
            'visite' => $visite, 'certificats'=> $certificat
        ));
    }
    
    /**
     * Vérifier numéro certificat.
     *
     * @Route("/serie/verifier/{certificat}/{annee}/{id}", name="visite_certificat_verifier")
     * @Method("GET")
     */
    public function verifiercertificatAction(Request $request)
    {
        $numero = intval($request->get("certificat", 0));
        $id = intval($request->get("id", 0));
        $annee = intval($request->get("annee", 0));
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository('AppBundle:Visite')->find($id);
        $certificat = $em->getRepository('AppBundle:Certificat')->trouverParNumeroAnnee($numero, $annee);
        if($certificat && $visite->getStatut() == 2){
            $visite->setStatut(4);
            $visite->setCertificat($annee."-".$numero);
            $em->flush();
            $certificat->setImmatriculation($visite->getImmatriculation_v());
            $certificat->setUtilise(1);
            $em->flush();
            return new Response("Valide");
        }
        return new Response($numero."_".$request->get('id', 0), 500); 
        
    }
    
    /**
     * Retour chez le controleur.
     *
     * @Route("/retour/controleur/{id}", name="retour_controleur")
     * @Method("GET")
     */
    public function retourcontroleurAction(Visite $visite)
    {
        if(!$visite){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($visite->getStatut() == 4){             
            try{
                $visite->setStatut(2);
                $em->persist(new Historique("RETOUR CONTROLEUR", "Visite", $visite->getCertificat(), "", $user));
                $split = \explode("-", $visite->getCertificat());
                $certificat = $em->getRepository('AppBundle:Certificat')->trouverUtiliseParNumeroAnnee($split[1], $split['0']);
                if(!$certificat){
                    throw $this->createNotFoundException("Ooops... Pas de certificat pour num : ".$split[1]." annee : ".$split['0']);
                }
                $visite->setCertificat("");
                $certificat->retourControleur();
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Retour effectué.');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', "Ooops... Une erreur s'est produite.");
            }  
        }
        return $this->redirectToRoute('visite_delivrance');
        
    }
    
}
