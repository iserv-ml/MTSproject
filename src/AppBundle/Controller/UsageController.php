<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage controller.
 *
 * @Route("admin/parametres/usage")
 */
class UsageController extends Controller
{
    /**
     * Lists all usage entities.
     *
     * @Route("/", name="admin_parametres_usage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('usage/index.html.twig');
    }

    /**
     * Creates a new usage entity.
     *
     * @Route("/new", name="admin_parametres_usage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $usage = new Usage();
        $form = $this->createForm('AppBundle\Form\UsageType', $usage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usage);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_usage_show', array('id' => $usage->getId()));
        }

        return $this->render('usage/new.html.twig', array(
            'usage' => $usage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a usage entity.
     *
     * @Route("/{id}", name="admin_parametres_usage_show")
     * @Method("GET")
     */
    public function showAction(Usage $usage)
    {
        if (!$usage) {
            throw $this->createNotFoundException("L'usage demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($usage);

        return $this->render('usage/show.html.twig', array(
            'usage' => $usage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing usage entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_usage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Usage $usage)
    {
        if (!$usage) {
            throw $this->createNotFoundException("L'usage demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($usage);
        $editForm = $this->createForm('AppBundle\Form\UsageType', $usage);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_usage_edit', array('id' => $usage->getId()));
        }

        return $this->render('usage/edit.html.twig', array(
            'usage' => $usage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a usage entity.
     *
     * @Route("/{id}", name="admin_parametres_usage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Usage $usage)
    {
        if (!$usage) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($usage->estSupprimable()) {
            $form = $this->createDeleteForm($usage);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($usage);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cet usage car il est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_usage_index');
    }
    
    /**
     * Deletes a usage entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_usage_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Usage $usage)
    {
        if (!$usage) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($usage->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($usage);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cet usage car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_usage_index');
    }

    /**
     * Creates a form to delete a usage entity.
     *
     * @param Usage $usage The usage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usage $usage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_usage_delete', array('id' => $usage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Usage entities.
     *
     * @Route("/admin/parametres/usageajax/liste", name="usageajax")
     * 
     * 
     */
    public function usageAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.code', 'm.libelle');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Usage')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Usage')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['code'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_usage_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_usage_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_usage_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Usage entities.
     *
     * @Route("/admin/parametres/usage/download", name="usage_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Usage')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Usage".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Usage'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");$col++;
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());
            $ligne++;
        }
    }
}
