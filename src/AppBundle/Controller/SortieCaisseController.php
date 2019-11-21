<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SortieCaisse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sortiecaisse controller.
 *
 * @Route("admin/gestion/centre/sorties")
 */
class SortieCaisseController extends Controller
{
    /**
     * Lists all sortieCaisse entities.
     *
     * @Route("/", name="admin_gestion_centre_sorties_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('sortiecaisse/index.html.twig');
    }

    /**
     * Creates a new sortieCaisse entity.
     *
     * @Route("/new", name="admin_gestion_centre_sorties_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $sortieCaisse = new Sortiecaisse();
        $form = $this->createForm('AppBundle\Form\SortieCaisseType', $sortieCaisse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            switch($sortieCaisse->traiter()){
                case 0 : $this->get('session')->getFlashBag()->add('error', 'Cette opération est interdite!');break;
                case 1: $this->get('session')->getFlashBag()->add('error', 'Le montant demandé est supérieur au solde du centre.');break;
                case 2 : $em->persist($sortieCaisse);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
                        return $this->redirectToRoute('admin_gestion_centre_sorties_show', array('id' => $sortieCaisse->getId()));
            }
        }
        return $this->render('sortiecaisse/new.html.twig', array(
            'sortieCaisse' => $sortieCaisse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a sortieCaisse entity.
     *
     * @Route("/{id}", name="admin_gestion_centre_sorties_show")
     * @Method("GET")
     */
    public function showAction(SortieCaisse $sortieCaisse)
    {
        if (!$sortieCaisse) {
            throw $this->createNotFoundException("La sortie demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($sortieCaisse);

        return $this->render('sortiecaisse/show.html.twig', array(
            'sortieCaisse' => $sortieCaisse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing sortieCaisse entity.
     *
     * @Route("/{id}/edit", name="admin_gestion_centre_sorties_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SortieCaisse $sortieCaisse)
    {
        if (!$sortieCaisse) {
            throw $this->createNotFoundException("La caisse demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($sortieCaisse);
        $editForm = $this->createForm('AppBundle\Form\SortieCaisseType', $sortieCaisse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_centre_sorties_edit', array('id' => $sortieCaisse->getId()));
        }

        return $this->render('sortiecaisse/edit.html.twig', array(
            'sortieCaisse' => $sortieCaisse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a sortieCaisse entity.
     *
     * @Route("/{id}", name="admin_gestion_centre_sorties_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SortieCaisse $sortieCaisse)
    {
        if(!$sortieCaisse) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($sortieCaisse->estSupprimable()) {
            $form = $this->createDeleteForm($sortieCaisse);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($sortieCaisse);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette sortie car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_gestion_centre_sorties_index');
    }
    
    /**
     * Deletes a sortieCaisse entity.
     *
     * @Route("/{id}/delete", name="admin_gestion_centre_sorties_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(SortieCaisse $sortieCaisse)
    {
        if (!$sortieCaisse) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($sortieCaisse->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($sortieCaisse);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette sortie car elle est utilisée');
        }
        return $this->redirectToRoute('admin_gestion_centre_sorties_index');
    }

    /**
     * Creates a form to delete a sortieCaisse entity.
     *
     * @param SortieCaisse $sortieCaisse The sortieCaisse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SortieCaisse $sortieCaisse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_centre_sorties_delete', array('id' => $sortieCaisse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all SortieCaisse entities.
     *
     * @Route("/admin/gestion/centre/sorties/liste", name="sortiecaisseajax")
     * 
     * 
     */
    public function sortiecaisseajaxAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.type', 'r.description', 'r.montant','r.dateCreation');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 5) ? intval($col) : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:SortieCaisse')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:SortieCaisse')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:SortieCaisse')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['type'], $aRow['description'], $aRow['montant'], $aRow['dateCreation'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_gestion_centre_sorties_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                //$action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_gestion_centre_sorties_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                //$action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_gestion_centre_sorties_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all SortieCaisse entities.
     *
     * @Route("/admin/gestion/centre/sorties/download", name="sortiecaisse_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:SortieCaisse')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("SortieCaisse".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'SortieCaisse'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("MONTANT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DATE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getType());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getMontant());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDateCreation());
            $ligne++;
        }
    }
    
}
