<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bien;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\BienType;
use AppBundle\Entity\Region;

/**
 * Bien controller.
 *
 * @Route("bien")
 */
class BienController extends Controller
{
    /**
     * Lists all bien entities.
     *
     * @Route("/", name="bien_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('bien/index.html.twig');
    }

    /**
     * Creates a new bien entity.
     *
     * @Route("/new/{proprietaireidd}", name="bien_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $proprietaireidd = NULL)
    {
        $bien = new Bien();
        $form = $this->createCreateForm($bien);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if($proprietaireidd != NULL){
           $proprietaire =  $em->getRepository('AppBundle:Proprietaire')->find($proprietaireidd);
           $form->get("searchproprio")->setData($proprietaire->getNomComplet());
           $form->get("proprietaireId")->setData($proprietaire->getId());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            
            $proprietaire = $em->getRepository('AppBundle:Proprietaire')->find($bien->getProprietaireId());
            if(!$proprietaire){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le propriétaire avec le champ autocoplete");
            }
            if($bien->getParentid() != -1){
                $parent = $em->getRepository('AppBundle:Bien')->find($bien->getParentid());
                if(!$parent){
                    throw $this->createNotFoundException("Oopps. Veuillez choisir l'immeuble avec le champ autocoplete");
                }
                $bien->setParent($parent);
            }
            $bien->setProprietaire($proprietaire);
            $bien->setStatut("DISPONIBLE");
            $em->persist($bien);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('bien_show', array('id' => $bien->getId()));
        }

        return $this->render('bien/new.html.twig', array(
            'bien' => $bien,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new bien entity de type appart.
     *
     * @Route("/appart/{id}", name="appart_new")
     * @Method({"GET", "POST"})
     */
    public function appartAction(Request $request, Bien $immeuble)
    {
        if (!$immeuble) {
            throw $this->createNotFoundException("L'immeuble demandé n'est pas disponible.");
        }
        $bien = new Bien();
        $em = $this->getDoctrine()->getManager();
        $typeBien = $em->getRepository('AppBundle:TypeBien')->findByCode("APPARTEMENT");
        $bien->setTypeBien($typeBien);
        $bien->setSearchparent($immeuble->getLibelle());
        $bien->setParentid($immeuble->getId());
        $bien->setAdresse($immeuble->getAdresse());
        $bien->setSearchproprio($immeuble->getProprietaire()->getNomComplet());
        $bien->setProprietaireId($immeuble->getProprietaire()->getId());
        $form = $this->createCreateForm($bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $proprietaire = $em->getRepository('AppBundle:Proprietaire')->find($bien->getProprietaireId());
            if(!$proprietaire){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le propriétaire avec le champ autocoplete");
            }
            if($bien->getParentid() != -1){
                $parent = $em->getRepository('AppBundle:Bien')->find($bien->getParentid());
                if(!$parent){
                    throw $this->createNotFoundException("Oopps. Veuillez choisir l'immeuble avec le champ autocoplete");
                }
                $bien->setParent($parent);
            }
            $bien->setProprietaire($proprietaire);
            $bien->setStatut("DISPONIBLE");
            $em->persist($bien);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('bien_show', array('id' => $bien->getId()));
        }

        return $this->render('bien/new.html.twig', array(
            'bien' => $bien,
            'form' => $form->createView(),
        ));
    }
    
