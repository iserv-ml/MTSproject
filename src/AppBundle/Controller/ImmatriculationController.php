<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Immatriculation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Immatriculation controller.
 *
 * @Route("admin/parametres/immatriculation")
 */
class ImmatriculationController extends Controller
{
    /**
     * Lists all immatriculation entities.
     *
     * @Route("/", name="admin_parametres_immatriculation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('immatriculation/index.html.twig');
    }

    /**
     * Creates a new immatriculation entity.
     *
     * @Route("/new", name="admin_parametres_immatriculation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $immatriculation = new Immatriculation();
        $form = $this->createForm('AppBundle\Form\ImmatriculationType', $immatriculation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($immatriculation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_immatriculation_show', array('id' => $immatriculation->getId()));
        }

        return $this->render('immatriculation/new.html.twig', array(
            'immatriculation' => $immatriculation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a immatriculation entity.
     *
     * @Route("/{id}", name="admin_parametres_immatriculation_show")
     * @Method("GET")
     */
    public function showAction(Immatriculation $immatriculation)
    {
        if (!$immatriculation) {
            throw $this->createNotFoundException("L'immatriculation demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($immatriculation);

        return $this->render('immatriculation/show.html.twig', array(
            'immatriculation' => $immatriculation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing immatriculation entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_immatriculation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Immatriculation $immatriculation)
    {
        $deleteForm = $this->createDeleteForm($immatriculation);
        $editForm = $this->createForm('AppBundle\Form\ImmatriculationType', $immatriculation);
        $editForm->handleRequest($request);
        if (!$immatriculation) {
            throw $this->createNotFoundException("L'immatriculation demandée n'est pas disponible.");
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_immatriculation_edit', array('id' => $immatriculation->getId()));
        }

        return $this->render('immatriculation/edit.html.twig', array(
            'immatriculation' => $immatriculation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a immatriculation entity.
     *
     * @Route("/{id}", name="admin_parametres_immatriculation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Immatriculation $immatriculation)
    {
        if (!$immatriculation) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($immatriculation->estSupprimable()) {
            $form = $this->createDeleteForm($immatriculation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($immatriculation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette immatriculation car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_immatriculation_index');
    }
    
    /**
     * Deletes a immatriculation entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_immatriculation_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Immatriculation $immatriculation)
    {
        if (!$immatriculation) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($immatriculation->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($immatriculation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette immatriculation car elle est utilisée.');
        }
        return $this->redirectToRoute('admin_parametres_immatriculation_index');
    }

    /**
     * Creates a form to delete a immatriculation entity.
     *
     * @param Immatriculation $immatriculation The immatriculation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Immatriculation $immatriculation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_immatriculation_delete', array('id' => $immatriculation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Immatriculation entities.
     *
     * @Route("/admin/parametres/immatriculationajax/liste", name="immatriculationajax")
     * 
     * 
     */
    public function immatriculationAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.code');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Immatriculation')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Immatriculation')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Immatriculation')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['code'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_immatriculation_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_immatriculation_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_immatriculation_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Immatriculation entities.
     *
     * @Route("/admin/parametres/immatriculation/download", name="immatriculation_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Immatriculation')->findAll();
        $date = new \DateTime("now");
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("IMMATRICULATION_".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'IMMATRICULATION_'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");$col++;
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());$col++;
            $ligne++;
        }
    }
}
