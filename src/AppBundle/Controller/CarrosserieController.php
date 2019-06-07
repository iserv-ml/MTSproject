<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Carrosserie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Carrosserie controller.
 *
 * @Route("admin/parametres/carrosserie")
 */
class CarrosserieController extends Controller
{
    /**
     * Lists all carrosserie entities.
     *
     * @Route("/", name="admin_parametres_carrosserie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('carrosserie/index.html.twig');
    }

    /**
     * Creates a new carrosserie entity.
     *
     * @Route("/new", name="admin_parametres_carrosserie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $carrosserie = new Carrosserie();
        $form = $this->createForm('AppBundle\Form\CarrosserieType', $carrosserie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($carrosserie);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_carrosserie_show', array('id' => $carrosserie->getId()));
        }

        return $this->render('carrosserie/new.html.twig', array(
            'carrosserie' => $carrosserie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a carrosserie entity.
     *
     * @Route("/{id}", name="admin_parametres_carrosserie_show")
     * @Method("GET")
     */
    public function showAction(Carrosserie $carrosserie)
    {
        if (!$carrosserie) {
            throw $this->createNotFoundException("La carrosserie demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($carrosserie);

        return $this->render('carrosserie/show.html.twig', array(
            'carrosserie' => $carrosserie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing carrosserie entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_carrosserie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Carrosserie $carrosserie)
    {
        if (!$carrosserie) {
            throw $this->createNotFoundException("La carrosserie demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($carrosserie);
        $editForm = $this->createForm('AppBundle\Form\CarrosserieType', $carrosserie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_carrosserie_edit', array('id' => $carrosserie->getId()));
        }

        return $this->render('carrosserie/edit.html.twig', array(
            'carrosserie' => $carrosserie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a carrosserie entity.
     *
     * @Route("/{id}", name="admin_parametres_carrosserie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Carrosserie $carrosserie)
    {
        if(!$carrosserie) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($carrosserie->estSupprimable()) {
            $form = $this->createDeleteForm($carrosserie);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($carrosserie);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette carrosserie car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_carrosserie_index');
    }
    
    /**
     * Deletes a carrosserie entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_carrosserie_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Carrosserie $carrosserie)
    {
        if (!$carrosserie) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($carrosserie->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($carrosserie);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette carrosserie car elle est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_carrosserie_index');
    }

    /**
     * Creates a form to delete a carrosserie entity.
     *
     * @param Carrosserie $carrosserie The carrosserie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Carrosserie $carrosserie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_carrosserie_delete', array('id' => $carrosserie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Carrosserie entities.
     *
     * @Route("/admin/parametres/carrosserieajax/liste", name="carrosserieajax")
     * 
     * 
     */
    public function carrosserieAjaxAction(Request $request)
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
        $rResult = $em->getRepository('AppBundle:Carrosserie')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Carrosserie')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['code'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_carrosserie_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_carrosserie_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_carrosserie_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Carrosserie entities.
     *
     * @Route("/admin/parametres/carrosserie/download", name="carrosserie_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Carrosserie')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Carrosserie".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Carrosserie'.$date->format('Y_m_d_H_i_s').'.xls';
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
