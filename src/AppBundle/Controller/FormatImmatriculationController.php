<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FormatImmatriculation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Formatimmatriculation controller.
 *
 * @Route("admin/parametres/formatimmatriculation")
 */
class FormatImmatriculationController extends Controller
{
    /**
     * Lists all formatImmatriculation entities.
     *
     * @Route("/", name="admin_parametres_formatimmatriculation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $formatImmatriculations = $em->getRepository('AppBundle:FormatImmatriculation')->findAll();

        return $this->render('formatimmatriculation/index.html.twig', array(
            'formatImmatriculations' => $formatImmatriculations,
        ));
    }

    /**
     * Creates a new formatImmatriculation entity.
     *
     * @Route("/new", name="admin_parametres_formatimmatriculation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $formatImmatriculation = new Formatimmatriculation();
        $form = $this->createForm('AppBundle\Form\FormatImmatriculationType', $formatImmatriculation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formatImmatriculation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_formatimmatriculation_show', array('id' => $formatImmatriculation->getId()));
        }

        return $this->render('formatimmatriculation/new.html.twig', array(
            'formatImmatriculation' => $formatImmatriculation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a formatImmatriculation entity.
     *
     * @Route("/{id}", name="admin_parametres_formatimmatriculation_show")
     * @Method("GET")
     */
    public function showAction(FormatImmatriculation $formatImmatriculation)
    {
        if (!$formatImmatriculation) {
            throw $this->createNotFoundException("Le format demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($formatImmatriculation);

        return $this->render('formatimmatriculation/show.html.twig', array(
            'formatImmatriculation' => $formatImmatriculation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing formatImmatriculation entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_formatimmatriculation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FormatImmatriculation $formatImmatriculation)
    {
        if (!$formatImmatriculation) {
            throw $this->createNotFoundException("Le format demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($formatImmatriculation);
        $editForm = $this->createForm('AppBundle\Form\FormatImmatriculationType', $formatImmatriculation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_formatimmatriculation_edit', array('id' => $formatImmatriculation->getId()));
        }

        return $this->render('formatimmatriculation/edit.html.twig', array(
            'formatImmatriculation' => $formatImmatriculation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a formatImmatriculation entity.
     *
     * @Route("/{id}", name="admin_parametres_formatimmatriculation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FormatImmatriculation $formatImmatriculation)
    {
        if(!$formatImmatriculation) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($formatImmatriculation->estSupprimable()) {
            $form = $this->createDeleteForm($formatImmatriculation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($formatImmatriculation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce format car il est utilisé.');
        }

        return $this->redirectToRoute('admin_parametres_formatimmatriculation_index');
    }
    
    /**
     * Deletes a fomatImmatriculation entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_formatimmatriculation_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(FormatImmatriculation $formatImmatriculation)
    {
        if (!$formatImmatriculation) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($formatImmatriculation->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($formatImmatriculation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette piste car elle est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_formatimmatriculation_index');
    }

    /**
     * Creates a form to delete a formatImmatriculation entity.
     *
     * @param FormatImmatriculation $formatImmatriculation The formatImmatriculation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FormatImmatriculation $formatImmatriculation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_formatimmatriculation_delete', array('id' => $formatImmatriculation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all FormatImmatriculation entities.
     *
     * @Route("/admin/parametres/formatajax/liste", name="formatajax")
     * 
     * 
     */
    public function formatAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 't.code', 'r.presentation', 'r.regex', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:FormatImmatriculation')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:FormatImmatriculation')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:FormatImmatriculation')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $actif = $aRow['actif'] ? 'Actif' : 'Inactif';
            $output['aaData'][] = array($aRow['code'],$aRow['presentation'],$aRow['regex'],$actif, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_formatimmatriculation_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_formatimmatriculation_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_formatimmatriculation_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Piste entities.
     *
     * @Route("/admin/parametres/format/download", name="format_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:FormatImmatriculation')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Format".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Format'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("TYPE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PRESENTATION");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("REGEX");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("ACTIF");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getTypeImmatriculation->getCode());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPresentation());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getRegex());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getActif());
            $ligne++;
        }
    }
}