     /**
     * Creates a form to create a Bien entity.
     *
     * @param Bien $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Bien $entity)
    {
        $form = $this->createForm(new BienType(), $entity, array(
            'action' => $this->generateUrl('bien_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Finds and displays a bien entity.
     *
     * @Route("/{id}", name="bien_show")
     * @Method("GET")
     */
    public function showAction(Bien $bien)
    {
        if (!$bien) {
            throw $this->createNotFoundException("Le bien demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($bien);

        return $this->render('bien/show.html.twig', array(
            'bien' => $bien,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bien entity.
     *
     * @Route("/{id}/edit", name="bien_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bien $bien)
    {
        if (!$bien) {
            throw $this->createNotFoundException("Le bien demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($bien);
        $editForm = $this->createEditForm($bien);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $proprietaire = $em->getRepository('AppBundle:Proprietaire')->find($bien->getProprietaireId());
            if(!$proprietaire){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le propriétaire avec le champ autocoplete");
            }
            if($bien->getParentid() != -1){
                $parent = $em->getRepository('AppBundle:Bien')->find($bien->getParentid());
                if(!$parent){
                    throw $this->createNotFoundException("Oopps. Veuillez choisir l'immeuble avec le champ autocoplete");
                }
                $bien->setParent($parent);
            }
            $bien->setProprietaire($proprietaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('bien_edit', array('id' => $bien->getId()));
        }

        return $this->render('bien/edit.html.twig', array(
            'bien' => $bien,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a bien entity.
     *
     * @Route("/{id}", name="bien_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bien $bien)
    {
        $form = $this->createDeleteForm($bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bien);
            $em->flush();
        }

        return $this->redirectToRoute('bien_index');
    }

    /**
     * Creates a form to delete a bien entity.
     *
     * @param Bien $bien The bien entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bien $bien)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bien_delete', array('id' => $bien->getId())))
            ->setMethod('DELETE')->add('submit', 'submit', array('label' => 'SUPPRIMER'))
            ->getForm()
        ;
    }
    
    /**
     * Lists all Bien entities.
     *
     * @Route("/bienajax/liste", name="bienajax")
     * @Method("GET")
     * 
     */
    public function bienAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.statut', 'r.typeAffaire', 'r.typeBien.libelle');
        $start = $request->get('iDisplayStart');
        $start = ($start != NULL && intval($start) > 0) ? intval($start) : 0;
        $end = $request->get('iDisplayLength');
        $end = ($end != NULL && intval($end) > 50) ? intval($end) : 50;
        $sCol = $request->get('iSortCol_0');
        $sCol = ($sCol != NULL && intval($sCol) > 0 && intval($sCol) < 4) ? intval($sCol)-1 : 0;
        $sdir = $request->get('sSortDir_0');
        $sdir = ($sdir!=NULL && $sdir=='asc') ? 'asc' : 'desc';
	$searchTerm = $request->get('sSearch');
        $searchTerm = ($searchTerm!=NULL && $searchTerm!= '') ? $searchTerm : NULL;
        
        $rResult = $em->getRepository('AppBundle:Bien')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Bien')->countRows();
	
	$output = array(
                "sEcho" => intval($request->get('sEcho')),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
	);
        $i = 1;
	foreach ( $rResult as  $aRow )
	{
            $appart = ($aRow['tcode'] == "IMMEUBLE") ? $em->getRepository('AppBundle:Bien')->countApparts($aRow['id']) : 0;
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($i,$aRow['libelle'],$aRow['statut'],$aRow['typeAffaire'],$aRow['tlibelle'], $appart, $action);
            $i++;
	}
	return new Response(json_encode( $output )); 
    }
    
    private function genererAction($id){
        $action = "<a href='".$this->generateUrl('bien_show', array('id'=> $id ))."'>Détail</a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= "<br/><a href='".$this->generateUrl('bien_edit', array('id'=> $id ))."'>Modifier</a>";
                $action .= "<br/><a href='".$this->generateUrl('bien_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'>Supprimer</a>";
        }
        return $action;
    }
    
    /**
     * Export all Bien entities.
     *
     * @Route("/export/download", name="bien_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $incidents = $em->getRepository('AppBundle:Bien')->findAll();
        $date = new \DateTime("now");
        // ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("BIEN_".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $incidents);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $filename = 'BIEN_'.$date->format('Y_m_d_H_i_s').'.xls';
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;        
    }

    
    private function writeRapport($phpExcelObject, $incidents) {
        $phpExcelObject->setActiveSheetIndex(0);
        $i = 0;
        $objWorksheet = $phpExcelObject->getActiveSheet();
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("TYPE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("LIBELLE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("ADRESSE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("STATUT");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("TYPE AFFAIRE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("PRIX DE VENTE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("PRIX DU LOYER");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("% AGENCE");$i++;
        $i =2;

        foreach($incidents as $incident){
            $j=0;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getTypeBien()->getLibelle());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getLibelle());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getAdresse());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getStatut());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getTypeAffaire());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getMontant());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getLoyer());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue("");$j++;            
            $i++;
        }
    }
    
    /**
    * Creates a form to edit a TypePiece entity.
    *
    * @param Commune $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Bien $entity)
    {
        $form = $this->createForm(new BienType(), $entity, array(
            'action' => $this->generateUrl('bien_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        $form->add('statut');
        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        if($entity->getProprietaire() != null){
            $form->get("searchproprio")->setData($entity->getProprietaire()->getNomComplet());
            $form->get("proprietaireId")->setData($entity->getProprietaire()->getId());
        }
        if($entity->getParent() != null){
            $form->get("searchparent")->setData($entity->getParent()->getLibelle());
            $form->get("parentid")->setData($entity->getParent()->getId());
        }
        return $form;
    }
    
    /**
     * @Route("/ajax/searchparent", name="parents_ajax")
     */
    public function parentsAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('AppBundle:Bien')->findParentAjax($search, $maxRows);
        return new Response(json_encode($clients));
    }
    
    /**
     * @Route("/ajax/search", name="biens_ajax")
     */
    public function biensAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('AppBundle:Bien')->findDispoAjax($search, $maxRows);
        return new Response(json_encode($clients));
    }
    
    /**
     * @Route("/trouver/cercle", name="cercles_ajax")
     */
    public function cerclesAjaxAction(Request $request){  
        
        $region = $request->get('id', '');
        $em = $this->getDoctrine()->getManager();
        $cercles = $em->getRepository('AppBundle:Cercle')->trouverParRegion($region);
        $select = '<select id="appbundle_bien_cercle" required="required" name="appbundle_bien[cercle]"><option value="">Choisir un cercle</option>';
        if($cercles != null && !empty($cercles)){
            foreach($cercles as $cercle){
                $select .= '<option value="'.$cercle->getId().'">'.$cercle->getLibelle().'</option>';
            }
        }
        $select .= "</select>";
        return new Response($select);
    }
}
