<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Modele;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Modele controller.
 *
 * @Route("admin/parametres/modele")
 */
class ModeleController extends Controller
{
    /**
     * Lists all modele entities.
     *
     * @Route("/", name="admin_parametres_modele_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('modele/index.html.twig');
    }

    /**
     * Creates a new modele entity.
     *
     * @Route("/new", name="admin_parametres_modele_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $modele = new Modele();
        $form = $this->createForm('AppBundle\Form\ModeleType', $modele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($modele);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_modele_show', array('id' => $modele->getId()));
        }

        return $this->render('modele/new.html.twig', array(
            'modele' => $modele,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a modele entity.
     *
     * @Route("/{id}", name="admin_parametres_modele_show")
     * @Method("GET")
     */
    public function showAction(Modele $modele)
    {
        if (!$modele) {
            throw $this->createNotFoundException("Le modèle demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($modele);

        return $this->render('modele/show.html.twig', array(
            'modele' => $modele,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing modele entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_modele_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Modele $modele)
    {
        if (!$modele) {
            throw $this->createNotFoundException("Le modèle demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($modele);
        $editForm = $this->createForm('AppBundle\Form\ModeleType', $modele);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_modele_edit', array('id' => $modele->getId()));
        }

        return $this->render('modele/edit.html.twig', array(
            'modele' => $modele,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Deletes a modele entity.
    *
    * @Route("/{id}", name="admin_parametres_modele_delete")
    * @Method("DELETE")
    */
    public function deleteAction(Request $request, Modele $modele)
    {
        if (!$modele) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($modele->estSupprimable()) {
            $form = $this->createDeleteForm($modele);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($modele);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce modèle car il est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_modele_index');
    }
    
    /**
     * Deletes a modele entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_modele_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Modele $modele)
    {
        if (!$modele) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($modele->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($modele);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce modèle car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_modele_index');
    }

    /**
     * Creates a form to delete a modele entity.
     *
     * @param Modele $modele The modele entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Modele $modele)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_modele_delete', array('id' => $modele->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Modele entities.
     *
     * @Route("/admin/parametres/modeleajax/liste", name="modeleajax")
     * 
     * 
     */
    public function modeleAjaxAction(Request $request)
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
        $rResult = $em->getRepository('AppBundle:Modele')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Modele')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Modele')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['code'], $aRow['marque'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_modele_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER_PRINCIPAL')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_modele_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_modele_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Modele entities.
     *
     * @Route("/admin/parametres/modele/download", name="modele_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Modele')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("MODELE".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'MODELE'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("MARQUE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());$col++;
            if($entity->getMarque() != null)
                $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getMarque()->getCode());$col++;
            $ligne++;
        }
    }
}
