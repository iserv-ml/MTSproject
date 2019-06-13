<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Caisse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Caisse controller.
 *
 * @Route("admin/parametres/caisse")
 */
class CaisseController extends Controller
{
    /**
     * Lists all caisse entities.
     *
     * @Route("/", name="admin_parametres_caisse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('caisse/index.html.twig');
    }

    /**
     * Creates a new caisse entity.
     *
     * @Route("/new", name="admin_parametres_caisse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $caisse = new Caisse();
        $form = $this->createForm('AppBundle\Form\CaisseType', $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($caisse);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_caisse_show', array('id' => $caisse->getId()));
        }

        return $this->render('caisse/new.html.twig', array(
            'caisse' => $caisse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a caisse entity.
     *
     * @Route("/{id}", name="admin_parametres_caisse_show")
     * @Method("GET")
     */
    public function showAction(Caisse $caisse)
    {
        if (!$caisse) {
            throw $this->createNotFoundException("La caisse demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($caisse);

        return $this->render('caisse/show.html.twig', array(
            'caisse' => $caisse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing caisse entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_caisse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Caisse $caisse)
    {
        if (!$caisse) {
            throw $this->createNotFoundException("La caisse demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($caisse);
        $editForm = $this->createForm('AppBundle\Form\CaisseType', $caisse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_caisse_edit', array('id' => $caisse->getId()));
        }

        return $this->render('caisse/edit.html.twig', array(
            'caisse' => $caisse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a caisse entity.
     *
     * @Route("/{id}", name="admin_parametres_caisse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Caisse $caisse)
    {
        if(!$caisse) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($caisse->estSupprimable()) {
            $form = $this->createDeleteForm($caisse);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($caisse);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette caisse car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_caisse_index');
    }
    
    /**
     * Deletes a caisse entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_caisse_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Caisse $caisse)
    {
        if (!$caisse) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($caisse->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($caisse);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette caisse car elle est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_caisse_index');
    }

    /**
     * Creates a form to delete a caisse entity.
     *
     * @param Caisse $caisse The caisse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Caisse $caisse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_caisse_delete', array('id' => $caisse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Caisse entities.
     *
     * @Route("/admin/parametres/caisseajax/liste", name="caisseajax")
     * 
     * 
     */
    public function caisseAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.numero', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Caisse')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Caisse')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['numero'],$aRow['actif'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_caisse_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_caisse_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_caisse_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Caisse entities.
     *
     * @Route("/admin/parametres/caisse/download", name="caisse_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Caisse')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Caisse".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Caisse'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("NUMERO");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("ACTIF");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getNumero());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getActif());$col++;
            $ligne++;
        }
    }
}
