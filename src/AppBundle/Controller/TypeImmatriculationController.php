<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeImmatriculation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Typeimmatriculation controller.
 *
 * @Route("admin/parametres/typeimmatriculation")
 */
class TypeImmatriculationController extends Controller
{
    /**
     * Lists all typeImmatriculation entities.
     *
     * @Route("/", name="admin_parametres_typeimmatriculation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('typeimmatriculation/index.html.twig');
    }

    /**
     * Creates a new typeImmatriculation entity.
     *
     * @Route("/new", name="admin_parametres_typeimmatriculation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeImmatriculation = new Typeimmatriculation();
        $form = $this->createForm('AppBundle\Form\TypeImmatriculationType', $typeImmatriculation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeImmatriculation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typeimmatriculation_show', array('id' => $typeImmatriculation->getId()));
        }

        return $this->render('typeimmatriculation/new.html.twig', array(
            'typeImmatriculation' => $typeImmatriculation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeImmatriculation entity.
     *
     * @Route("/{id}", name="admin_parametres_typeimmatriculation_show")
     * @Method("GET")
     */
    public function showAction(TypeImmatriculation $typeImmatriculation)
    {
        if (!$typeImmatriculation) {
            throw $this->createNotFoundException("Le type d'immatriculation demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeImmatriculation);

        return $this->render('typeimmatriculation/show.html.twig', array(
            'typeImmatriculation' => $typeImmatriculation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeImmatriculation entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_typeimmatriculation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeImmatriculation $typeImmatriculation)
    {
        if (!$typeImmatriculation) {
            throw $this->createNotFoundException("Le type d'immatriculation demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeImmatriculation);
        $editForm = $this->createForm('AppBundle\Form\TypeImmatriculationType', $typeImmatriculation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typeimmatriculation_edit', array('id' => $typeImmatriculation->getId()));
        }

        return $this->render('typeimmatriculation/edit.html.twig', array(
            'typeImmatriculation' => $typeImmatriculation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeImmatriculation entity.
     *
     * @Route("/{id}", name="admin_parametres_typeimmatriculation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeImmatriculation $typeImmatriculation)
    {
        if (!$typeImmatriculation) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($typeImmatriculation->estSupprimable()) {
            $form = $this->createDeleteForm($typeImmatriculation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($typeImmatriculation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', "Impossible de supprimer  ce type d'immatriculation car il est utilisé.");
        }

        return $this->redirectToRoute('admin_parametres_typeimmatriculation_index');
    }
    
    /**
     * Deletes a typeImmatriculation entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_typeImmatriculation_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(TypeImmatriculation $typeImmatriculation)
    {
        if (!$typeImmatriculation) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($typeImmatriculation->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeImmatriculation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
        }else{
            $this->get('session')->getFlashBag()->add('error', "Impossible de supprimer  ce type d'immatriculation car il est utilisé.");
        }
        return $this->redirectToRoute('admin_parametres_typeimmatriculation_index');
    }

    /**
     * Creates a form to delete a typeImmatriculation entity.
     *
     * @param TypeImmatriculation $typeImmatriculation The typeImmatriculation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeImmatriculation $typeImmatriculation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_typeimmatriculation_delete', array('id' => $typeImmatriculation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all TypeImmatriculation entities.
     *
     * @Route("/admin/parametres/typeimmatriculationajax/liste", name="typeimmatriculationajax")
     * 
     * 
     */
    public function typeimmatriculationAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.description', 'r.code');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:TypeImmatriculation')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:TypeImmatriculation')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['description'],$aRow['code'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_typeimmatriculation_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_typeimmatriculation_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_typeimmatriculation_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all TypeImmatriculation entities.
     *
     * @Route("/admin/parametres/typeimmatriculation/download", name="typeimmatriculation_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:TypeImmatriculation')->findAll();
        $date = new \DateTime("now");
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("TYPEIMMATRICULATION_".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'TYPEIMMATRICULATION_'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DESCRIPTION");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDescription());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());
            $ligne++;
        }
    }
}
