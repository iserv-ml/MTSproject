<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Method("GET")
     */
    public function quittanceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:Affectation')->derniereAffectation($user->getId());
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$centre || (!$admin && (!$affectation || $affectation === -1))){
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
            return $this->render('visite/quittance.html.twig', array('profil'=>$profil, 'caisse'=>$caisse, 'centre'=>$centre, 'idCaisse'=>$idCaisse,));
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
     * @Method("GET")
     */
    public function visitecontroleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$affectation && !$admin){
            $this->get('session')->getFlashBag()->add('error', "Vous n'êtes affecté à aucune piste. Contacter l'administrateur.");
            return $this->redirectToRoute('homepage');
        }
        $piste = $admin ? "ADMIN" : "PISTE N° ".$affectation->getPiste()->getNumero();
        return $this->render('visite/controles.html.twig', array('piste'=>$piste, 'centre'=>$centre->getEtat()));
    }
    
    /**
     * Lists all visite entities.
     *
     * @Route("/delivrance", name="visite_delivrance")
     * @Method("GET")
     */
    public function delivranceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        return $this->render('visite/delivrance.html.twig', array('centre'=>$centre->getEtat()));
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
            $this->get('session')->getFlashBag()->add('error', "Vérifier le certificat de visite technique. La date de la prochaine visite n'est pas arrivée.");
            return $this->redirectToRoute('vehicule_index');
        }        
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
        $chaineOptimale = \AppBundle\Utilities\Utilities::trouverChaineOptimale($chaines, $em);
        if($chaineOptimale != null){
            $visite = new Visite();
            $visite->aiguiller($vehicule, 0, $chaineOptimale, $visiteParent, $centre);
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
        return $this->render('visite/show.html.twig', array(
            'visite' => $visite,
            'delete_form' => $deleteForm->createView(),
            'dateRevisite' => $date2,
        ));
    }
    
    /**
     * Finds and displays a visite entity.
     *
     * @Route("/{id}/delivrance", name="visite_show_delivrance")
     * @Method("GET")
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
        return $this->render('visite/show.delivrance.html.twig', array(
            'visite' => $visite,
            'delete_form' => $deleteForm->createView(),
            'dateRevisite' => $date2,
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
            $output['aaData'][] = array($aRow['immatriculation'],$aRow['nom'].' '.$aRow['prenom'],$revisite,$aRow['caisse'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id, $quittance){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER')){
            if($quittance != null){
                $action .= "<a title='Quittance' class='btn btn-success' href='".$this->generateUrl('caisse_quittance_show', array('id'=> $quittance->getId() ))."'><i class='fa fa-credit-card' ></i> Voir</a>";
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
            $action = ($centre->getEtat())?$this->genererPisteAction($aRow['id'], $aRow['statut']) : "Centre fermé";
            $revisite = $aRow['revisite'] == 1 ? "REVISITE" : "NORMALE";
            if($aRow['statut'] == 1){
                $etat = "A CONTROLER";
            }else if($aRow['statut'] == 2){
                $etat = "SUCCESS";
            }else if($aRow['statut'] == 3){
                $etat = "ECHEC";
            }else if($aRow['statut'] == 4){
                $etat = "DELIVRE";
            }
            $output['aaData'][] = array($aRow['immatriculation'],$aRow['nom'].' '.$aRow['prenom'],$revisite,$etat,$aRow['piste'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererPisteAction($id, $statut){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CONTROLLEUR')){
            if($statut == 1){
                $action .= " <a title='Controller' class='btn btn-success' href='".$this->generateUrl('visite_maha', array('id'=> $id ))."'><i class='fa fa-config' ></i> Controler</a>";
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
        $rResult = $em->getRepository('AppBundle:Visite')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
        $iTotal = $em->getRepository('AppBundle:Visite')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Visite')->countRowsFiltre($searchTerm);
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
            
            $output['aaData'][] = array($aRow['immatriculation'],$aRow['nom'].' '.$aRow['prenom'],$revisite,$etat,$aRow['caisse'].'/'.$aRow['piste'], $action);
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
        $contenu = $visite->lireFichierResultatMaha();
        if($contenu != null){
            while(!feof($contenu)) {
                $ligne = fgets($contenu);
                if(strpos($ligne, 'CRC') !== false ) break;
                $valeurs = $visite->lireLigneMaha($ligne);
                if($valeurs != null){
                    $controle = $em->getRepository('AppBundle:Controle')->trouverParCodeGenre($valeurs[0], $visite->getVehicule()->getTypeVehicule()->getGenre()->getCode());
                    if($controle != null){
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
                    }else{
                        //$this->get('session')->getFlashBag()->add('error', "Le fichier est corrompu!");break;
                    }
                }else{
                    continue;
                }
            }
            $em->flush();
            return $this->redirectToRoute('visite_controleur', array('id' => $visite->getId()));
        }else{
            $this->get('session')->getFlashBag()->add('error', "Le fichier de résultat maha nexiste pas!");
        }
        return $this->render('visite/maha.html.twig', array(
            'visite' => $visite,
        ));
        
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
        $controles = $em->getRepository('AppBundle:Controle')->trouverVisuelActif($visite->getVehicule()->getTypeVehicule()->getGenre()->getCode());
        if($request->get('controleur')){
            if($visite->getStatut() != 1 ){
                throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
            }
            $succes = true;
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
                }
            }
            if($succes){
                $visite->setStatut(2);
                $date1 = $visite->getDate()->format('Y-m-d');
                $date = new \DateTime($date1);
                $date->add(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getValidite().'M')); // P1D means a period of 1 day
                $visite->setDateValidite($date);
                $visite->setNumeroCertificat('BKO'.\time());
                $visite->getVehicule()->setDateProchaineVisite($date->format('Y-m-d'));
                $visite->getVehicule()->setCompteurRevisite(0);
            }else{
                $visite->setStatut(3);
                $visite->getVehicule()->incrementerCompteurRevisite();
            }
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
            return $this->render('visite/show.html.twig', array(
                'controles' => $controles,
                'visite' => $visite,
                'dateRevisite' => $date2,
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
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }  
        if($vehicule->visiteArrive()){
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
            switch(\AppBundle\Utilities\Utilities::evaluerDemandeVisite($derniereVisite)){
                case 1:  
                    $this->get('session')->getFlashBag()->add('notice', 'Visite déjà en cours.');
                    return $this->render('visite/visite.html.twig', array('visite' => $derniereVisite,));
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', "Vérifier le certificat de visite technique. La date de la prochaine visite n'est pas arrivée.");
            return $this->redirectToRoute('vehicule_index');
        }
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesOuvertes();
        $chaineOptimale = \AppBundle\Utilities\Utilities::trouverChaineOptimale($chaines, $em);
        if($chaineOptimale != null){
            $visite = new Visite();
            $visite->setContreVisite(true);
            $visite->setStatut(1);
            $visite->aiguiller($vehicule, 0, $chaineOptimale, null, $centre);
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
}
