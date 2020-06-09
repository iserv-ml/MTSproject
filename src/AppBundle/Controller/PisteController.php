<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Piste;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Piste controller.
 *
 * @Route("admin/parametres/piste")
 */
class PisteController extends Controller
{
    /**
     * Lists all piste entities.
     *
     * @Route("/", name="admin_parametres_piste_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('piste/index.html.twig');
    }

    /**
     * Creates a new piste entity.
     *
     * @Route("/new", name="admin_parametres_piste_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $piste = new Piste();
        $form = $this->createForm('AppBundle\Form\PisteType', $piste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($piste);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_piste_show', array('id' => $piste->getId()));
        }

        return $this->render('piste/new.html.twig', array(
            'piste' => $piste,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a piste entity.
     *
     * @Route("/{id}", name="admin_parametres_piste_show")
     * @Method("GET")
     */
    public function showAction(Piste $piste)
    {
        if (!$piste) {
            throw $this->createNotFoundException("La piste demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($piste);

        return $this->render('piste/show.html.twig', array(
            'piste' => $piste,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing piste entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_piste_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Piste $piste)
    {
        if (!$piste) {
            throw $this->createNotFoundException("La piste demandée n'est pas disponible.");
        }
        
        $deleteForm = $this->createDeleteForm($piste);
        $editForm = $this->createForm('AppBundle\Form\PisteType', $piste);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if(!$piste->getActif() && $piste->chaineActiveOuAffectation()){
                $this->get('session')->getFlashBag()->add('notice', 'Impossible de désactiver cette piste. Vérifier les chaines et les affectations actives');
                return $this->redirectToRoute('admin_parametres_piste_edit', array('id' => $piste->getId()));
            }
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_piste_edit', array('id' => $piste->getId()));
        }

        return $this->render('piste/edit.html.twig', array(
            'piste' => $piste,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a piste entity.
     *
     * @Route("/{id}", name="admin_parametres_piste_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Piste $piste)
    {
        if(!$piste) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($piste->estSupprimable()) {
            $form = $this->createDeleteForm($piste);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($piste);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette piste car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_piste_index');
    }
    
    /**
     * Deletes a piste entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_piste_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Piste $piste)
    {
        if (!$piste) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($piste->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($piste);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette piste car elle est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_piste_index');
    }

    /**
     * Creates a form to delete a piste entity.
     *
     * @param Piste $piste The piste entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Piste $piste)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_piste_delete', array('id' => $piste->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Piste entities.
     *
     * @Route("/admin/parametres/pisteajax/liste", name="pisteajax")
     * 
     * 
     */
    public function pisteAjaxAction(Request $request)
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
        $rResult = $em->getRepository('AppBundle:Piste')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Piste')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Piste')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $actif = $aRow['actif'] ? 'Active' : 'Inactive';
            $output['aaData'][] = array($aRow['numero'],$actif,$aRow['repertoire'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_piste_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER_PRINCIPAL')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_piste_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_piste_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Piste entities.
     *
     * @Route("/admin/parametres/piste/download", name="piste_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Piste')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Piste".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Piste'.$date->format('Y_m_d_H_i_s').'.xls';
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
