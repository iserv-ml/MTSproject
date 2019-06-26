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
         return $this->render('visite/quittance.html.twig');
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
        return $this->render('visite/controles.html.twig');
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
        $vehicule = $em->getRepository('AppBundle:Vehicule')->find($request->get('id'));
        if(!$vehicule){
            throw $this->createNotFoundException("Ooops... Une erreur s'est produite.");
        }
        $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($vehicule->getId());
        //$visiteParent = $em->getRepository('AppBundle:Visite')->visiteParent($vehicule->getId());
        /*if($visiteParent == -1){
            throw $this->createNotFoundException("Merci de vérifier l'historique des visites de ce véhicule.");
        }*/
        if(!$derniereVisite || $derniereVisite->getStatut() == 2 || $derniereVisite->getStatut() == 4){
            //il faudra tenir compte de la date de la dernière visite
            $visiteParent = null;
        }elseif($derniereVisite->getStatut() == 3){
            $visiteParent = $derniereVisite;
        }else{
            $this->get('session')->getFlashBag()->add('notice', 'Visite déjà en cours.');
            return $this->render('visite/visite.html.twig', array(
            'visite' => $derniereVisite,
            ));
        }
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
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
        }else{
            throw $this->createNotFoundException("Aucune chaine active. Merci de contacter l'administrateur.");
        }
        if(!$chaineOptimale){
            throw $this->createNotFoundException("CHAINE OPTIMALE");
        }
        $visite = new Visite();
        $visite->aiguiller($vehicule, 0, $chaineOptimale, $visiteParent);
        $em->persist($visite);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Aiguillage effectué.');
        return $this->render('visite/visite.html.twig', array(
            'visite' => $visite,
            ));
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
        }
        return $this->render('visite/show.html.twig', array(
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
            $iTotal = $em->getRepository('AppBundle:Visite')->countQuittanceRows($affectation->getCaisse()->getId());    
        }else{
            $rResult = $em->getRepository('AppBundle:Visite')->findQuittancesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, 0);
            $iTotal = $em->getRepository('AppBundle:Visite')->countQuittanceRows($affectation->getCaisse()->getId());    
        }
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $revisite = $aRow['revisite'] == 1 ? "REVISITE" : "NORMALE";
            $output['aaData'][] = array($aRow['immatriculation'],$aRow['nom'].' '.$aRow['prenom'],$revisite, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER')){
            $action .= " <a title='Quittance' class='btn btn-success' href='".$this->generateUrl('caisse_quittance_creer', array('id'=> $id ))."'><i class='fa fa-credit-card' ></i></a>";
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
        if($admin){
            $rResult = $em->getRepository('AppBundle:Visite')->findControlesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, 0);
            $iTotal = $em->getRepository('AppBundle:Visite')->countControlesRows(0);    
        }
        else {
            $rResult = $em->getRepository('AppBundle:Visite')->findControlesAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, $affectation->getPiste()->getId());
            $iTotal = $em->getRepository('AppBundle:Visite')->countControlesRows($affectation->getPiste()->getId());
        }
        
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererPisteAction($aRow['id'], $aRow['statut']);
            $revisite = $aRow['revisite'] == 1 ? "REVISITE" : "NORMALE";
            if($aRow['statut'] == 1){
                $etat = "A CONTROLER";
            }else if($aRow['statut'] == 2){
                $etat = "SUCCESS";
            }else if($aRow['statut'] == 3){
                $etat = "ECHEC";
            }
            $output['aaData'][] = array($aRow['immatriculation'],$aRow['nom'].' '.$aRow['prenom'],$revisite,$etat, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererPisteAction($id, $statut){
        $action = "";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CONTROLLEUR')){
            if($statut == 1){
                $action .= " <a title='Controller' class='btn btn-success' href='".$this->generateUrl('visite_controleur', array('id'=> $id ))."'><i class='fa fa-config' ></i> Controler</a>";
            }elseif($statut > 1){
                $action .= " <a title='Détail' class='btn btn-success' href='".$this->generateUrl('visite_show', array('id'=> $id ))."'><i class='fa fa-plus' ></i> Voir le rapport</a>";
            }
        }
        return $action;
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
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        $user = $this->container->get('security.context')->getToken()->getUser();
        $affectation = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($user->getId());
        $admin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR');
        if(!$visite || $visite->getStatut() == 0 || (!$admin && $visite->getChaine()->getPiste()->getId()!= $affectation->getPiste()->getId())){
            throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
        }
        $controles = $em->getRepository('AppBundle:Controle')->trouverActif();
        if($request->get('controleur')){
            if($visite->getStatut() != 1 ){
                throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
            }
            $succes = true;
            $date = new \DateTime();
            foreach($controles as $controle){
                if($request->get($controle->getCode())!=null){
                    $resultatMaha = $em->getRepository('AppBundle:CodeMahaResultat')->trouverParControleResultat($controle->getCode(), $request->get($controle->getCode()));
                    if($resultatMaha == null){
                        throw $this->createAccessDeniedException("Cette opération n'est pas autorisée null");
                    }else{
                        $resultat = new \AppBundle\Entity\Resultat();
                        $resultat->generer($visite, $resultatMaha);
                        $em->persist($resultat);
                        $em->flush();
                        if(!$resultatMaha->getReussite()){
                            $succes = false;
                        }
                    }
                }else{
                    throw $this->createAccessDeniedException("Cette opération n'est pas autorisée");
                }
            }
            if($succes){
                $visite->setStatut(2);
            }else{
                $visite->setStatut(3);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Controle terminé. Merci de consulter le résultat');
            if($visite->getStatut()==3){
                $date1 = $visite->getDate()->format('Y-m-d');
                $date = new \DateTime($date1);
                $date->add(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getDelai().'D')); // P1D means a period of 1 day
                $date2 = $date->format('Y-m-d');
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
}
