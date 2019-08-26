<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypePiece;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Typepiece controller.
 *
 * @Route("admin/parametres/typepiece")
 */
class TypePieceController extends Controller
{
    /**
     * Lists all typePiece entities.
     *
     * @Route("/", name="admin_parametres_typepiece_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('typePiece/index.html.twig');
    }

    /**
     * Creates a new typePiece entity.
     *
     * @Route("/new", name="admin_parametres_typepiece_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typePiece = new Typepiece();
        $form = $this->createForm('AppBundle\Form\TypePieceType', $typePiece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typePiece);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typepiece_show', array('id' => $typePiece->getId()));
        }

        return $this->render('typepiece/new.html.twig', array(
            'typePiece' => $typePiece,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typePiece entity.
     *
     * @Route("/{id}", name="admin_parametres_typepiece_show")
     * @Method("GET")
     */
    public function showAction(TypePiece $typePiece)
    {
        if (!$typePiece) {
            throw $this->createNotFoundException("Le type de pièce demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typePiece);

        return $this->render('typepiece/show.html.twig', array(
            'typePiece' => $typePiece,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typePiece entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_typepiece_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypePiece $typePiece)
    {
        if (!$typePiece) {
            throw $this->createNotFoundException("Le type de pièce demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typePiece);
        $editForm = $this->createForm('AppBundle\Form\TypePieceType', $typePiece);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typepiece_edit', array('id' => $typePiece->getId()));
        }

        return $this->render('typepiece/edit.html.twig', array(
            'typePiece' => $typePiece,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typePiece entity.
     *
     * @Route("/{id}", name="admin_parametres_typepiece_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypePiece $typePiece)
    {
        if (!$typePiece) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($typePiece->estSupprimable()) {
            $form = $this->createDeleteForm($typePiece);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($typePiece);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce type de pièce car il est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_typepiece_index');
    }
    
    /**
     * Deletes a typePiece entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_typepiece_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(TypePiece $typePiece)
    {
        if (!$typePiece) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($typePiece->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($typePiece);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce type de pièce car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_typepiece_index');
    }

    /**
     * Creates a form to delete a typePiece entity.
     *
     * @param TypePiece $typePiece The typePiece entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypePiece $typePiece)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_typepiece_delete', array('id' => $typePiece->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all TypePiece entities.
     *
     * @Route("/admin/parametres/typepieceajax/liste", name="typepieceajax")
     * 
     * 
     */
    public function typepieceAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.code');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:TypePiece')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:TypePiece')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['code'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_typepiece_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_typepiece_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_typepiece_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all TypePiece entities.
     *
     * @Route("/admin/parametres/typepiece/download", name="typepiece_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:TypePiece')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("TYPEPIECE".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'TYPEPIECE'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("LIBELLE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());$col++;
            $ligne++;
        }
    }
    
}
