<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Penalite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Penalite controller.
 *
 * @Route("admin/parametres/penalite")
 */
class PenaliteController extends Controller
{
    /**
     * Lists all penalite entities.
     *
     * @Route("/", name="admin_parametres_penalite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('penalite/index.html.twig');
    }

    /**
     * Creates a new penalite entity.
     *
     * @Route("/new", name="admin_parametres_penalite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $penalite = new Penalite();
        $form = $this->createForm('AppBundle\Form\PenaliteType', $penalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($penalite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_penalite_show', array('id' => $penalite->getId()));
        }

        return $this->render('penalite/new.html.twig', array(
            'penalite' => $penalite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a penalite entity.
     *
     * @Route("/{id}", name="admin_parametres_penalite_show")
     * @Method("GET")
     */
    public function showAction(Penalite $penalite)
    {
        if (!$penalite) {
            throw $this->createNotFoundException("La pénalité demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($penalite);

        return $this->render('penalite/show.html.twig', array(
            'penalite' => $penalite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing penalite entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_penalite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Penalite $penalite)
    {
        if (!$penalite) {
            throw $this->createNotFoundException("La pénalité demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($penalite);
        $editForm = $this->createForm('AppBundle\Form\PenaliteType', $penalite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_penalite_edit', array('id' => $penalite->getId()));
        }

        return $this->render('penalite/edit.html.twig', array(
            'penalite' => $penalite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a penalite entity.
     *
     * @Route("/{id}", name="admin_parametres_penalite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Penalite $penalite)
    {
        if (!$penalite) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($penalite->estSupprimable()) {
            $form = $this->createDeleteForm($penalite);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($penalite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette pénalité car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_penalite_index');
    }
    
        /**
     * Deletes a penalite entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_penalite_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Penalite $penalite)
    {
        if (!$penalite) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($penalite->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($penalite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette pénalité car elle est utilisée');
        }
        return $this->redirectToRoute('admin_parametres_penalite_index');
    }

    /**
     * Creates a form to delete a penalite entity.
     *
     * @param Penalite $penalite The penalite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Penalite $penalite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_penalite_delete', array('id' => $penalite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Penalite entities.
     *
     * @Route("/admin/parametres/penaliteajax/liste", name="penaliteajax")
     * 
     * 
     */
    public function penaliteAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.dureeMin', 'r.dureeMax', 'r.pourcentage');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Penalite')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Penalite')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['dureeMin'],$aRow['dureeMax'],$aRow['pourcentage'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_penalite_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_penalite_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_penalite_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Penalite entities.
     *
     * @Route("/admin/parametres/penalite/download", name="penalite_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Penalite')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("PENALITE".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'PENALITE'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DUREE MIN");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DUREE MAX");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("POURCENTAGE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDureeMin());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDureeMax());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPourcentage());
            $ligne++;
        }
    }
}
