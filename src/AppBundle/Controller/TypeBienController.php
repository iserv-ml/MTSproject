<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeBien;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\TypeBienType;

/**
 * Typebien controller.
 *
 * @Route("typebien")
 */
class TypeBienController extends Controller
{
    /**
     * Lists all typeBien entities.
     *
     * @Route("/", name="typebien_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('typebien/index.html.twig');
    }

    /**
     * Creates a new typeBien entity.
     *
     * @Route("/new", name="typebien_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeBien = new Typebien();
        $form = $this->createCreateForm($typeBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeBien);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('typebien_show', array('id' => $typeBien->getId()));
        }

        return $this->render('typebien/new.html.twig', array(
            'typeBien' => $typeBien,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a form to create a TypeBien entity.
     *
     * @param TypeBien $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TypeBien $entity)
    {
        $form = $this->createForm(new TypeBienType(), $entity, array(
            'action' => $this->generateUrl('typebien_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Finds and displays a typeBien entity.
     *
     * @Route("/{id}", name="typebien_show")
     * @Method("GET")
     */
    public function showAction(TypeBien $typeBien)
    {
        if (!$typeBien) {
            throw $this->createNotFoundException("Le type de bien demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeBien);

        return $this->render('typebien/show.html.twig', array(
            'typeBien' => $typeBien,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeBien entity.
     *
     * @Route("/{id}/edit", name="typebien_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeBien $typeBien)
    {
        if (!$typeBien) {
            throw $this->createNotFoundException("Le type de bien demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeBien);
        $editForm = $this->createEditForm($typeBien);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('typebien_edit', array('id' => $typeBien->getId()));
        }

        return $this->render('typebien/edit.html.twig', array(
            'typeBien' => $typeBien,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeBien entity.
     *
     * @Route("/{id}", name="typebien_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeBien $typeBien)
    {
        $form = $this->createDeleteForm($typeBien);
        $form->handleRequest($request);

        if ($typeBien->estSupprimable() && $form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeBien);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'La suppression est effective');
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce type de bien.');
        }

        return $this->redirectToRoute('typebien_index');
    }

    /**
     * Creates a form to delete a typeBien entity.
     *
     * @param TypeBien $typeBien The typeBien entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeBien $typeBien)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typebien_delete', array('id' => $typeBien->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all typeBien entities.
     *
     * @Route("/typebienajax/liste", name="typebienajax")
     * @Method("GET")
     * 
     */
    public function typebienAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.code', 'r.dateCreation', 'r.dateModification');
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
        
        $rResult = $em->getRepository('AppBundle:TypeBien')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:TypeBien')->countRows();
	
	$output = array(
                "sEcho" => intval($request->get('sEcho')),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
	);
        $i = 1;
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($i,$aRow['code'],$aRow['libelle'],$aRow['dateCreation'],$aRow['dateModification'], $action);
            $i++;
	}
	return new Response(json_encode( $output )); 
    }
    
    private function genererAction($id){
        $action = "<a href='".$this->generateUrl('typebien_show', array('id'=> $id ))."'>Détail</a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= "<br/><a href='".$this->generateUrl('typebien_edit', array('id'=> $id ))."'>Modifier</a>";
                $action .= "<br/><a href='".$this->generateUrl('typebien_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'>Supprimer</a>";
        }
        return $action;
    }
    
    /**
     * Export all TypeBien entities.
     *
     * @Route("/export/download", name="typebien_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $incidents = $em->getRepository('AppBundle:TypeBien')->findAll();
        $date = new \DateTime("now");
        // ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("TYPE_BIEN".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $incidents);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $filename = 'TYPE_BIEN'.$date->format('Y_m_d_H_i_s').'.xls';
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;        
    }

    
    private function writeRapport($phpExcelObject, $incidents) {
        $phpExcelObject->setActiveSheetIndex(0);
        $objWorksheet = $phpExcelObject->getActiveSheet();
        $objWorksheet->getCellByColumnAndRow(0, 1)->setValue("ID");
        $objWorksheet->getCellByColumnAndRow(1, 1)->setValue("CODE");
        $objWorksheet->getCellByColumnAndRow(2, 1)->setValue("LIBELLE");
        $objWorksheet->getCellByColumnAndRow(3, 1)->setValue("DATE DE CREATION");
        $objWorksheet->getCellByColumnAndRow(4, 1)->setValue("DERNIERE MODIFICATION");
        $objWorksheet->getCellByColumnAndRow(5, 1)->setValue("CREE PAR");
        $objWorksheet->getCellByColumnAndRow(6, 1)->setValue("MODIFIE PAR");
        $objWorksheet->getCellByColumnAndRow(7, 1)->setValue("DATE DE SUPRESSION");
        $i =2;

        foreach($incidents as $incident){
            $objWorksheet->getCellByColumnAndRow(0, $i)->setValue($incident->getId());
            $objWorksheet->getCellByColumnAndRow(1, $i)->setValue($incident->getCode());
            $objWorksheet->getCellByColumnAndRow(2, $i)->setValue($incident->getLibelle());
            if($incident->getDateCreation()!=null)
                $objWorksheet->getCellByColumnAndRow(3, $i)->setValue($incident->getDateCreation()->format('Y-m-d H:i:s'));
            if($incident->getDateModification()!=null)
                $objWorksheet->getCellByColumnAndRow(4, $i)->setValue($incident->getDateModification()->format('Y-m-d H:i:s'));
            $objWorksheet->getCellByColumnAndRow(5, $i)->setValue($incident->getCreePar());
            $objWorksheet->getCellByColumnAndRow(6, $i)->setValue($incident->getModifierPar());
            if($incident->getDeletedAt()!=null)
                $objWorksheet->getCellByColumnAndRow(7, $i)->setValue($incident->getDeletedAt()->format('Y-m-d H:i:s'));
            
            $i++;
        }
    }
    
    /**
    * Creates a form to edit a TypeBien entity.
    *
    * @param Commune $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeBien $entity)
    {
        $form = $this->createForm(new TypeBienType(), $entity, array(
            'action' => $this->generateUrl('typebien_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }
}
